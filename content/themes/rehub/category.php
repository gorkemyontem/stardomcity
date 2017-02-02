<?php if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly ?>
<?php get_header(); ?>
<!-- CONTENT -->
<div class="rh-container"> 
    <div class="rh-content-wrap clearfix">
        <!-- Main Side -->
        <div class="main-side clearfix<?php if (rehub_option('rehub_framework_category_layout') == 'rehub_framework_category_gridfull') : ?> full_width<?php endif ;?>">
            <div class="wpsm-title middle-size-title wpsm-cat-title"><h5><span><?php _e('Category:', 'rehub_framework'); ?></span> <?php single_cat_title(); ?></h5></div>
            <?php if( !is_paged()) : ?><article class='top_rating_text post'><?php echo category_description(); ?></article><?php endif ;?>
            <?php if (rehub_option('rehub_framework_category_layout') == 'rehub_framework_category_gridfull') : ?>
                <div class="masonry_grid_fullwidth three-col-gridhub">
                <?php  wp_enqueue_script('masonry'); wp_enqueue_script('imagesloaded'); wp_enqueue_script('masonry_init'); ?>
            <?php elseif (rehub_option('rehub_framework_category_layout') == 'rehub_framework_category_grid') : ?>
                <div class="masonry_grid_fullwidth two-col-gridhub">
                <?php  wp_enqueue_script('masonry'); wp_enqueue_script('imagesloaded'); wp_enqueue_script('masonry_init'); ?>
            <?php else : ?>
                <div>    
            <?php endif ;?>
                <?php if (have_posts()) : ?>
                <?php while (have_posts()) : the_post(); ?>
                    <?php if (rehub_option('rehub_framework_category_layout') == 'rehub_framework_category_blog') : ?>
                        <?php include(rh_locate_template('inc/parts/query_type2.php')); ?>
                    <?php elseif (rehub_option('rehub_framework_category_layout') == 'rehub_framework_category_list') : ?>
                        <?php include(rh_locate_template('inc/parts/query_type1.php')); ?>
                    <?php elseif (rehub_option('rehub_framework_category_layout') == 'rehub_framework_category_grid') : ?>
                        <?php include(rh_locate_template('inc/parts/query_type3.php')); ?>
                    <?php elseif (rehub_option('rehub_framework_category_layout') == 'rehub_framework_category_gridfull') : ?>
                        <?php include(rh_locate_template('inc/parts/query_type3.php')); ?>  
                    <?php else : ?>
                        <?php include(rh_locate_template('inc/parts/query_type1.php')); ?>	
                    <?php endif ;?>
                <?php endwhile; ?>

                <?php else : ?>		
                    <h5><?php _e('Sorry. No posts in this category yet', 'rehub_framework'); ?></h5>			   
                <?php endif; ?>
                </div>
                <div class="clearfix"></div>    
            <?php rehub_pagination();?>  	
        </div>	
        <!-- /Main Side -->
        <?php if (rehub_option('rehub_framework_category_layout') != 'rehub_framework_category_gridfull') : ?>
            <!-- Sidebar -->
            <?php get_sidebar(); ?>
            <!-- /Sidebar --> 
        <?php endif ;?>
    </div>
</div>
<!-- /CONTENT -->     
<!-- FOOTER -->
<?php get_footer(); ?>