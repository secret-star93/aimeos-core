<?php

/**
 * @license LGPLv3, http://opensource.org/licenses/LGPL-3.0
 * @copyright Metaways Infosystems GmbH, 2012
 * @copyright Aimeos (aimeos.org), 2015-2016
 * @package MShop
 * @subpackage Coupon
 */


namespace Aimeos\MShop\Coupon\Provider;


/**
 * Gift/present coupon model.
 *
 * @package MShop
 * @subpackage Coupon
 */
class Present
	extends \Aimeos\MShop\Coupon\Provider\Factory\Base
	implements \Aimeos\MShop\Coupon\Provider\Factory\Iface
{
	private $beConfig = array(
		'present.productcode' => array(
			'code' => 'present.productcode',
			'internalcode'=> 'present.productcode',
			'label'=> 'Product code of the rebate product',
			'type'=> 'string',
			'internaltype'=> 'string',
			'default'=> '',
			'required'=> true,
		),
		'present.quantity' => array(
			'code' => 'present.quantity',
			'internalcode'=> 'present.quantity',
			'label'=> 'Number of articles that will be added to the basket',
			'type'=> 'number',
			'internaltype'=> 'integer',
			'default'=> 1,
			'required'=> true,
		),
	);


	/**
	 * Adds the result of a coupon to the order base instance.
	 *
	 * @param \Aimeos\MShop\Order\Item\Base\Iface $base Basic order of the customer
	 */
	public function addCoupon( \Aimeos\MShop\Order\Item\Base\Iface $base )
	{
		if( $this->getObject()->isAvailable( $base ) === false ) {
			return;
		}

		$config = $this->getItemBase()->getConfig();

		if( !isset( $config['present.productcode'] ) || !isset( $config['present.quantity'] ) )
		{
			throw new \Aimeos\MShop\Coupon\Exception( sprintf(
				'Invalid configuration for coupon provider "%1$s", needs "%2$s"',
				$this->getItemBase()->getProvider(), 'present.productcode, present.quantity'
			) );
		}

		$orderProduct = $this->createProduct( $config['present.productcode'], $config['present.quantity'] );

		$base->addCoupon( $this->getCode(), array( $orderProduct ) );
	}


	/**
	 * Checks the backend configuration attributes for validity.
	 *
	 * @param array $attributes Attributes added by the shop owner in the administraton interface
	 * @return array An array with the attribute keys as key and an error message as values for all attributes that are
	 * 	known by the provider but aren't valid
	 */
	public function checkConfigBE( array $attributes )
	{
		return $this->checkConfig( $this->beConfig, $attributes );
	}


	/**
	 * Returns the configuration attribute definitions of the provider to generate a list of available fields and
	 * rules for the value of each field in the administration interface.
	 *
	 * @return array List of attribute definitions implementing \Aimeos\MW\Common\Critera\Attribute\Iface
	 */
	public function getConfigBE()
	{
		return $this->getConfigItems( $this->beConfig );
	}
}
