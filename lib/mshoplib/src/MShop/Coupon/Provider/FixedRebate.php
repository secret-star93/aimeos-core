<?php

/**
 * @copyright Copyright (c) Metaways Infosystems GmbH, 2012
 * @license LGPLv3, http://www.arcavias.com/en/license
 * @package MShop
 * @subpackage Coupon
 */


/**
 * Fixed price coupon model.
 *
 * @package MShop
 * @subpackage Coupon
 */
class MShop_Coupon_Provider_FixedRebate
	extends MShop_Coupon_Provider_Abstract
	implements MShop_Coupon_Provider_Factory_Interface
{
	/**
	 * Adds the result of a coupon to the order base instance.
	 *
	 * @param MShop_Order_Item_Base_Interface $base Basic order of the customer
	 */
	public function addCoupon( MShop_Order_Item_Base_Interface $base )
	{
		if( $this->_getObject()->isAvailable( $base ) === false ) {
			return;
		}

		$rebate = '0.00';
		$currency = $base->getPrice()->getCurrencyId();
		$config = $this->_getItem()->getConfig();

		if( !isset( $config['fixedrebate.productcode'] ) || !isset( $config['fixedrebate.rebate'] ) )
		{
			throw new MShop_Coupon_Exception( sprintf(
				'Invalid configuration for coupon provider "%1$s", needs "%2$s"',
				$this->_getItem()->getProvider(), 'fixedrebate.productcode, fixedrebate.rebate'
			) );
		}

		if( is_array( $config['fixedrebate.rebate'] ) )
		{
			if( isset( $config['fixedrebate.rebate'][$currency] ) ) {
				$rebate = $config['fixedrebate.rebate'][$currency];
			}
		}
		else
		{
			$rebate = $config['fixedrebate.rebate'];
		}


		$priceManager = MShop_Factory::createManager( $this->_getContext(), 'price' );
		$prices = $this->_getPriceByTaxRate( $base );
		$orderProducts = array();

		krsort( $prices );

		if( empty( $prices ) ) {
			$prices = array( '0.00' => $priceManager->createItem() );
		}

		foreach( $prices as $taxrate => $price )
		{
			if( abs( $rebate ) < 0.01 ) {
				break;
			}

			$amount = $price->getValue() + $price->getCosts();

			if( $amount > 0 && $amount < $rebate )
			{
				$value = $price->getValue() + $price->getCosts();
				$rebate -= $value;
			}
			else
			{
				$value = $rebate;
				$rebate = '0.00';
			}

			$price = $priceManager->createItem();
			$price->setValue( -$value );
			$price->setRebate( $value );
			$price->setTaxRate( $taxrate );

			$orderProduct = $this->_createProduct( $config['fixedrebate.productcode'], 1 );
			$orderProduct->setPrice( $price );

			$orderProducts[] = $orderProduct;
		}

		$base->addCoupon( $this->_getCode(), $orderProducts );
	}
}
