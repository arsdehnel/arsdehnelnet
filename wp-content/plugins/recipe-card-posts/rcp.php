<?php
/**
 * Plugin Name: Recipe Card Posts
 * Plugin URI:  http://arsdehnel.net/plugin/recipe-card-posts/
 * Description: Take a simple recipe-based post and add ways to make the post data extract into a recipe card.
 * Version:     0.1
 * Author:      Adam Dehnel
 * Author URI:  http://arsdehnel.net/
 * License:     GPLv2 or later
 * 
 * 
 * 
*/

global $recipecardposts_statuses;
$recipecardposts_statuses = array('publish'=>'Active','trash'=>'Inactive');

add_action( 'init'                                      , 'recipecardposts_create_posttypes' );

function recipecardposts_create_posttypes() {
    register_post_type( 'recipe_card_post',
        array(
            'labels' => array(
                'name'          => __( 'Recipes' ),
                'singular_name' => __( 'Recipe' ),
                'add_new_item'  => __( 'Create Recipe' ),
                'edit_item'     => __( 'Edit Recipe' )
            ),
            'description'           => 'Mostly regular post type but special coding will happen on these pages to add a "recipe card" view that will extract bits of the post into a nice format.',
            'public'                => true,
            'exclude_from_search'   => false,
            'supports'              => array( 'title', 'editor', 'category' ),
            'taxonomies'            => array( 'category', 'post_tag' ),
        )
    );
    flush_rewrite_rules();
}