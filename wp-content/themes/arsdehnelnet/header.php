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
	<script src="<?php echo get_template_directory_uri(); ?>/js/respond.min.js"></script>
	<![endif]-->
	<?php wp_head(); ?>
	<link href='http://fonts.googleapis.com/css?family=Montserrat+Subrayada:400,700' rel='stylesheet' type='text/css'>
</head>
	<?php
		if( is_single() ):
			$categories = get_the_category( get_the_id() );
			foreach( $categories as $category ):
				$cat_array[$category->slug] = $category->slug;
			endforeach;
		endif;
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
			//no category, we're on the homepage
			if( is_front_page() && $section == 'home' ):
				$class = $section.' active current';
			//is this a category homepage?
			elseif( $item->title == single_cat_title( '', false ) ):
				$class = $section.' active current';			
			//there are categories, we're on a post page and this post has been marked in this particular category
			elseif( is_array( $cat_array ) && array_key_exists( $section, $cat_array ) ):
				$class = $section.' active current';
			//either doesn't match or there are no categories or something weird
			else:
				$class = $section;
			endif;
			?>
			<a href="<?php echo $item->url; ?>" class="<?php echo $class;?>" data-section-id="<?php echo $section;?>">
				<span class="icon-<?php echo $section;?>"></span><?php echo $item->title; ?>
  			</a>
  			<?php
		endforeach;
		
		//set the search class
		if( is_search() ):
			$class = ' active current';
		else:
			$class = '';
		endif;
	?>
</nav>
<div id="main" class="main site-main">
	<?php
		if( !is_search() ):
			?>
			<div id="search-container" class="search-box-wrapper hide">
				<div class="search-box">
					<?php get_search_form(); ?>
				</div>
			</div>
			<a href="#search-container" class="search-toggle icon-search search<?php echo $class; ?>"><span class="icon-search"></span>search</a>
			<?php
		endif;
	?>