<?php
/*
  Name: Simple list
 */

?>

<?php
use ContentEgg\application\helpers\TemplateHelper;
?>

<div class="egg_sort_list simple_sort_list re_sort_list mb20"><a name="aff-link-list"></a>
    <div class="aff_offer_links">
        <?php $i=0; foreach ($items as $key => $item): ?>
            <?php $afflink = $item['url'] ;?>
            <?php $aff_thumb = $item['img'] ;?>
            <?php $offer_title = wp_trim_words( $item['title'], 10, '...' ); ?>
            <?php $i++;?>  
            <?php if(rehub_option('rehub_btn_text') !='') :?><?php $btn_txt = rehub_option('rehub_btn_text') ; ?><?php else :?><?php $btn_txt = __('Buy this item', 'rehub_framework') ;?><?php endif ;?>  
            <div class="rehub_feat_block table_view_block<?php if ($i == 1){echo' best_price_item';}?>">
                
                    <div class="offer_thumb">   
                        <a rel="nofollow" target="_blank" class="re_track_btn" href="<?php echo esc_url($afflink) ?>">
                            <?php WPSM_image_resizer::show_static_resized_image(array('src'=> $aff_thumb, 'height'=> 100, 'title' => $offer_title, 'no_thumb_url' => get_template_directory_uri().'/images/default/noimage_100_70.png'));?>                                    
                        </a>
                    </div>
                    <div class="desc_col desc_simple_col">
                        <div class="simple_title">
                            <a rel="nofollow" target="_blank" class="re_track_btn" href="<?php echo esc_url($afflink) ?>">
                                <?php echo esc_attr($offer_title); ?>
                            </a>
                        </div>                                
                    </div>                    
                    <div class="desc_col price_simple_col">
                        <?php if(!empty($item['price'])) : ?>
                            <p itemprop="offers" itemscope itemtype="http://schema.org/Offer">
                                <span class="price_count">
                                    <span><?php echo $item['currency']; ?></span> <?php echo TemplateHelper::price_format_i18n($item['price']); ?>
                                    <?php if(!empty($item['priceOld'])) : ?>
                                    <strike>
                                        <span class="amount"><?php echo TemplateHelper::price_format_i18n($item['priceOld']); ?></span>
                                    </strike>
                                    <?php endif ;?>                                      
                                </span> 
                                <meta itemprop="price" content="<?php echo $item['price'] ?>">
                                <meta itemprop="priceCurrency" content="<?php echo $item['currencyCode']; ?>">
                                <?php if ($item['availability']): ?>
                                    <link itemprop="availability" href="http://schema.org/InStock">
                                <?php endif ;?>                        
                            </p>
                        <?php endif ;?>                        
                    </div>
                    <div class="desc_col shop_simple_col">
                        <div class="aff_tag mt10"><?php echo rehub_get_site_favicon('http://amazon.com'); ?></div> 
                        <small class="small_size available_stock"><?php if ($item['availability']): ?><span class="yes_available"><?php _e('In stock', 'rehub_framework') ;?></span><?php endif; ?></small>
                    </div>
                    <div class="buttons_col">
                        <div class="priced_block clearfix">
                            <div>
                                <a class="re_track_btn btn_offer_block" href="<?php echo esc_url($afflink) ?>" target="_blank" rel="nofollow">
                                    <?php echo $btn_txt ; ?>
                                </a>                                                        
                            </div>
                        </div>
                    </div>                                                          
            </div>
        <?php endforeach; ?>               
    </div>
    <div class="last_update"><?php _e('Last update was in: ', 'rehub_framework'); ?><?php echo TemplateHelper::getLastUpdateFormatted('Amazon'); ?></div>    
</div>
<div class="clearfix"></div>