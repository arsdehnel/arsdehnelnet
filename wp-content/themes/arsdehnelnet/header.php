<?php
/**
 * The Header for our theme
 *
 * Displays all of the <head> section and everything up till <div id="main">
 *
 * @package WordPress
 * @subpackage Twenty_Fourteen
 * @since Twenty Fourteen 1.0
 */
?><!DOCTYPE html>
<!--[if IE 7]>
<html class="ie ie7" <?php language_attributes(); ?>>
<![endif]-->
<!--[if IE 8]>
<html class="ie ie8" <?php language_attributes(); ?>>
<![endif]-->
<!--[if !(IE 7) | !(IE 8) ]><!-->
<html <?php language_attributes(); ?>>
<!--<![endif]-->
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width">
	<title><?php wp_title( '|', true, 'right' ); ?></title>
	<link rel="profile" href="http://gmpg.org/xfn/11">
	<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>">
	<script src="//ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
	<!--[if lt IE 9]>
	<script src="<?php echo get_template_directory_uri(); ?>/js/html5.js"></script>
	<![endif]-->
	<?php wp_head(); ?>
	<link href='http://fonts.googleapis.com/css?family=Montserrat+Subrayada:400,700' rel='stylesheet' type='text/css'>
</head>
	<?php
		$categories = get_the_category( get_the_id() );
		//print_r( $categories );
		foreach( $categories as $category ):
			$cat_array[$category->slug] = $category->slug;
		endforeach;
		//add_filter( 'body_class', $cat_array );
	?>

<body <?php body_class(); ?>>
<div id="page" class="hfeed">
<header id="site-header">
  <h1>Adam Dehnel</h1>
  <div>father, developer, cook and woodworker</div>
</header>
<nav class="nav-main">
	<?php
		$items = wp_get_nav_menu_items( 'main', $args );
		foreach( $items as $item ):
			$section = strtolower( $item->title );
			if( $item->title == single_cat_title( '', false ) || array_key_exists( $section, $cat_array ) ):
				$class = $section.' active';
			else:
				$class = $section;
			endif;
			?>
			<a href="<?php echo $item->url; ?>" class="<?php echo $class;?>" data-section-id="<?php echo $section;?>">
				<?php echo $item->title; ?>
  			</a>
  			<?php
			//print_r( $item );
		endforeach;
	?>
</nav>
<!--
	<header id="masthead" class="site-header" role="banner">
		<div class="header-main">

			<div class="search-toggle">
				<a href="#search-container" class="screen-reader-text"><?php _e( 'Search', 'twentyfourteen' ); ?></a>
			</div>

		</div>

		<div id="search-container" class="search-box-wrapper hide">
			<div class="search-box">
				<?php get_search_form(); ?>
			</div>
		</div>
	</header><!-- #masthead -->

<main id="main" class="site-main">
