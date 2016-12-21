<?php global $product; global $post;?>
<?php if (empty( $product ) || ! $product->is_visible() ) {return;}?>
<?php $classes = array('product', 'col_item');?>
<?php if (rehub_option('woo_btn_disable') == '1'){$classes[] = 'non_btn';}?>
<?php $woolinktype = (isset($woolinktype)) ? $woolinktype : '';?>
<?php $custom_img_width = (isset($custom_img_width)) ? $custom_img_width : '';?>
<?php $custom_img_height = (isset($custom_img_height)) ? $custom_img_height : '';?>
<?php $custom_col = (isset($custom_col)) ? $custom_col : '';?>
<?php $woolinktype = (isset($woolinktype)) ? $woolinktype : '';?>
<?php $woolink = ($woolinktype == 'aff' && $product->product_type =='external') ? $product->add_to_cart_url() : get_post_permalink($post->ID) ;?>
<?php $wootarget = ($woolinktype == 'aff' && $product->product_type =='external') ? ' target="_blank" rel="nofollow"' : '' ;?>
<?php $offer_coupon = get_post_meta( $post->ID, 'rehub_woo_coupon_code', true ) ?>
<?php $offer_coupon_date = get_post_meta( $post->ID, 'rehub_woo_coupon_date', true ) ?>
<?php $offer_coupon_mask = '1' ?>
<?php $offer_url = esc_url( $product->add_to_cart_url() ); ?>
<?php $coupon_style = $expired = ''; if(!empty($offer_coupon_date)) : ?>
    <?php 
    $timestamp1 = strtotime($offer_coupon_date); 
    $seconds = $timestamp1 - (int)current_time('timestamp',0); 
    $days = floor($seconds / 86400);
    $seconds %= 86400;
    if ($days > 0) {
      $coupon_text = $days.' '.__('days left', 'rehub_framework');
      $coupon_style = '';
    }
    elseif ($days == 0){
      $coupon_text = __('Last day', 'rehub_framework');
      $coupon_style = '';
    }
    else {
        $coupon_text = __('Expired', 'rehub_framework');
        $coupon_style = ' expired_coupon';
        $expired = '1';
    }                 
    ?>
<?php endif ;?>
<?php do_action('woo_change_expired', $expired); //Here we update our expired?>
<?php $classes[] = $coupon_style;?>
<?php $coupon_mask_enabled = (!empty($offer_coupon) && ($offer_coupon_mask =='1' || $offer_coupon_mask =='on') && $expired!='1') ? '1' : ''; ?>
<?php if($coupon_mask_enabled =='1') {$classes[] = 'reveal_enabled';}?>
<?php if(rehub_option('woo_thumb_enable') == '1') {$classes[] = 'thumb_enabled_col';}?>
<div class="<?php echo implode(' ', $classes); ?>">
    <figure class="full_image_woo">
        <a href="<?php echo $woolink ;?>"<?php echo $wootarget ;?>>
            <?php if ( $product->is_featured() ) : ?>
                    <?php echo apply_filters( 'woocommerce_featured_flash', '<span class="onfeatured">' . __( 'Featured!', 'rehub_framework' ) . '</span>', $post, $product ); ?>
            <?php endif; ?>        
            <?php if ( $product->is_on_sale()) : ?>
                <?php 
                $percentage=0;
                $featured = ($product->is_featured()) ? ' onsalefeatured' : '';
                if ($product->regular_price) {
                    $percentage = round( ( ( $product->regular_price - $product->sale_price ) / $product->regular_price ) * 100 );
                }
                if ($percentage && $percentage>0 && !$product->is_type( 'variable' )) {
                    $sales_html = apply_filters( 'woocommerce_sale_flash', '<span class="onsale'.$featured.'"><span>- ' . $percentage . '%</span></span>', $post, $product );
                }
                else{
                    $sales_html = apply_filters( 'woocommerce_sale_flash', '<span class="onsale'.$featured.'">' . esc_html__( 'Sale!', 'rehub_framework' ) . '</span>', $post, $product );  
                }              
                ?>
                <?php echo $sales_html; ?>
            <?php endif; ?>
            <?php 
            $showimg = new WPSM_image_resizer();
            $showimg->use_thumb = true; 
            $showimg->no_thumb = rehub_woocommerce_placeholder_img_src('');                                   
            ?>
            <?php if($custom_col) : ?>
                <?php $showimg->width = (int)$custom_img_width;?>
                <?php $showimg->height = (int)$custom_img_height;?>            
            <?php elseif($columns == '3_col') : ?>
                <?php $showimg->width = '250';?>
                <?php $showimg->height = '250';?>
            <?php elseif($columns == '4_col') : ?>
                <?php $showimg->width = '274';?>  
                <?php $showimg->height = '274';?>                
            <?php elseif($columns == '5_col') : ?>
                <?php $showimg->width = '210';?> 
                <?php $showimg->height = '210';?>                  
            <?php elseif($columns == '6_col') : ?>
                <?php $showimg->width = '170';?> 
                <?php $showimg->height = '170';?>                                     
            <?php else : ?>
                <?php $showimg->width = '250';?>
                <?php $showimg->height = '250';?>                                                       
            <?php endif ; ?>  
            <?php $showimg->crop = true;?>          
            <?php $showimg->show_resized_image(); ?>
        </a>
        <div class="yith_float_btns">
            <div class="button_action"> 
                <?php if(rehub_option('woo_rhcompare') == 1) {echo wpsm_comparison_button(array('class'=>'rhwooloopcompare'));}?>
                <?php if ( defined( 'YITH_WCWL' )){ ?> 
                    <?php echo do_shortcode('[yith_wcwl_add_to_wishlist]'); ?> 
                <?php } ?>                                          
            </div> 
        </div>          
        <div class="brand_store_tag">       
            <?php WPSM_Woohelper::re_show_brand_tax(); //show brand taxonomy?>
        </div>
        <?php do_action( 'rehub_after_woo_brand' ); ?>
    </figure>
    <div class="woo_column_cont">
        <div class="woo_loop_desc">      
            <a <?php if(rehub_option('woo_thumb_enable') =='1') :?>class="<?php echo getHotIconclass($post->ID); ?>"<?php endif ;?> href="<?php echo $woolink ;?>"<?php echo $wootarget ;?>>
                <?php echo rh_expired_or_not($post->ID, 'span');?>     
                <?php 
                    /**
                     * woocommerce_shop_loop_item_title hook.
                     *
                     * @hooked woocommerce_template_loop_product_title - 10
                     */     
                    do_action( 'woocommerce_shop_loop_item_title' ); 
                ?>
            </a>
            <?php do_action( 'rehub_vendor_show_action' ); ?>            
        </div>
        <div class="woo_loop_actions">
            <div class="product_price_height">
                <?php
                    /**
                     * woocommerce_after_shop_loop_item_title hook.
                     *
                     * @hooked woocommerce_template_loop_rating - 5
                     * @hooked woocommerce_template_loop_price - 10
                     */
                    do_action( 'woocommerce_after_shop_loop_item_title' );
                ?>
            </div>          
        </div>
    </div>

    <?php if (rehub_option('woo_btn_disable') != '1'):?>
    <div class="woo_loop_btn_actions woo_column_cont">      
            <?php if ( $product->add_to_cart_url() !='') : ?>
                <?php  echo apply_filters( 'woocommerce_loop_add_to_cart_link',
                    sprintf( '<a href="%s" rel="nofollow" data-product_id="%s" data-product_sku="%s" class="re_track_btn woo_loop_btn btn_offer_block %s %s product_type_%s"%s>%s</a>',
                    esc_url( $product->add_to_cart_url() ),
                    esc_attr( $product->id ),
                    esc_attr( $product->get_sku() ),
                    $product->is_purchasable() && $product->is_in_stock() ? 'add_to_cart_button' : '',
                    $product->supports( 'ajax_add_to_cart' ) ? 'ajax_add_to_cart' : '',
                    esc_attr( $product->product_type ),
                    $product->product_type =='external' ? ' target="_blank"' : '',
                    esc_html( $product->add_to_cart_text() )
                    ),
            $product );?>                
            <?php if ($coupon_mask_enabled =='1') :?>
                <?php wp_enqueue_script('zeroclipboard'); ?>
                <a class="woo_loop_btn coupon_btn re_track_btn btn_offer_block rehub_offer_coupon masked_coupon <?php if(!empty($offer_coupon_date)) {echo $coupon_style ;} ?>" data-clipboard-text="<?php echo $offer_coupon ?>" data-codeid="<?php echo $product->id ?>" data-dest="<?php echo $offer_url ?>"><?php if(rehub_option('rehub_mask_text') !='') :?><?php echo rehub_option('rehub_mask_text') ; ?><?php else :?><?php _e('Reveal coupon', 'rehub_framework') ?><?php endif ;?>
                </a>
            <?php else :?>
                <?php if(!empty($offer_coupon)) : ?>
                    <?php wp_enqueue_script('zeroclipboard'); ?>
                    <div class="rehub_offer_coupon not_masked_coupon <?php if(!empty($offer_coupon_date)) {echo $coupon_style ;} ?>" data-clipboard-text="<?php echo $offer_coupon ?>"><i class="fa fa-scissors fa-rotate-180"></i><span class="coupon_text"><?php echo $offer_coupon ?></span>
                    </div>
                <?php endif;?>
            <?php endif;?>                     
        <?php endif;?>            
    </div>
    <?php endif; ?>    

    <?php if (rehub_option('woo_thumb_enable') == '1') :?>
        <div class="re_actions_for_column">
            <div class="floatleft">
                <span class="date_for_grid">
                    <i class="fa fa-clock-o"></i><?php printf( __( '%s ago', 'rehub_framework' ), human_time_diff( get_the_time( 'U' ), current_time( 'timestamp' ) ) ); ?>
                </span>    
            </div>
            <div class="floatright"><?php echo getHotThumb($post->ID, false, false, true);?></div>
        </div>  
    <?php endif;?>  

</div>