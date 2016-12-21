<?php
/*
  Name: Big product cart without title
 */

use ContentEgg\application\helpers\TemplateHelper;

?>



<?php foreach ($items as $item): ?>
    <?php $afflink = $item['url'] ;?>
    <?php $aff_thumb = $item['img'] ;?>
    <?php $offer_title = wp_trim_words( $item['title'], 20, '...' ); ?>  
    <?php if(rehub_option('rehub_btn_text') !='') :?><?php $btn_txt = rehub_option('rehub_btn_text') ; ?><?php else :?><?php $btn_txt = __('Buy this item', 'rehub_framework') ;?><?php endif ;?>   

    <div class="col_wrap_two">
        <div class="product_egg single_product_egg">

            <div class="image col_item">
                <a rel="nofollow" target="_blank" class="re_track_btn" href="<?php echo esc_url($afflink) ?>">
                    <?php WPSM_image_resizer::show_static_resized_image(array('src'=> $aff_thumb, 'width'=> 500, 'title' => $offer_title));?>
                    <?php if(!empty($item['percentageSaved'])) : ?>
                        <span class="sale_a_proc">
                            <?php    
                                echo '-'; echo $item['percentageSaved']; echo '%';
                            ;?>
                        </span>
                    <?php endif ;?>                                   
                </a>
                <?php if (!empty($item['extra']['itemLinks'][3])): ?>
                    <span class="add_wishlist_ce">
                        <a href="<?php echo $item['extra']['itemLinks'][3]['URL'];?>" rel="nofollow" target="_blank" ><i class="fa fa-heart-o"></i> <?php echo $item['extra']['itemLinks'][3]['Description'];?></a>
                    </span>
                <?php endif; ?>                
            </div>

            <div class="product-summary col_item">                              

                <?php if(!empty($item['price'])) : ?>
                    <div class="deal-box-price" itemprop="offers" itemscope itemtype="http://schema.org/Offer">
                        <sup class="cur_sign"><?php echo $item['currency']; ?></sup><?php echo TemplateHelper::price_format_i18n($item['price']); ?>
                        <?php if(!empty($item['priceOld'])) : ?>
                        <span class="retail-old">
                          <strike><span class="value"><?php echo TemplateHelper::price_format_i18n($item['priceOld']); ?></span></strike>
                        </span>
                        <?php endif ;?>                
                        <meta itemprop="price" content="<?php echo $item['price'] ?>">
                        <meta itemprop="priceCurrency" content="<?php echo $item['currencyCode']; ?>">
                        <?php if ($item['availability']): ?>
                            <link itemprop="availability" href="http://schema.org/InStock">
                        <?php endif ;?>                        
                    </div>                
                <?php endif ;?>
                <?php if ($item['extra']['totalNew']): ?>
                    <span class="new-or-used-amazon">
                    <?php echo $item['extra']['totalNew']; ?>
                    <?php _e('new', 'rehub_framework'); ?>
                    <?php if($item['extra']['lowestNewPrice']): ?>
                        <?php _e(' from', 'rehub_framework'); ?>
                        <?php echo $item['currency']; ?><?php echo TemplateHelper::price_format_i18n($item['extra']['lowestNewPrice']); ?>
                    <?php endif; ?>                    
                    <br>
                    </span>
                <?php endif; ?>
                <?php if ($item['extra']['totalUsed']): ?>
                    <span class="new-or-used-amazon">
                    <?php echo $item['extra']['totalUsed']; ?>
                    <?php _e('used', 'rehub_framework'); ?> <?php _e('from', 'rehub_framework'); ?>
                    <?php echo $item['currency']; ?><?php echo TemplateHelper::price_format_i18n($item['extra']['lowestUsedPrice']); ?>
                    <br>
                    </span>
                <?php endif; ?>                
                <small class="small_size">
                    <?php if ((bool) $item['extra']['IsEligibleForSuperSaverShipping']): ?>
                        <span class="yes_available"><?php _e('Free shipping', 'rehub_framework'); ?></span>
                    <?php endif; ?>                        
                </small>                 
                <div class="buttons_col">
                    <div class="priced_block clearfix">
                        <div>
                            <a class="re_track_btn btn_offer_block" href="<?php echo esc_url($afflink) ?>" target="_blank" rel="nofollow">
                                <?php echo $btn_txt ; ?>
                                <span class="aff_tag mtinside"><?php echo rehub_get_site_favicon('http://amazon.com'); ?></span>
                            </a>                                                
                        </div>
                    </div>
                </div>                               
                <div class="last_update_amazon mb15"><?php _e('Last update was in: ', 'rehub_framework'); ?><?php echo TemplateHelper::getLastUpdateFormatted('Amazon'); ?></div>
                    
                <?php if ($item['extra']['itemAttributes']['Feature']): ?>  
                    <p>
                        <ul class="featured_list">
                            <?php $length = $maxlength = '';?>
                            <?php foreach ($item['extra']['itemAttributes']['Feature'] as $k => $feature): ?>
                                <?php $length = strlen($feature); $maxlength += $length; ?> 
                                <li><?php echo $feature; ?></li>
                                <?php if($k >= 5 || $maxlength > 400) break; ?>                                    
                        <?php endforeach; ?>
                        </ul>
                    </p>                     
                <?php endif; ?> 

            </div>           
        </div> 
    </div>  
    <div class="clearfix"></div>
<?php endforeach; ?>     