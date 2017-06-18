<?php

/**
 * @license LGPLv3, http://opensource.org/licenses/LGPL-3.0
 * @copyright Metaways Infosystems GmbH, 2011
 * @copyright Aimeos (aimeos.org), 2015-2017
 * @package MShop
 * @subpackage Plugin
 */


namespace Aimeos\MShop\Plugin\Provider\Order;


/**
 * Free shipping implementation if ordered product sum is above a certain value
 *
 * Sets the shipping costs to zero if the configured threshold is met or exceeded.
 * Only the costs of the delivery option are set to 0.00, not the shipping costs
 * of specific product items!
 *
 * Example:
 * - threshold: 'EUR' => '50.00'
 *
 * There would be no shipping costs for orders of 50 EUR or above. The rebates
 * granted by coupons for example are included into the calculation of the total
 * basket value.
 *
 * To trace the execution and interaction of the plugins, set the log level to DEBUG:
 *	madmin/log/manager/standard/loglevel = 7
 *
 * @package MShop
 * @subpackage Plugin
 * @deprecated Use Reduction service decorator for each delivery option instead
 */
class Shipping
	extends \Aimeos\MShop\Plugin\Provider\Factory\Base
	implements \Aimeos\MShop\Plugin\Provider\Factory\Iface
{
	/**
	 * Subscribes itself to a publisher
	 *
	 * @param \Aimeos\MW\Observer\Publisher\Iface $p Object implementing publisher interface
	 */
	public function register( \Aimeos\MW\Observer\Publisher\Iface $p )
	{
		$p->addListener( $this->getObject(), 'addProduct.after' );
		$p->addListener( $this->getObject(), 'deleteProduct.after' );
		$p->addListener( $this->getObject(), 'setService.after' );
		$p->addListener( $this->getObject(), 'addCoupon.after' );
		$p->addListener( $this->getObject(), 'deleteCoupon.after' );
	}


	/**
	 * Receives a notification from a publisher object
	 *
	 * @param \Aimeos\MW\Observer\Publisher\Iface $order Shop basket instance implementing publisher interface
	 * @param string $action Name of the action to listen for
	 * @param mixed $value Object or value changed in publisher
	 */
	public function update( \Aimeos\MW\Observer\Publisher\Iface $order, $action, $value = null )
	{
		if( !( $order instanceof \Aimeos\MShop\Order\Item\Base\Iface ) )
		{
			$msg = $this->getContext()->getI18n()->dt( 'mshop', 'Object is not of required type "%1$s"' );
			throw new \Aimeos\MShop\Plugin\Exception( sprintf( $msg, '\Aimeos\MShop\Order\Item\Base\Iface' ) );
		}

		$config = $this->getItemBase()->getConfig();
		if( !isset( $config['threshold'] ) ) { return true; }

		try {
			$delivery = $order->getService( 'delivery' );
		} catch( \Aimeos\MShop\Order\Exception $oe ) {
			// no delivery item available yet
			return true;
		}

		$this->checkThreshold( $order, $delivery->getPrice(), $config['threshold'] );

		return true;
	}


	/**
	 * Tests if the shipping threshold is reached and updates the price accordingly
	 *
	 * @param \Aimeos\MShop\Order\Item\Base\Iface $order Basket object
	 * @param \Aimeos\MShop\Price\Item\Iface $price Delivery price item
	 * @param array $threshold Associative list of currency/threshold pairs
	 */
	protected function checkThreshold( \Aimeos\MShop\Order\Item\Base\Iface $order,
		\Aimeos\MShop\Price\Item\Iface $price, array $threshold )
	{
		$currency = $price->getCurrencyId();

		if( !isset( $threshold[$currency] ) ) {
			return;
		}

		$sum = \Aimeos\MShop\Factory::createManager( $this->getContext(), 'price' )->createItem();

		foreach( $order->getProducts() as $product ) {
			$sum->addItem( $product->getPrice(), $product->getQuantity() );
		}

		if( $sum->getValue() + $sum->getRebate() >= $threshold[$currency] && $price->getCosts() > '0.00' )
		{
			$price->setRebate( $price->getCosts() );
			$price->setCosts( '0.00' );
		}
		else if( $sum->getValue() + $sum->getRebate() < $threshold[$currency] && $price->getRebate() > '0.00' )
		{
			$price->setCosts( $price->getRebate() );
			$price->setRebate( '0.00' );
		}
	}
}
