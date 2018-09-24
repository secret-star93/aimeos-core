<?php

/**
 * @license LGPLv3, http://opensource.org/licenses/LGPL-3.0
 * @copyright Metaways Infosystems GmbH, 2012
 * @copyright Aimeos (aimeos.org), 2015-2017
 * @package MShop
 * @subpackage Index
 */


namespace Aimeos\MShop\Index\Manager\Text;


/**
 * MySQL based index text for searching in product tables.
 *
 * @package MShop
 * @subpackage Index
 */
class MySQL
	extends \Aimeos\MShop\Index\Manager\Text\Standard
{
	private $searchConfig = array(
		// @deprecated Removed 2019.01
		'index.text.id' => array(
			'code' => 'index.text.id',
			'internalcode' => 'mindte."textid"',
			'internaldeps'=>array( 'LEFT JOIN "mshop_index_text" AS mindte
				USE INDEX ("idx_msindte_value", "idx_msindte_p_s_lt_la_ty_do_va") ON mindte."prodid" = mpro."id"' ),
			'label' => 'Product index text ID',
			'type' => 'string',
			'internaltype' => \Aimeos\MW\DB\Statement\Base::PARAM_STR,
			'public' => false,
		),
		'index.text.relevance' => array(
			'code' => 'index.text.relevance()',
			'internalcode' => ':site AND mindte."listtype" IN ($1)
				AND ( mindte."langid" = $2 OR mindte."langid" IS NULL )
				AND MATCH( mindte."value" ) AGAINST( $3 IN BOOLEAN MODE )',
			'label' => 'Product texts, parameter(<list type code>,<language ID>,<search term>)',
			'type' => 'float',
			'internaltype' => \Aimeos\MW\DB\Statement\Base::PARAM_FLOAT,
			'public' => false,
		),
		// @deprecated Removed 2019.01, Results are ordered by default
		'sort:index.text.relevance' => array(
			'code' => 'sort:index.text.relevance()',
			'internalcode' => 'MATCH( mindte."value" ) AGAINST( $3 IN BOOLEAN MODE )',
			'label' => 'Product texts, parameter(<list type code>,<language ID>,<search term>)',
			'type' => 'float',
			'internaltype' => \Aimeos\MW\DB\Statement\Base::PARAM_FLOAT,
			'public' => false,
		),
	);


	/**
	 * Initializes the object
	 *
	 * @param \Aimeos\MShop\Context\Item\Iface $context Context object
	 */
	public function __construct( \Aimeos\MShop\Context\Item\Iface $context )
	{
		parent::__construct( $context );

		$site = $context->getLocale()->getSitePath();

		$func = function( array $params ) {

			if( isset( $params[2] ) )
			{
				$str = '';
				$list = ['-', '+', '>', '<', '(', ')', '~', '*', ':', '"', '&', '|', '!', '/', '§', '$', '%', '{', '}', '[', ']', '=', '?', '\\', '\'', '#', ';', '.', ',', '@'];
				$search = str_replace( $list, ' ', $params[2] );

				foreach( explode( ' ', $search ) as $part )
				{
					$len = strlen( $part );

					if( $len > 0 ) {
						$str .= ' +' . $part . '*';
					}
				}

				$params[2] = '\'' . $str . '\'';
			}

			return $params;
		};

		$this->searchConfig['index.text.relevance']['function'] = $func;
		$this->searchConfig['sort:index.text.relevance']['function'] = $func;

		$this->replaceSiteMarker( $this->searchConfig['index.text.relevance'], 'mindte."siteid"', $site );
	}


	/**
	 * Returns a list of objects describing the available criterias for searching.
	 *
	 * @param boolean $withsub Return also attributes of sub-managers if true
	 * @return array List of items implementing \Aimeos\MW\Criteria\Attribute\Iface
	 */
	public function getSearchAttributes( $withsub = true )
	{
		$list = parent::getSearchAttributes( $withsub );

		foreach( $this->searchConfig as $key => $fields ) {
			$list[$key] = new \Aimeos\MW\Criteria\Attribute\Standard( $fields );
		}

		return $list;
	}
}