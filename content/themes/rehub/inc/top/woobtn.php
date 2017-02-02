<?php if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}?>
<?php global $product;?>
<div class="price-in-compare-flip mt10 woocommerce">
 
    <?php if ($product->get_price() !='') : ?>
        <span class="price-woo-compare-chart rehub-main-font"><?php echo $product->get_price_html(); ?></span>
        <div class="mb10"></div>
    <?php endif;?>
    <?php if ( $product->is_in_stock() &&  $product->add_to_cart_url() !='') : ?>
        <?php  echo apply_filters( 'woocommerce_loop_add_to_cart_link',
            sprintf( '<a href="%s" data-product_id="%s" data-product_sku="%s" class="re_track_btn btn_offer_block btn-woo-compare-chart woo_loop_btn %s %s product_type_%s"%s%s>%s</a>',
            esc_url( $product->add_to_cart_url() ),
            esc_attr( $product->id ),
            esc_attr( $product->get_sku() ),
            $product->is_purchasable() && $product->is_in_stock() ? 'add_to_cart_button' : '',
            $product->supports( 'ajax_add_to_cart' ) ? 'ajax_add_to_cart' : '',
            esc_attr( $product->product_type ),
            $product->product_type =='external' ? ' target="_blank"' : '',
            $product->product_type =='external' ? ' rel="nofollow"' : '',
            esc_html( $product->add_to_cart_text() )
            ),
        $product );?>
    <?php endif; ?>
	<?php if ( defined( 'YITH_WCWL' )){ ?>
	    <div class="yith_woo_chart"> 
			<?php echo do_shortcode('[yith_wcwl_add_to_wishlist]'); ?>
		</div> 
	<?php } ?>                     
</div> 