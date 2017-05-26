<?php

/**
 * @license LGPLv3, http://opensource.org/licenses/LGPL-3.0
 * @copyright Aimeos (aimeos.org), 2017
 * @package MW
 * @subpackage Process
 */


namespace Aimeos\MW\Process;


/**
 * Posix process control for parallel processing classes
 *
 * @package MW
 * @subpackage Process
 */
class Pcntl implements Iface
{
	private $max;
	private $prio;
	private $list = [];


	/**
	 * Initializes the object and sets up the signal handler
	 *
	 * @param integer $max Maximum number of tasks allowed to run in parallel
	 * @param integer $prio Task priority from -20 (high) to 20 (low)
	 * @return void
	 * @throws \Aimeos\MW\Process\Exception If setting up the signal handler failed
	 */
	public function __construct( $max = 4, $prio = 19 )
	{
		$this->max = $max;
		$this->prio = $prio;

		if( php_sapi_name() === 'cli' && function_exists( 'pcntl_signal' )
			&& function_exists( 'pcntl_waitpid' ) && function_exists( 'posix_kill' )
		) {
			$handler = function( $signo )
			{
				foreach( $this->list as $pid => $entry )
				{
					posix_kill( $pid, $signo );
					pcntl_waitpid( $pid );
				}

				exit( 0 );
			};

			if( pcntl_signal( SIGTERM, $handler ) === false ) {
				throw new Exception( 'Unable to install signal handler: ' . pcntl_strerror( pcntl_get_last_error() ) );
			}
		}
	}


	/**
	 * Clears the cloned object
	 */
	public function __clone()
	{
		$this->list = [];
	}


	/**
	 * Checks if processing tasks in parallel is available
	 *
	 * @return boolean True if available, false if not
	 */
	public function isAvailable()
	{
		if( php_sapi_name() === 'cli' && function_exists( 'pcntl_fork' ) && function_exists( 'pcntl_wait' ) ) {
			return true;
		}

		return false;
	}


	/**
	 * Starts a new task by executing the given anonymous function
	 *
	 * @param \Closure $fcn Anonymous function to execute
	 * @param array $data List of parameters that is passed to the closure function
	 * @param boolean $restart True if the task should be restarted if it fails (only once)
	 * @return \Aimeos\MW\Process\Iface Self object for method chaining
	 * @throws \Aimeos\MW\Process\Exception If starting the new task failed
	 */
	public function start( \Closure $fcn, array $data, $restart = false )
	{
		while( count( $this->list ) >= $this->max ) {
			$this->waitOne();
		}

		foreach( $data as $key => $value )
		{
			if( is_object( $value ) ) {
				$data[$key] = clone $value;
			}
		}

		flush(); // flush all pending output so it's not printed in childs again

		if( ( $pid = pcntl_fork() ) === -1 ) {
			throw new Exception( 'Unable to fork new process: ' . pcntl_strerror( pcntl_get_last_error() ) );
		}

		if( $pid === 0 ) // child process
		{
			if( function_exists( 'pcntl_setpriority' ) ) {
				pcntl_setpriority( $this->prio );
			}

			for( $i = 0; $i < ob_get_level(); $i++ ) {
				ob_end_clean(); // avoid printing buffered messages of the parent again
			}

			try {
				call_user_func_array( $fcn, $data );
			} catch( \Exception $e ) {
				exit( 1 );
			}

			exit( 0 );
		}

		$this->list[$pid] = [$fcn, $data, $restart];

		return $this;
	}


	/**
	 * Waits for the running tasks until all have finished
	 *
	 * @return \Aimeos\MW\Process\Iface Self object for method chaining
	 */
	public function wait()
	{
		while( !empty( $this->list ) ) {
			$this->waitOne();
		}

		return $this;
	}


	/**
	 * Waits for the next running tasks to finish
	 *
	 * @return void
	 * @throws \Aimeos\MW\Process\Exception If an error occurs or the task exited with an error
	 */
	protected function waitOne()
	{
		$status = -1;

		if( ( $pid = pcntl_wait( $status ) ) === -1 ) {
			throw new Exception( 'Unable to wait for child process: ' . pcntl_strerror( pcntl_get_last_error() ) );
		}

		list( $fcn, $data, $restart ) = $this->list[$pid];
		unset( $this->list[$pid] );

		if( $status > 0 )
		{
			if( $restart === false ) {
				throw new Exception( sprintf( 'Process (PID "%1$s") failed with status "%2$s"', $pid, $status ) );
			}

			$this->start( $fcn, $data, false );
		}
	}
}