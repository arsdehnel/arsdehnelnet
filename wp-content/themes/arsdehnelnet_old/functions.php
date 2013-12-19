<?php
register_nav_menu( 'main' );

function wp_arsdehnelnet_menu( $menu_name ){

    if( ( $locations = get_nav_menu_locations() ) && isset( $locations[ $menu_name ] ) ):
    
		$menu = wp_get_nav_menu_object( $locations[ $menu_name ] );
	
		$menu_items = wp_get_nav_menu_items($menu->term_id);
	
		$menu_list = '<nav id="menu-' . $menu_name . '" class="nav-main">';
	
		foreach ( (array) $menu_items as $key => $menu_item ) {
			$title = $menu_item->title;
		    $url = $menu_item->url;
		    $menu_list .= '<a href="' . $url . '" class="'.strtolower($title).'">' . $title . '</a>';
		}
		$menu_list .= '</nav>';
		
    else:
    
		$menu_list = $menu_name . '" not defined.';
		
    endif;

	return $menu_list;

}