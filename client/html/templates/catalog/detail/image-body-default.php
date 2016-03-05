<?php

/**
 * @copyright Copyright (c) Metaways Infosystems GmbH, 2012
 * @license LGPLv3, http://opensource.org/licenses/LGPL-3.0
 */

$enc = $this->encoder();

/** client/html/catalog/detail/url/target
 * Destination of the URL where the controller specified in the URL is known
 *
 * The destination can be a page ID like in a content management system or the
 * module of a software development framework. This "target" must contain or know
 * the controller that should be called by the generated URL.
 *
 * @param string Destination of the URL
 * @since 2014.03
 * @category Developer
 * @see client/html/catalog/detail/url/controller
 * @see client/html/catalog/detail/url/action
 * @see client/html/catalog/detail/url/config
 */
$detailTarget = $this->config( 'client/html/catalog/detail/url/target' );

/** client/html/catalog/detail/url/controller
 * Name of the controller whose action should be called
 *
 * In Model-View-Controller (MVC) applications, the controller contains the methods
 * that create parts of the output displayed in the generated HTML page. Controller
 * names are usually alpha-numeric.
 *
 * @param string Name of the controller
 * @since 2014.03
 * @category Developer
 * @see client/html/catalog/detail/url/target
 * @see client/html/catalog/detail/url/action
 * @see client/html/catalog/detail/url/config
 */
$detailController = $this->config( 'client/html/catalog/detail/url/controller', 'catalog' );

/** client/html/catalog/detail/url/action
 * Name of the action that should create the output
 *
 * In Model-View-Controller (MVC) applications, actions are the methods of a
 * controller that create parts of the output displayed in the generated HTML page.
 * Action names are usually alpha-numeric.
 *
 * @param string Name of the action
 * @since 2014.03
 * @category Developer
 * @see client/html/catalog/detail/url/target
 * @see client/html/catalog/detail/url/controller
 * @see client/html/catalog/detail/url/config
 */
$detailAction = $this->config( 'client/html/catalog/detail/url/action', 'detail' );

/** client/html/catalog/detail/url/config
 * Associative list of configuration options used for generating the URL
 *
 * You can specify additional options as key/value pairs used when generating
 * the URLs, like
 *
 *  client/html/<clientname>/url/config = array( 'absoluteUri' => true )
 *
 * The available key/value pairs depend on the application that embeds the e-commerce
 * framework. This is because the infrastructure of the application is used for
 * generating the URLs. The full list of available config options is referenced
 * in the "see also" section of this page.
 *
 * @param string Associative list of configuration options
 * @since 2014.03
 * @category Developer
 * @see client/html/catalog/detail/url/target
 * @see client/html/catalog/detail/url/controller
 * @see client/html/catalog/detail/url/action
 * @see client/html/url/config
 */
$detailConfig = $this->config( 'client/html/catalog/detail/url/config', array() );

$url = $enc->attr( $this->url( $detailTarget, $detailController, $detailAction, $this->get( 'detailParams', array() ), array(), $detailConfig ) );

?>
<?php $this->block()->start( 'catalog/detail/image' ); ?>
<div class="catalog-detail-image" data-dir="horizontal">
<?php if( isset( $this->detailProductItem ) ) : ?>
<?php	$mediaItems = $this->detailProductItem->getRefItems( 'media', 'default', 'default' ); ?>
	<div class="image-single">
		<div class="carousel">
<?php foreach( $mediaItems as $id => $mediaItem ) : ?>
<?php	$previewUrl = $this->content( $mediaItem->getPreview() ); ?>
			<div id="image-<?php echo $enc->attr( $id ); ?>" class="item"
				style="background-image: url('<?php echo $previewUrl; ?>')"
				data-image="<?php echo $previewUrl; ?>"
				data-zoom-image="<?php echo $this->content( $mediaItem->getUrl() ); ?>">
			</div>
<?php endforeach; ?>
		</div>
	</div>
	<div class="image-thumbs">
		<a class="prev-thumbs disabled"></a>
		<a class="next-thumbs disabled"></a>
		<div class="thumbs">
<?php	if( count( $mediaItems ) > 1 ) : $class = 'item selected'; ?>
<?php		foreach( $mediaItems as $id => $mediaItem ) : ?>
			<a href="<?php echo $url . '#image-' . $id; ?>" class="<?php echo $class; ?>" style="background-image: url('<?php echo $this->content( $mediaItem->getPreview() ); ?>')"></a>
<?php			$class = 'item'; ?>
<?php		endforeach; ?>
<?php	endif; ?>
		</div>
	</div>
<?php endif; ?>
<?php echo $this->get( 'imageBody' ); ?>
</div>
<?php $this->block()->stop(); ?>
<?php echo $this->block()->get( 'catalog/detail/image' ); ?>
