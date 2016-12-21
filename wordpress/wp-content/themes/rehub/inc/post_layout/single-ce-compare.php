<!-- CONTENT -->
<div class="content"> 
	<div class="clearfix">
	    <!-- Main Side -->
        <div class="main-side single<?php if(vp_metabox('rehub_post_side.post_size') == 'full_post') : ?> full_width<?php endif; ?> clearfix">            
            <?php if (have_posts()) : while (have_posts()) : the_post(); ?>
                <article class="post post-inner <?php $category = get_the_category($post->ID); if ($category) {$first_cat = $category[0]->term_id; echo 'category-'.$first_cat.'';} ?>" id="post-<?php the_ID(); ?>">
                    <!-- Title area -->
                    <div class="rh_post_layout_compare_ce">
                        <?php 
                            $crumb = '';
                            if( function_exists( 'yoast_breadcrumb' ) ) {
                                $crumb = yoast_breadcrumb('<div class="breadcrumb">','</div>', false);
                            }
                            if( ! is_string( $crumb ) || $crumb === '' ) {
                                if(rehub_option('rehub_disable_breadcrumbs') == '1' || vp_metabox('rehub_post_side.disable_parts') == '1') {echo '';}
                                elseif (function_exists('dimox_breadcrumbs')) {
                                    dimox_breadcrumbs(); 
                                }
                            }
                            echo $crumb;  
                        ?> 
                        <div class="title_single_area">
                            <h1 class="<?php if(rehub_option('hotmeter_disable') !='1') :?><?php echo getHotIconclass($post->ID); ?><?php endif ;?>"><?php the_title(); ?></h1> <?php if(rehub_option('hotmeter_disable') !='1') :?><?php echo getHotLike(get_the_ID()); ?><?php endif ;?>
                            <div class="rh_post_layout_compare_holder">
                                <?php if(vp_metabox('rehub_post_side.show_featured_image') != '1' && has_post_thumbnail())  : ?>
                                    <div class="featured_compare_left compare-full-images">
                                        <figure><?php echo re_badge_create('tablelabel'); ?>
                                            <?php           
                                                $image_id = get_post_thumbnail_id(get_the_ID());  
                                                $image_url = wp_get_attachment_image_src($image_id,'full');
                                                $image_url = $image_url[0]; 
                                            ?>  
                                            <a href="<?php echo $image_url;?>" target="_blank">     
                                                <?php WPSM_image_resizer::show_static_resized_image(array('lazy'=>true, 'thumb'=> true, 'crop'=> false, 'width'=> 500, 'no_thumb_url' => get_template_directory_uri() . '/images/default/noimage_500_500.png'));?>
                                            </a>
                                        </figure> 
                                        <?php echo rh_get_post_thumbnails(array('video'=>1, 'columns'=>4, 'height'=>60));?>                                                
                                    </div>
                                <?php endif;?>
                                <div class="single_compare_right">   
                                    <div class="review_compare_score">
                                        <?php $overall_review = rehub_get_overall_score();?>
                                        <?php if($overall_review):?>
                                            <div class="mb15 flowhidden">
                                            <span class="floatleft"><strong><?php _e('Overall score: ', 'rehub_framework');?></strong></span>
                                            <?php $starscoreadmin = $overall_review*10 ;?>
                                            <div class="star-big floatright">
                                                <span class="stars-rate unix-star">
                                                    <span style="width: <?php echo (int)$starscoreadmin;?>%;"></span>
                                                </span>
                                            </div>
                                            </div>                       
                                        <?php endif;?>
                                    </div>                                                 
                      
                                    <?php echo do_shortcode('[wpsm_compare_multioffer ce_enable=1 pricehistory=1 pricealert=1]');?>
                                    <?php if(rehub_option('rehub_disable_share_top') =='1' || vp_metabox('rehub_post_side.disable_parts') == '1')  : ?>
                                    <?php else :?>
                                        <div class="top_share"><?php get_template_part('inc/parts/post_share'); ?></div>
                                        <div class="clearfix"></div> 
                                    <?php endif; ?>                                                                                                      
                                </div> 
                            </div>
                        </div>                                                 
                        <?php if(rehub_option('rehub_single_after_title') && vp_metabox('rehub_post_side.show_banner_ads') != '1') : ?><div class="mediad mediad_top"><?php echo do_shortcode(rehub_option('rehub_single_after_title')); ?></div><div class="clearfix"></div><?php endif; ?> 
                    </div>
                    <?php $no_featured_image_layout = 1;?>
                    <?php include(locate_template('inc/parts/top_image.php')); ?>                                       

                    <?php if(rehub_option('rehub_single_before_post') && vp_metabox('rehub_post_side.show_banner_ads') != '1') : ?><div class="mediad mediad_before_content"><?php echo do_shortcode(rehub_option('rehub_single_before_post')); ?></div><?php endif; ?>

                    <?php the_content(); ?>

                </article>
                <div class="clearfix"></div>
                <?php include(locate_template('inc/post_layout/single-common-footer.php')); ?>                    
            <?php endwhile; endif; ?>
            <?php comments_template(); ?>
		</div>	
        <!-- /Main Side -->  
        <!-- Sidebar -->
        <?php if(vp_metabox('rehub_post_side.post_size') == 'full_post') : ?><?php else : ?><?php get_sidebar(); ?><?php endif; ?>
        <!-- /Sidebar -->
    </div>
</div>
<!-- /CONTENT -->     