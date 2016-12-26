<?php
/*
  Name: Offers without button
 */
?>

<?php wp_enqueue_style('eggrehub'); ?>

<div class="rh_deal_block"> 
    <?php $i=0; foreach ($items as $key => $item): ?>
        <?php $offer_price = str_replace(' ', '', $item['price']); if($offer_price =='0') {$offer_price = '';} ?>
        <?php $offer_price_old = str_replace(' ', '', $item['old_price']); if($offer_price_old =='0') {$offer_price_old = '';} ?>
        <?php $afflink = $item['url'] ;?>
        <?php $aff_thumb = $item['img'] ;?>
        <?php $offer_title = wp_trim_words( $item['title'], 10, '...' ); ?>
        <?php $i++;?>  
        <div class="deal_block_row">
            <div class="deal-pic-wrapper">
                <a rel="nofollow" target="_blank" class="re_track_btn" href="<?php echo esc_url($afflink) ?>"<?php echo $item['ga_event'] ?>>
                    <?php WPSM_image_resizer::show_static_resized_image(array('src'=> $aff_thumb, 'crop'=> false, 'width'=> 70, 'height'=> 70, 'no_thumb_url' => get_template_directory_uri() . '/images/default/noimage_70_70.png'));?>                                    
                </a>                
            </div>
            <div class="rh-deal-details">
                <div class="rh-deal-text">
                    <div class="rh-deal-name">
                        <h5>
                        <a rel="nofollow" class="re_track_btn" target="_blank" href="<?php echo esc_url($afflink) ?>"<?php echo $item['ga_event'] ?>>
                            <?php echo esc_attr($offer_title); ?>
                        </a>
                        </h5>
                    </div>
                </div>
                <div class="rh-deal-left">
                    <?php if(!empty($offer_price)) : ?>
                        <div class="rh-deal-price">
                            <ins><span><?php echo $item['currency']; ?></span><?php echo $offer_price ?></ins>
                            <?php if(!empty($offer_price_old)) : ?>
                            <del>
                                <?php echo $offer_price_old ?>
                            </del>
                            <?php endif ;?>                                
                        </div>
                    <?php endif ;?>                  
                    <div class="rh-deal-tag">
                        <?php echo rehub_get_site_favicon($item['orig_url']); ?>                             
                    </div>
                </div>

            </div>
        </div>
    <?php endforeach; ?>
</div>