<?php

/**
 * @copyright Copyright (c) Metaways Infosystems GmbH, 2012
 * @license LGPLv3, http://www.arcavias.com/license
 * @package MShop
 * @subpackage Plugin
 */


/**
 * Checks the products in a basket for changed prices.
 *
 * @package MShop
 * @subpackage Plugin
 */
class MShop_Plugin_Provider_Order_ProductPrice
	extends MShop_Plugin_Provider_Order_Abstract
	implements MShop_Plugin_Provider_Interface
{


	/**
	 * Subscribes itself to a publisher
	 *
	 * @param MW_Observer_Publisher_Interface $p Object implementing publisher interface
	 */
	public function register( MW_Observer_Publisher_Interface $p )
	{
		$p->addListener( $this, 'check.after' );
	}


	/**
	 * Receives a notification from a publisher object
	 *
	 * @param MW_Observer_Publisher_Interface $order Shop basket instance implementing publisher interface
	 * @param string $action Name of the action to listen for
	 * @param mixed $value Object or value changed in publisher
	 * @throws MShop_Plugin_Provider_Exception if checks fail
	 * @return bool true if checks succeed
	 */
	public function update( MW_Observer_Publisher_Interface $order, $action, $value = null )
	{
		$context = $this->_getContext();

		$context->getLogger()->log(__METHOD__ . ': event=' . $action, MW_Logger_Abstract::DEBUG);

		$class = 'MShop_Order_Item_Base_Interface';
		if( !( $order instanceof $class ) ) {
			throw new MShop_Plugin_Order_Exception( sprintf( 'Object is not of required type "%1$s"', $class ) );
		}

		if( !( $value & MShop_Order_Item_Base_Abstract::PARTS_PRODUCT ) ) {
			return true;
		}


		$productManager = MShop_Factory::createManager( $context, 'product' );
		$priceManager = MShop_Factory::createManager( $context, 'price' );

		$attrIds = $attributes = $prodCodes = $prodMap = $changedProducts = array();
		$orderProducts = $order->getProducts();

		foreach( $orderProducts as $pos => $item )
		{
			if( $item->getFlags() & MShop_Order_Item_Base_Product_Abstract::FLAG_IMMUTABLE != 0 ) {
				continue;
			}

			$prodCodes[] = $item->getProductCode();

			foreach( $item->getAttributes() as $ordAttrItem )
			{
				if( ( $id = $ordAttrItem->getAttributeId() ) != '' ) {
					$attrIds[$id] = null;
				}
			}
		}


		if( !empty( $attrIds ) )
		{
			$attrManager = MShop_Factory::createManager( $context, 'attribute' );

			$search = $attrManager->createSearch( true );
			$expr = array(
				$search->compare( '==', 'attribute.id', array_keys( $attrIds ) ),
				$search->getConditions(),
			);
			$search->setConditions( $search->combine( '&&', $expr ) );

			$attributes = $attrManager->searchItems( $search, array( 'price' ) );
		}


		if( !empty( $prodCodes ) )
		{
			$search = $productManager->createSearch( true );
			$expr = array(
				$search->compare( '==', 'product.code', $prodCodes ),
				$search->getConditions(),
			);
			$search->setConditions( $search->combine( '&&', $expr ) );

			$products = $productManager->searchItems( $search, array( 'price' ) );

			foreach( $products as $item ) {
				$prodMap[ $item->getCode() ] = $item;
			}
		}


		foreach( $orderProducts as $pos => $orderProduct )
		{
			$refPrices = array();

			if( $orderProduct->getFlags() & MShop_Order_Item_Base_Product_Abstract::FLAG_IMMUTABLE != 0 ) {
				continue;
			}
			// fetch prices of articles/sub-products
			if( isset( $prodMap[ $orderProduct->getProductCode() ] ) ) {
				$refPrices = $prodMap[ $orderProduct->getProductCode() ]->getRefItems( 'price', 'default', 'default' );
			}

			// fetch prices of selection/parent products
			if( empty( $refPrices ) )
			{
				$product = $productManager->getItem( $orderProduct->getProductId(), array( 'price' ) );
				$refPrices = $product->getRefItems( 'price', 'default', 'default' );
			}

			if( empty( $refPrices ) )
			{
				$pid = $orderProduct->getProductId();
				$pcode = $orderProduct->getProductCode();
				$codes = array( 'product' => array( $pos => 'product.price' ) );
				$msg = sprintf( 'No price for product ID "%1$s" or product code "%2$s" available', $pid, $pcode );

				throw new MShop_Plugin_Provider_Exception( $msg, -1, null, $codes );
			}

			$price = $priceManager->getLowestPrice( $refPrices, $orderProduct->getQuantity() );

			// add prices of product attributes to compute the end price for comparison
			foreach( $orderProduct->getAttributes() as $orderAttribute )
			{
				$attrPrices = array();
				$attrId = $orderAttribute->getAttributeId();

				if( isset( $attributes[$attrId] ) ) {
					$attrPrices = $attributes[$attrId]->getRefItems( 'price', 'default', 'default' );
				}

				if( !empty( $attrPrices ) ) {
					$price->addItem( $priceManager->getLowestPrice( $attrPrices, $orderProduct->getQuantity() ) );
				}
			}

			$orderPosPrice = $orderProduct->getPrice();

			if( ( $orderPosPrice->getValue() !== $price->getValue()
				|| $orderPosPrice->getCosts() !== $price->getCosts()
				|| $orderPosPrice->getTaxrate() !== $price->getTaxrate() )
				&& $orderProduct->getFlags() !== MShop_Order_Item_Base_Product_Abstract::FLAG_IMMUTABLE )
			{
				$orderProduct->setPrice( $price );

				$order->deleteProduct( $pos );
				$order->addProduct( $orderProduct, $pos );

				$changedProducts[$pos] = 'price.changed';
			}
		}

		if ( count( $changedProducts ) > 0 )
		{
			$code = array( 'product' => $changedProducts );
			$msg = sprintf( 'Please have a look at the prices of the products in your basket' );
			throw new MShop_Plugin_Provider_Exception( $msg, -1, null, $code );
		}

		return true;
	}
}