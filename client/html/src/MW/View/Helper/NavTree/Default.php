<?php

/**
 * @copyright Copyright (c) Metaways Infosystems GmbH, 2012
 * @license LGPLv3, http://opensource.org/licenses/LGPL-3.0
 * @package MW
 * @subpackage View
 */


/**
 * View helper class for generating the navigation tree.
 *
 * @package MW
 * @subpackage View
 * @deprecated 2016.01
 */
class MW_View_Helper_NavTree_Default
	extends MW_View_Helper_Abstract
	implements MW_View_Helper_Interface
{
	private $target;
	private $controller;
	private $action;
	private $config;
	private $encoder;
	private $contentUrl;


	/**
	 * Initializes the view helper classes.
	 *
	 * @param MW_View_Interface $view View instance with registered view helpers
	 */
	public function __construct( MW_View_Interface $view )
	{
		parent::__construct( $view );

		$this->target = $view->config( 'client/html/catalog/list/url/target' );
		$this->controller = $view->config( 'client/html/catalog/list/url/controller', 'catalog' );
		$this->action = $view->config( 'client/html/catalog/list/url/action', 'list' );
		$this->config = $view->config( 'client/html/catalog/list/url/config', array() );
		$this->contentUrl = $this->config( 'client/html/common/content/baseurl' );

		$this->encoder = $view->encoder();
	}


	/**
	 * Returns the HTML for the navigation tree.
	 *
	 * @param MShop_Catalog_Item_Interface $item Catalog item with child nodes
	 * @param array Associative list of catalog IDs as keys and catalog nodes as values
	 * @param array Associative list of parameters used for filtering
	 * @return string Rendered HTML of the navigation tree
	 */
	public function transform( MShop_Catalog_Item_Interface $item, array $path, array $params = array() )
	{
		if( $item->getStatus() <= 0 ) {
			return '';
		}

		$id = $item->getId();
		$enc = $this->encoder;
		$config = $item->getConfig();

		$class = ( $item->hasChildren() ? ' withchild' : ' nochild' );
		$class .= ( isset( $path[$item->getId()] ) ? ' active' : '' );
		$class .= ( isset( $config['css-class'] ) ? ' ' . $config['css-class'] : '' );

		$params['f_name'] = $item->getName( 'url' );
		$params['f_catid'] = $id;

		$url = $enc->attr( $this->getView()->url( $this->target, $this->controller, $this->action, $params, array(), $this->config ) );

		$output = '<li class="cat-item catid-' . $enc->attr( $id . $class ) . '" data-id="' . $id . '" >';
		$output .= '<a class="cat-item" href="' . $url . '"><div class="media-list">';

		foreach( $item->getListItems( 'media', 'icon' ) as $listItem )
		{
			if( ( $mediaItem = $listItem->getRefItem() ) !== null ) {
				$output .= $this->media( $mediaItem, $this->contentUrl, array( 'class' => 'media-item' ) );
			}
		}

		$output .= '</div><span class="cat-name">' . $enc->html( $item->getName(), $enc::TRUST ) . '</span></a>';

		$children = $item->getChildren();

		if( !empty( $children ) )
		{
			$output .= '<ul class="level-' . $enc->attr( $item->getNode()->level + 1 ) . '">';

			foreach( $children as $child ) {
				$output .= $this->transform( $child, $path, $params );
			}

			$output .= '</ul>';
		}

		$output .= '</li>';

		return $output;
	}
}