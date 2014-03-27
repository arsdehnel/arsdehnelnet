<?php
/**
 * @package OneCategory
 */
/*
Plugin Name: OneCategory
Description: Makes the post page category checkboxes into radio buttons and removes the "uncategorized" option
Version: 0.1
Author: arsdehnel
License: GPLv2 or later
*/

wp_enqueue_script( 'code-migrate-script', plugins_url().'/one-category/main.js', array(), false, true );