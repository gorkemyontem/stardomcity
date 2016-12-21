<?php if ( function_exists( 'has_post_thumbnail' ) && has_post_thumbnail( get_the_ID() ) ) : ?>
	<div class="edd_download_image">
		<a href="<?php the_permalink(); ?>" title="<?php the_title_attribute(); ?>">
			<?php echo get_the_post_thumbnail( get_the_ID(), 'grid_news' ); ?>
		</a>
        <?php if(rehub_option('rehub_framework_edd_rating') =='1') :?><?php rehub_get_user_resultsedd('small') ?><?php endif ;?>		
	</div>
<?php endif; ?>