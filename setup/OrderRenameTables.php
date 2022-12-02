<?php

/**
 * @license LGPLv3, https://opensource.org/licenses/LGPL-3.0
 * @copyright Aimeos (aimeos.org), 2022
 */


namespace Aimeos\Upscheme\Task;


class OrderRenameTables extends Base
{
	public function before() : array
	{
		return [
			'OrderAddProductParentid', 'OrderAddBaseServiceCurrencyid', 'OrderAddBaseProductCurrencyid',
			'OrderRenameAttributeParentid', 'OrderRenameProductStatus', 'OrderRenameProductSupplier'
		];
	}


	public function up()
	{
		$this->constraints()->indexes()->tables();
	}


	protected function constraints()
	{
		$this->db( 'db-order' )
			->dropForeign( 'mshop_order_base_product_attr', 'fk_msordbaprat_parentid' )
			->dropForeign( 'mshop_order_base_service_attr', 'fk_msordbaseat_parentid' )
			->dropForeign( 'mshop_order_base_service_tx', 'fk_msordbasetx_parentid' );

		return $this;
	}


	protected function indexes()
	{
		$this->db( 'db-order' )
			->dropIndex( 'mshop_order_base_address', 'unq_msordbaad_bid_type' )
			->dropIndex( 'mshop_order_base_address', 'idx_msordbaad_bid_lname' )
			->dropIndex( 'mshop_order_base_address', 'idx_msordbaad_bid_addr1' )
			->dropIndex( 'mshop_order_base_address', 'idx_msordbaad_bid_postal' )
			->dropIndex( 'mshop_order_base_address', 'idx_msordbaad_bid_city' )
			->dropIndex( 'mshop_order_base_address', 'idx_msordbaad_bid_email' )
			->dropIndex( 'mshop_order_base_coupon', 'idx_msordbaco_bid_code' )
			->dropIndex( 'mshop_order_base_product', 'unq_msordbapr_bid_pos' )
			->dropIndex( 'mshop_order_base_product', 'idx_msordbapr_bid_pid' )
			->dropIndex( 'mshop_order_base_product', 'idx_msordbapr_bid_pcd' )
			->dropIndex( 'mshop_order_base_product', 'idx_msordbapr_bid_qtyo' )
			->dropIndex( 'mshop_order_base_product', 'idx_msordbapr_ct_pid_bid' )
			->dropIndex( 'mshop_order_base_service', 'unq_msordbase_bid_cd_typ_sid' )
			->dropIndex( 'mshop_order_base_service', 'idx_msordbase_code_type_sid' )
			->dropIndex( 'mshop_order_base_product_attr', 'unq_msordbaprat_oid_aid_ty_cd' )
			->dropIndex( 'mshop_order_base_product_attr', 'idx_msordbaprat_si_cd_va' )
			->dropIndex( 'mshop_order_base_service_attr', 'unq_msordbaseat_oid_aid_ty_cd' )
			->dropIndex( 'mshop_order_base_service_attr', 'idx_msordbaseat_si_cd_va' );

		return $this;
	}


	protected function tables()
	{
		$this->db( 'db-order' )
			->renameTable( 'mshop_order_base_address', 'mshop_order_address' )
			->renameTable( 'mshop_order_base_coupon', 'mshop_order_coupon' )
			->renameTable( 'mshop_order_base_product', 'mshop_order_product' )
			->renameTable( 'mshop_order_base_product_attr', 'mshop_order_product_attr' )
			->renameTable( 'mshop_order_base_service', 'mshop_order_service' )
			->renameTable( 'mshop_order_base_service_attr', 'mshop_order_service_attr' )
			->renameTable( 'mshop_order_base_service_tx', 'mshop_order_service_tx' );

		return $this;
	}
}
