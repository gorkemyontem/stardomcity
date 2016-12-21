<?php
/*
  Name: Sorted list
 */
use ContentEgg\application\helpers\TemplateHelper;  
?>

<?php
// sort items by price
usort($items, function($a, $b) {
    if (!$a['price']) return 1;
    if (!$b['price']) return -1;
    return $a['price'] - $b['price'];
});
$product_price_update = get_post_meta( get_the_ID(), '_cegg_last_update_AffilinetProducts', true );
$product_keyword_update = get_post_meta( get_the_ID(), '_cegg_last_bykeyword_update_AffilinetProducts', true );
if ($product_price_update) {
    $product_update = date("F j, Y, g:i a", $product_price_update);
}
elseif ($product_keyword_update) {
   $product_update = date("F j, Y, g:i a", $product_keyword_update);  
}
?>

<div class="rehub_feat_block egg_sort_list notitle_sort_list"><a name="aff-link-list"></a>
    <div class="aff_offer_links">
        <?php $i=0; foreach ($items as $key => $item): ?>
            <?php $offer_price = (!empty($item['price'])) ? TemplateHelper::price_format_i18n($item['price']) : ''; ?>
            <?php $offer_price_old = (!empty($item['priceOld'])) ? TemplateHelper::price_format_i18n($item['priceOld']) : ''; ?>
            <?php $clean_price = (!empty($item['price'])) ? $item['price'] : ''; ?>
            <?php $currency = (!empty($item['currency'])) ? $item['currency'] : ''; ?>
            <?php $currency_code = (!empty($item['currencyCode'])) ? $item['currencyCode'] : ''; ?>          
            <?php $availability = (!empty($item['availability'])) ? $item['availability'] : ''; ?>
            <?php $merchant = (!empty($item['merchant'])) ? $item['merchant'] : ''; ?>
            <?php $afflink = (!empty($item['url'])) ? $item['url'] : '' ;?>
            <?php $aff_thumb = (!empty($item['img'])) ? $item['img'] : '' ;?>
            <?php $offer_title = (!empty($item['title'])) ? wp_trim_words( $item['title'], 12, '...' ) : ''; ?>
            <?php if (!empty($item['logo'])) :?>
                <?php $logo = $item['logo']; ?>             
            <?php elseif (!empty($item['extra']['logo'])) :?>
                <?php $logo = $item['extra']['logo']; ?>
            <?php else:?>
                <?php $logo = ''; ?>                
            <?php endif;?>  
            <?php if(rehub_option('rehub_btn_text') !='') :?><?php $btn_txt = rehub_option('rehub_btn_text') ; ?><?php else :?><?php $btn_txt = __('Buy this item', 'rehub_framework') ;?><?php endif ;?>
            <?php $i++;?>  
 
            <div class="rehub_feat_block table_view_block<?php if ($i == 1){echo' best_price_item';}?>">
                <div class="rehub_woo_review_tabs" style="display:table-row">
                    <div class="offer_thumb">   
                        <a rel="nofollow" target="_blank" class="re_track_btn" href="<?php echo esc_url($afflink) ?>">
                            <?php WPSM_image_resizer::show_static_resized_image(array('src'=> $aff_thumb, 'width'=> 120, 'title' => $offer_title, 'no_thumb_url' => get_template_directory_uri().'/images/default/noimage_123_90.png'));?>                                    
                        </a>                       
                    </div>
                    <div class="desc_col">
                        <h4 class="offer_title">
                            <a rel="nofollow" target="_blank" class="re_track_btn" href="<?php echo esc_url($afflink) ?>">
                                <?php echo esc_attr($offer_title); ?>
                            </a>
                        </h4>
                        <?php if ($item['description']): ?>
                            <p><?php echo $item['description']; ?></p>                    
                        <?php endif; ?>                        
                        <small class="small_size">
                        <?php if ($availability): ?>
                            <?php _e('Available: ', 'rehub_framework') ;?><span class="yes_available"><?php _e('In stock', 'rehub_framework') ;?></span>
                        <?php endif; ?>                         
                        </small>                                
                    </div>
                    <div class="buttons_col">
                        <div class="priced_block clearfix">
                            <?php if(!empty($offer_price)) : ?>
                                <p itemprop="offers" itemscope itemtype="http://schema.org/Offer">
                                    <span class="price_count">
                                        <ins><span><?php echo $currency; ?></span> <?php echo $offer_price ?></ins>
                                        <?php if(!empty($offer_price_old)) : ?>
                                        <del>
                                            <span class="amount"><?php echo $offer_price_old ?></span>
                                        </del>
                                        <?php endif ;?>                                      
                                    </span> 
                                    <meta itemprop="price" content="<?php echo $clean_price ?>">
                                    <meta itemprop="priceCurrency" content="<?php echo $currency_code; ?>">
                                    <?php if ($availability): ?>
                                        <link itemprop="availability" href="http://schema.org/InStock">
                                    <?php endif ;?>                         
                                </p>
                            <?php endif ;?>
                            <div>
                                <a class="re_track_btn btn_offer_block" href="<?php echo esc_url($afflink) ?>" target="_blank" rel="nofollow">
                                    <?php echo $btn_txt ; ?>
                                </a>
                                <?php $offer_coupon_mask = 1 ?>
                                <?php if(!empty($item['extra']['coupon']['code_date'])) : ?>
                                    <?php 
                                    $timestamp1 = strtotime($item['extra']['coupon']['code_date']); 
                                    $seconds = $timestamp1 - time(); 
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
                                      $coupon_text = __('Coupon is Expired', 'rehub_framework');
                                      $coupon_style = 'expired_coupon';
                                    }                 
                                    ?>
                                <?php endif ;?>
                                <?php  if(!empty($item['extra']['coupon']['code'])) : ?>
                                    <?php wp_enqueue_script('zeroclipboard'); ?>
                                    <?php if ($offer_coupon_mask !='1' && $offer_coupon_mask !='on') :?>
                                        <div class="rehub_offer_coupon not_masked_coupon <?php if(!empty($item['extra']['coupon']['code_date'])) {echo $coupon_style ;} ?>" data-clipboard-text="<?php echo $item['extra']['coupon']['code'] ?>"><i class="fa fa-scissors fa-rotate-180"></i><span class="coupon_text"><?php echo $item['extra']['coupon']['code'] ?></span></div>   
                                    <?php else :?>
                                        <?php wp_enqueue_script('affegg_coupons'); ?>
                                        <div class="rehub_offer_coupon masked_coupon <?php if(!empty($item['extra']['coupon']['code_date'])) {echo $coupon_style ;} ?>" data-clipboard-text="<?php echo $item['extra']['coupon']['code'] ?>" data-codetext="<?php echo $item['extra']['coupon']['code'] ?>" data-dest="<?php echo esc_url($item['url']) ?>"><?php if(rehub_option('rehub_mask_text') !='') :?><?php echo rehub_option('rehub_mask_text') ; ?><?php else :?><?php _e('Reveal coupon', 'rehub_framework') ?><?php endif ;?><i class="fa fa-external-link-square"></i></div>   
                                    <?php endif;?>
                                    <?php if(!empty($item['extra']['coupon']['code_date'])) {echo '<div class="time_offer">'.$coupon_text.'</div>';} ?>    
                                <?php endif ;?> 
                                <?php if($logo) :?>
                                    <div class="egg-logo mt10"><img src="<?php echo $logo; ?>" alt="<?php echo esc_attr($offer_title); ?>" /></div>
                                <?php elseif ($merchant) :?>
                                    <div class="aff_tag mt10"><?php echo $merchant; ?></div>
                                <?php endif ;?>                         
                            </div>
                        </div>
                    </div>
                </div>                                                          
            </div>
        <?php endforeach; ?>
        <?php if (!empty($product_update)) :?>
            <div class="last_update"><?php _e('Last update was on: ', 'rehub_framework'); ?><?php echo $product_update  ;?></div>
        <?php endif ;?>       
    </div>
</div>
<div class="clearfix"></div>