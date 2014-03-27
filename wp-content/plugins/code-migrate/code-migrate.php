<?php
/**
 * @package CodeMigrate
 */
/*
Plugin Name: CodeMigrate
Description: Migrates code from one server location to another, assuming all locations are on the same host as the WordPress installation that has this plugin installed.
Version: 0.1
Author: arsdehnel
License: GPLv2 or later
*/

global $code_migrate_db_version, $admin;
$code_migrate_db_version = "0.1";

add_action('admin_menu', 'code_migrate_menu');

function code_migrate_menu() {
	//add_object_page( 'CodeMigrate', 'CodeMigrate', 'edit_plugins', 'code-migrate', 'init');
	add_plugins_page('CodeMigrate', 'CodeMigrate', 'edit_plugins', 'code-migrate', 'init');
	add_submenu_page( null, 'File Listing', 'CodeMigrate Files Listing', 'manage_options', 'code-migrate-files', 'code_migrate_file_listing' ); 
	add_submenu_page( null, 'Processing', 'CodeMigrate Processing', 'manage_options', 'code-migrate-process', 'code_migrate_process' ); 
	add_submenu_page( null, 'Path Processing', 'CodeMigrate Path Processing', 'manage_options', 'code-migrate-path-process', 'code_migrate_path_process' ); 
	add_submenu_page( null, 'Site Processing', 'CodeMigrate Site Processing', 'manage_options', 'code-migrate-site-process', 'code_migrate_site_process' ); 
}

function code_migrate_file_listing() {	
	//include some external assets
	wp_enqueue_style( 'code-migrate-styles', plugins_url().'/code-migrate/styles.css' );
	wp_enqueue_script( 'code-migrate-script', plugins_url().'/code-migrate/main.js', array(), false, true );
	
	//call the view
	include_once dirname( __FILE__ ) . '/migrate-files.php';
}

function code_migrate_process() {	
	//include some external assets
	wp_enqueue_style( 'code-migrate-styles', plugins_url().'/code-migrate/styles.css' );
	wp_enqueue_script( 'code-migrate-script', plugins_url().'/code-migrate/main.js', array(), false, true );
	
	//call the view
	include_once dirname( __FILE__ ) . '/migrate-process.php';
}

function code_migrate_site_process(){
	global $wpdb;
	if( empty( $_POST['cd_mgrt_site_id'] ) ):
		$wpdb->insert( 
					  'wp_codemigrate_sites', 
					  array( 
							'site_name' 	=> $_POST['site_name'], 
							'status_code' 	=> $_POST['status_code'],
							'created_by'	=> get_current_user_id()
					  )
			 		  );//insert
	else:
		$wpdb->update( 
					  'wp_codemigrate_sites', 
					  array( 
							'site_name' 	=> $_POST['site_name'], 
							'status_code' 	=> $_POST['status_code'],
							'modified_by'	=> get_current_user_id()
					  ),
					  array( 
					  		'cd_mgrt_site_id' => $_POST['cd_mgrt_site_id'] 
					  ) 
					  );//update
	endif;
}

function code_migrate_path_process(){
	global $wpdb;
	if( empty( $_POST['cd_mgrt_path_id'] ) ):
		$wpdb->insert( 
					  'wp_codemigrate_paths', 
					  array( 
					  		'cd_mgrt_site_id'	=> $_POST['cd_mgrt_site_id'],
							'path_name'		 	=> $_POST['path_name'],
							'path'				=> $_POST['path'], 
							'status_code' 		=> $_POST['status_code'],
							'created_by'		=> get_current_user_id()
					  )
			 		  );//insert
	else:
		$wpdb->update( 
					  'wp_codemigrate_paths', 
					  array( 
							'path_name'		 	=> $_POST['path_name'],
							'path'				=> $_POST['path'], 
							'status_code' 		=> $_POST['status_code'],
							'modified_by'		=> get_current_user_id()
					  ),
					  array( 
					  		'cd_mgrt_path_id' => $_POST['cd_mgrt_path_id'] 
					  ) 
					  );//update
	endif;
}

function init(){

	//instantiate the db
	global $wpdb;
	
	//setup the modal
	add_thickbox();

	//header
	$admin['header_text'] 		= '<h2>CodeMigrate</h2>';

	//setup the database if it's not already there and adjust the table if needed
	$admin['database_upgrades'] = upgrate_table_sites();
	$admin['database_upgrades'] = array_merge( $admin['database_upgrades'], upgrate_table_paths() );
	
	//get the sites data
	$admin['sites']				= $wpdb->get_results( "SELECT * FROM wp_codemigrate_sites");
	
	//get the path data
	foreach( $admin['sites'] as $key => $site ):
		$admin['sites'][$key]->paths	= $wpdb->get_results( "SELECT * FROM wp_codemigrate_paths WHERE cd_mgrt_site_id = ".$site->cd_mgrt_site_id );
	endforeach;

	//include some external assets
	wp_enqueue_style( 'code-migrate-styles', plugins_url().'/code-migrate/styles.css' );
	wp_enqueue_script( 'code-migrate-script', plugins_url().'/code-migrate/main.js', array(), false, true );
		
	//pass all this to an admin file
	include_once dirname( __FILE__ ) . '/admin.php';
}

function upgrate_table_sites(){
	global $wpdb;
	
	$table_name = $wpdb->prefix . "codemigrate_sites"; 
	$sql = "CREATE TABLE $table_name (
										cd_mgrt_site_id mediumint(9) NOT NULL AUTO_INCREMENT,
										site_name varchar(100) NOT NULL,
										status_code varchar(1) NOT NULL,
										date_created datetime NOT NULL,
										created_by varchar(30),
										date_modified datetime,
										modified_by varchar(30),
										PRIMARY KEY  (cd_mgrt_site_id)
			);";
			
	require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
	return dbDelta( $sql );
}
function upgrate_table_paths(){
	global $wpdb;
	
	$table_name = $wpdb->prefix . "codemigrate_paths"; 
	$sql = "CREATE TABLE $table_name (
										cd_mgrt_path_id mediumint(9) NOT NULL AUTO_INCREMENT,
										cd_mgrt_site_id mediumint(9) NOT NULL,
										path_name varchar(100) NOT NULL,
										path varchar(200) NOT NULL,
										status_code varchar(1) NOT NULL,
										date_created datetime NOT NULL,
										created_by varchar(30),
										date_modified datetime,
										modified_by varchar(30),
										PRIMARY KEY  (cd_mgrt_path_id)
			);";
			
	require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
	return dbDelta( $sql );
}