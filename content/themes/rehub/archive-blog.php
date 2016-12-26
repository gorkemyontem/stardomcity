<?php get_header(); ?>
<!-- CONTENT -->
<div class="content"> 
    <div class="clearfix">
          <!-- Main Side -->
          <div class="main-side no_bg_wrap clearfix<?php if (rehub_option('blog_archive_layout') == 'gridfull_blog') : ?> full_width<?php endif ;?>">
            <?php
                if(isset($_GET['author_name'])) :
                $curauth = get_userdatabylogin($author_name);
            else :
                $curauth = get_userdata(intval($author));
            endif;?> 
            <?php if (rehub_option('blog_archive_layout') == 'grid_blog') : ?>
                <?php  wp_enqueue_script('masonry'); wp_enqueue_script('imagesloaded'); wp_enqueue_script('masonry_init'); ?>
                <div class="masonry_grid_fullwidth two-col-gridhub">
            <?php elseif (rehub_option('blog_archive_layout') == 'gridfull_blog') : ?>   
                <?php  wp_enqueue_script('masonry'); wp_enqueue_script('imagesloaded'); wp_enqueue_script('masonry_init'); ?>
                <div class="masonry_grid_fullwidth three-col-gridhub">                     
            <?php endif ;?>                        
            <?php if (have_posts()) : ?>
            <?php while (have_posts()) : the_post(); ?>
                <?php if (rehub_option('blog_archive_layout') == 'big_blog') : ?>
                    <?php get_template_part('inc/parts/query_type2'); ?>
                <?php elseif (rehub_option('blog_archive_layout') == 'list_blog') : ?>
                    <?php get_template_part('inc/parts/query_type1'); ?>
                <?php elseif (rehub_option('blog_archive_layout') == 'grid_blog' || rehub_option('blog_archive_layout') == 'gridfull_blog') : ?>
                    <?php get_template_part('inc/parts/query_type3'); ?>                    
                <?php else : ?>
                    <?php get_template_part('inc/parts/query_type1'); ?>	
                <?php endif ;?>
            <?php endwhile; ?>
            <?php else : ?>		
            <h5><?php _e('Sorry. No posts in this category yet', 'rehub_framework'); ?></h5>	
            <?php endif; ?>	
            <?php if (rehub_option('blog_archive_layout') == 'grid_blog' || rehub_option('blog_archive_layout') == 'gridfull_blog') : ?></div><?php endif ;?>
            <div class="clearfix"></div>
            <?php rehub_pagination(); ?>
        </div>	
        <!-- /Main Side -->
        <?php if (rehub_option('blog_archive_layout') != 'gridfull_blog') : ?>
            <!-- Sidebar -->
            <?php get_sidebar(); ?>
            <!-- /Sidebar --> 
        <?php endif ;?>
    </div>
</div>
<!-- /CONTENT -->     
<!-- FOOTER -->
<?php get_footer(); ?>