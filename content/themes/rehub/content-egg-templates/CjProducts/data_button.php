<?php
/*
  Name: Just button
 */
?>

<?php foreach ($items as $item): ?>
<?php $afflink = $item['url'] ;?> 
<?php $merchant = (!empty($item['merchant'])) ? $item['merchant'] : ''; ?>
<?php if(rehub_option('rehub_btn_text') !='') :?><?php $btn_txt = rehub_option('rehub_btn_text') ; ?><?php else :?><?php $btn_txt = __('Buy this item', 'rehub_framework') ;?><?php endif ;?>   	    
<a href="<?php echo esc_url($afflink) ?>" class="re_track_btn wpsm-button rehub_main_btn btn_offer_block" target="_blank" rel="nofollow">
	<span><strong><?php echo esc_html($btn_txt) ?></strong></span>
	<span class="aff_tag mtinside"><?php echo $merchant; ?></span>	
</a>                                                                  
<?php endforeach; ?>         