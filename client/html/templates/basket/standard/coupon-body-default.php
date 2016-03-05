<?php

/**
 * @copyright Copyright (c) Metaways Infosystems GmbH, 2014
 * @license LGPLv3, http://opensource.org/licenses/LGPL-3.0
 */

$basketTarget = $this->config( 'client/html/basket/standard/url/target' );
$basketController = $this->config( 'client/html/basket/standard/url/controller', 'basket' );
$basketAction = $this->config( 'client/html/basket/standard/url/action', 'index' );
$basketConfig = $this->config( 'client/html/basket/standard/url/config', array() );

$enc = $this->encoder();
$allowed = $this->config( 'client/html/basket/standard/coupon/allowed', 1 );

$coupons = array();
if( isset( $this->standardBasket ) ) {
	$coupons = $this->standardBasket->getCoupons();
}

?>
<?php $this->block()->start( 'basket/stardard/coupon' ); ?>
<div class="basket-standard-coupon container">
	<div class="header">
		<h2><?php echo $enc->html( $this->translate( 'client', 'Coupon codes' ) ); ?></h2>
	</div>
	<div class="content">
<?php if( $allowed > count( $coupons ) ) : ?>
		<div class="coupon-new">
			<input class="coupon-code" name="<?php echo $enc->attr( $this->formparam( 'b_coupon' ) ); ?>" type="text" maxlength="255" />
			<button class="standardbutton" type="submit"><?php echo $enc->html( $this->translate( 'client', '+' ) ); ?></button>
		</div>
<?php endif; ?>
<?php if( !empty( $coupons ) ) : ?>
		<ul class="attr-list">
<?php	foreach( $coupons as $code => $products ) : ?>
			<li class="attr-item">
				<span class="coupon-code"><?php echo $enc->html( $code ); ?></span>
				<a class="minibutton change" href="<?php echo $enc->attr( $this->url( $basketTarget, $basketController, $basketAction, array( 'b_action' => 'coupon-delete', 'b_coupon' => $code ), array(), $basketConfig ) ); ?>">
					<?php echo $enc->html( $this->translate( 'client', 'X' ) ); ?>
				</a>
			</li>
<?php	endforeach; ?>
		</ul>
<?php endif; ?>
	</div>
<?php echo $this->get( 'couponBody' ); ?>
</div>
<?php $this->block()->stop(); ?>
<?php echo $this->block()->get( 'basket/stardard/coupon' ); ?>
