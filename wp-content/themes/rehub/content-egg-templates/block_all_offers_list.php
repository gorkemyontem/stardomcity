<?php
/*
 * Name: Offers list from all affiliate modules
 * Modules:
 * Module Types: PRODUCT
 * 
 */
?>

<?php
use ContentEgg\application\helpers\TemplateHelper;
// sort items by price
?>      
<?php 
    $all_items = array(); 
    foreach ($data as $module_id => $items) {
        foreach ($items as $item_ar) {
            $item_ar['module_id'] = $module_id;
            $all_items[] = $item_ar;

        }       
    }
    usort($all_items, function($a, $b) {
        if (!$a['price']) return 1;
        if (!$b['price']) return -1;
        return $a['price'] - $b['price'];
    }); 
               
?>
<?php $deal_list_title = (rehub_option('rehub_choosedeal_text') !='') ? esc_html(rehub_option('rehub_choosedeal_text')) : __('Choose your deal', 'rehub_framework'); ?>
<div class="rehub_feat_block"><a name="aff-link-list"></a>
<div class="egg_sort_list re_sort_list simple_sort_list notitle_sort_list">
    <div class="aff_offer_links">
        <?php  foreach ($all_items as $key => $item): ?>
            <?php $afflink = $item['url'] ;?>
            <?php $aff_thumb = $item['img'] ;?>
            <?php $offer_title = wp_trim_words( $item['title'], 10, '...' ); ?>
            <?php $merchant = (!empty($item['merchant'])) ? $item['merchant'] : ''; ?>
            <?php $manufacturer = (!empty($item['manufacturer'])) ? $item['manufacturer'] : ''; ?>
            <?php if (!empty($item['domain'])):?>
                <?php $domain = $item['domain'];?>
            <?php elseif (!empty($item['extra']['domain'])):?>
                <?php $domain = $item['extra']['domain'];?>
            <?php else:?>
                <?php $domain = '';?>        
            <?php endif;?>              
            <?php if (!empty($item['logo'])) :?>
                <?php $logo = $item['logo']; ?>            
            <?php elseif (!empty($item['extra']['logo'])) :?>
                <?php $logo = $item['extra']['logo']; ?>
            <?php elseif (!empty($item['extra']['MerchantLogoURL'])) :?>
                <?php $logo = $item['extra']['MerchantLogoURL']; ?>  
            <?php elseif (!empty($item['extra']['programLogo'])) :?>
                <?php $logo = $item['extra']['programLogo']; ?>                               
            <?php elseif(isset($item['module_id']) && $item['module_id'] =='Amazon') :?>
                <?php $logo = get_template_directory_uri().'/images/logos/amazon.jpg' ;?>
            <?php elseif(isset($item['module_id']) && $item['module_id'] =='Aliexpress') :?>
                <?php $logo = get_template_directory_uri().'/images/logos/aliexpress.jpg' ;?> 
            <?php elseif(isset($item['module_id']) && $item['module_id'] =='Ebay') :?>
                <?php $logo = get_template_directory_uri().'/images/logos/ebay.jpg' ;?>
            <?php elseif(isset($item['module_id']) && $item['module_id'] =='Flipkart') :?>
                <?php $logo = get_template_directory_uri().'/images/logos/flipkart.png' ;?> 
            <?php elseif(isset($item['module_id']) && $item['module_id'] =='PayTM') :?>
                <?php $logo = get_template_directory_uri().'/images/logos/paytm.jpg' ;?>            
            <?php elseif(isset($item['module_id']) && !empty($domain)) :?>
                <?php $logo = rh_ae_logo_get('http://'.$domain); ?>                                                 
            <?php else :?>
                <?php $logo = ''; ?>
            <?php endif;?>
            <?php if(rehub_option('rehub_btn_text') !='') :?><?php $btn_txt = rehub_option('rehub_btn_text') ; ?><?php else :?><?php $btn_txt = __('Buy this item', 'rehub_framework') ;?><?php endif ;?>  
            <div class="rehub_feat_block table_view_block">
                
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
                            </p>
                        <?php endif ;?>                        
                    </div>
                    <div class="desc_col shop_simple_col">
                        <?php if($logo) :?>
                            <div class="egg-logo"><img src="<?php echo $logo; ?>" alt="<?php echo esc_attr($offer_title); ?>" /></div>
                        <?php elseif ($merchant) :?>
                            <div class="aff_tag"><?php echo $merchant; ?></div>
                        <?php elseif ($manufacturer) :?>
                            <div class="aff_tag"><?php echo $manufacturer; ?></div>                            
                        <?php endif ;?>                         
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
</div>
</div>
<div class="clearfix"></div>