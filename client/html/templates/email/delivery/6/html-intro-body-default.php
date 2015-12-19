<?php

/**
 * @copyright Copyright (c) Metaways Infosystems GmbH, 2014
 * @license LGPLv3, http://opensource.org/licenses/LGPL-3.0
 */

$enc = $this->encoder();
$order = $this->extOrderItem;

/// Delivery e-mail intro with order ID (%1$s), order date (%2$s) and delivery status (%3%s)
$msg = $this->translate( 'client', 'The parcel for your order %1$s from %2$s could not be delivered.' );

$key = 'stat:' . $order->getDeliveryStatus();
$status = $this->translate( 'client/code', $key );
$format = $this->translate( 'client', 'Y-m-d' );

$string = sprintf( $msg, $order->getId(), date_create( $order->getTimeCreated() )->format( $format ), $status );

?>
<p class="email-common-intro content-block">
<?php echo $enc->html( nl2br( $string ), $enc::TRUST ); ?>
<?php echo $this->get( 'introBody' ); ?>
</p>
