<?php
/**
 * The Template for displaying all single posts
 *
 * @package WordPress
 * @subpackage Twenty_Fourteen
 * @since Twenty Fourteen 1.0
 */

get_header(); ?>

	<div id="primary" class="content-area">
		<div id="content" class="site-content" role="main">
			<?php
				// Start the Loop.
				while ( have_posts() ) : the_post();	
					?>
					<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
						<header class="entry-header">
							<?php
								the_title( '<h1 class="entry-title">', '</h1>' );
							?>
						</header><!-- .entry-header -->
						<div class="proposal-content">
							<ul class="proposal-meta">
								<?php 
									echo '<li><strong>Invoice #:</strong><span>'.get_the_date('Y').'-'.get_the_id().'</span></li>';
									echo '<li><strong>Client:</strong><span>'.get_field('client_name').'</span></li>';
									the_date('m/d/Y', '<li><strong>Date:</strong><span>', '</span></li>');
								?>
							</ul>
							<div class="proposal-featured-image">
								<?php
									the_post_thumbnail();
								?>
							</div>
							<div class="entry-content">
								<?php
									the_content( __( 'Continue reading <span class="meta-nav">&rarr;</span>', 'twentyfourteen' ) );
								?>
							</div><!-- .entry-content -->
						</div>
					</article><!-- #post-## -->
					<?php
				endwhile;
			?>
		</div><!-- #content -->
	</div><!-- #primary -->

<?php
get_footer();
