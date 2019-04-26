<?php

/**
 * @license LGPLv3, http://opensource.org/licenses/LGPL-3.0
 * @copyright Aimeos (aimeos.org), 2019
 */

/* Available data:
 * - orderItems : List of order items
 * - baseItems : List of order base items
 */

$enc = $this->encoder();
$baseItems = $this->baseItems;

?>
<orderitem>
	<?php foreach( $this->orderItems as $id => $item ) : ?>
		<invoice>
			<?php foreach( $item->toArray() as $key => $value ) : ?>
				<<?= $key ?>><![CDATA[<?= $value ?>]]></<?= $key ?>>
			<?php endforeach ?>
		</invoice>
		<?php if( isset( $baseItems[$item->getBaseId()] ) ) : $baseItem = $baseItems[$item->getBaseId()] ?>

			<base>
				<?php foreach( $baseItem->toArray() as $key => $value ) : ?>
					<<?= $key ?>><![CDATA[<?= $value ?>]]></<?= $key ?>>
				<?php endforeach ?>
			</base>

			<address>
				<?php foreach( $baseItem->getAddresses() as $type => $list ) : ?>
					<?php foreach( $list as $addressItem ) : ?>
						<addressitem type="<?= $enc->attr( $addressItem->getType() ) ?>" position="<?= $enc->attr( $addressItem->getPosition() ) ?>">
							<?php foreach( $addressItem->toArray() as $key => $value ) : ?>
								<<?= $key ?>><![CDATA[<?= $value ?>]]></<?= $key ?>>
							<?php endforeach ?>
						</addressitem>
					<?php endforeach ?>
				<?php endforeach ?>
			</address>

			<product>
				<?php foreach( $baseItem->getProducts() as $productItem ) : ?>
					<productitem position="<?= $enc->attr( $productItem->getPosition() ) ?>">
						<?php foreach( $productItem->toArray() as $key => $value ) : ?>
							<<?= $key ?>><![CDATA[<?= $value ?>]]></<?= $key ?>>
						<?php endforeach ?>
						<attribute>
							<?php foreach( $productItem->getAttributeItems() as $attributeItem ) : ?>
								<attributeitem>
									<?php foreach( $attributeItem->toArray() as $key => $value ) : ?>
										<<?= $key ?>><![CDATA[<?= $value ?>]]></<?= $key ?>>
									<?php endforeach ?>
								</attributeitem>
							<?php endforeach ?>
						</attribute>
						<product>
							<?php foreach( $productItem->getProducts() as $subprodItem ) : ?>
								<productitem position="<?= $enc->attr( $subprodItem->getPosition() ) ?>">
									<?php foreach( $subprodItem->toArray() as $key => $value ) : ?>
										<<?= $key ?>><![CDATA[<?= $value ?>]]></<?= $key ?>>
									<?php endforeach ?>
									<attribute>
										<?php foreach( $subprodItem->getAttributeItems() as $attributeItem ) : ?>
											<attributeitem>
												<?php foreach( $attributeItem->toArray() as $key => $value ) : ?>
													<<?= $key ?>><![CDATA[<?= $value ?>]]></<?= $key ?>>
												<?php endforeach ?>
											</attributeitem>
										<?php endforeach ?>
									</attribute>
									<product>
									</product>
								</productitem>
							<?php endforeach ?>
						</product>
					</productitem>
				<?php endforeach ?>
			</product>

			<service>
				<?php foreach( $baseItem->getServices() as $type => $list ) : ?>
					<?php foreach( $list as $serviceItem ) : ?>
						<serviceitem type="<?= $enc->attr( $serviceItem->getType() ) ?>" position="<?= $enc->attr( $serviceItem->getPosition() ) ?>">
							<?php foreach( $serviceItem->toArray() as $key => $value ) : ?>
								<<?= $key ?>><![CDATA[<?= $value ?>]]></<?= $key ?>>
							<?php endforeach ?>
							<attribute>
								<?php foreach( $serviceItem->getAttributeItems() as $attributeItem ) : ?>
									<attributeitem>
										<?php foreach( $attributeItem->toArray() as $key => $value ) : ?>
											<<?= $key ?>><![CDATA[<?= $value ?>]]></<?= $key ?>>
										<?php endforeach ?>
									</attributeitem>
								<?php endforeach ?>
							</attribute>
						</serviceitem>
					<?php endforeach ?>
				<?php endforeach ?>
			</service>

			<coupon>
				<?php foreach( $baseItem->getCoupons() as $coupon => $list ) : ?>
					<couponitem>
						<order.base.coupon.code><![CDATA[<?= $coupon ?>]]></order.base.coupon.code>
					</couponitem>
				<?php endforeach ?>
			</coupon>

		<?php endif ?>
	<?php endforeach ?>
</orderitem>