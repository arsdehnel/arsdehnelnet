<?php
/**
 * The Sidebar containing the main widget area
 *
 * @package WordPress
 * @subpackage Twenty_Fourteen
 * @since Twenty Fourteen 1.0
 */
?>
<div id="secondary">
	<?php
		$description = get_bloginfo( 'description', 'display' );
		if ( ! empty ( $description ) ) :
	?>
	<h2 class="site-description"><?php echo esc_html( $description ); ?></h2>
	<?php endif; ?>	
	<div role="complementary" class="primary-sidebar widget-area" id="primary-sidebar">
		<?php
			$categories = get_categories(array('hide_empty'=>'0','exclude'=>'1'));
			
			foreach( $categories as $category ):
				$posts = query_posts( 'cat='.$category->cat_ID );
				
				?>
				<aside class="widget widget_archive accordion-wrapper" id="archives-<?php echo $category->cat_ID;?>">
					<h1 class="widget-title accordion-header"><a href="#"><?php echo $category->name;?></a></h1>
					<ul class="accordion-body">
						<?php
							if( is_array( $posts ) ):
								foreach( $posts as $post ):
									echo '<li><a href="'.$post->guid.'">'.$post->post_title.'</a></li>';
								endforeach;
							else:
								echo '<li>No topics posted</li>';
							endif;
						?>
					</ul>
				</aside>
				<?php
			endforeach;		
		?>
	</div>	

	<?php if ( is_active_sidebar( 'sidebar-1' ) ) : ?>
	<div id="primary-sidebar" class="primary-sidebar widget-area" role="complementary">
		<?php dynamic_sidebar( 'sidebar-1' ); ?>
	</div><!-- #primary-sidebar -->
	<?php endif; ?>
</div><!-- #secondary -->

