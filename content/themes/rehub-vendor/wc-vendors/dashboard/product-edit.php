<?php
/**
 * The template for displaying the Product edit form
 *
 * Override this template by copying it to yourtheme/wc-vendors/dashboard/
 *
 * @package    WCVendors_Pro
 * @version    1.3.2
 */
/**
 *   DO NOT EDIT ANY OF THE LINES BELOW UNLESS YOU KNOW WHAT YOU'RE DOING
 *
*/
global $stardomBase;
$title = 	( is_numeric( $object_id ) ) ? __('Save Changes', 'wcvendors-pro') : __('Add Product', 'wcvendors-pro');
$product = 	( is_numeric( $object_id ) ) ? wc_get_product( $object_id ) : null;

// Get basic information for the product
$product_title     			= ( isset($product) && null !== $product ) ? $product->post->post_title    : '';
$product_description        = ( isset($product) && null !== $product ) ? $product->post->post_content  : '';
$product_short_description  = ( isset($product) && null !== $product ) ? $product->post->post_excerpt  : '';
$post_status				= ( isset($product) && null !== $product ) ? $product->post->post_status   : '';

/**
 *  Ok, You can edit the template below but be careful!
*/
?>

<h2><?php echo $title; ?></h2>

<!-- Product Edit Form -->
<form method="post" action="" id="wcv-product-edit" class="wcv-form wcv-formvalidator">

	<!-- Basic Product Details -->
	<div class="wcv-product-basic wcv-product">
		<!-- Product Title -->
		<?php WCVendors_Pro_Product_Form::title( $object_id, $product_title ); ?>
		<!-- Product Description -->
		<?php WCVendors_Pro_Product_Form::description( $object_id, $product_description );  ?>

	  <!-- Media uploader -->
		<div class="wcv-product-media">
			<?php do_action( 'wcv_before_media', $object_id ); ?>
				<?php WCVendors_Pro_Form_helper::product_media_uploader( $object_id ); ?>
			<?php do_action( 'wcv_after_media', $object_id ); ?>

		</div>

			<!-- Product Categories -->
	    <?php WCVendors_Pro_Product_Form::categories( $object_id, true ); ?>
			<!-- Product Campaign Type -->
			<?php $stardomBase->form_campaign_type( $object_id ); ?>
			<!-- Product Social Media Channel -->
			<?php $stardomBase->form_social_media_channel( $object_id ); ?>
	    <!-- Product Tags -->
	    <?php WCVendors_Pro_Product_Form::tags( $object_id, true ); ?>
			<p class="tip">Virgül ile ayır ör: a,b,c,d</p>

			<br/>
	</div>


	<div class="all-100">
		<label for="wcv_product_attributes" class=""><?php _e( 'Attributes', 'woocommerce' ); ?></label>
		<!-- Attributes -->
		<?php do_action( 'wcv_before_attributes_tab', $object_id ); ?>
		<div class="wcv_product_attributes">
			<?php WCVendors_Pro_Product_Form::product_attributes( $object_id ); ?>
		</div>

		<?php do_action( 'wcv_after_attributes_tab', $object_id ); ?>

		<!-- Price and Sale Price -->
		<div class="">
			<?php WCVendors_Pro_Product_Form::prices( $object_id ); ?>
		</div>


		<?php WCVendors_Pro_Product_Form::form_data( $object_id, $post_status ); ?>
		<?php WCVendors_Pro_Product_Form::save_button( $title ); ?>
		<?php WCVendors_Pro_Product_Form::draft_button( __('Save Draft','wcvendors-pro') ); ?>


		</div>
</form>
