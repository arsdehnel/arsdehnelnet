<?php
	global $wpdb;
	
	if(!isset($wpdb))
	{
	    require_once('../../../wp-config.php');
	    require_once('../../../wp-load.php');
	    require_once('../../../wp-includes/wp-db.php');
	}
	
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

/* End of file */
/* Location: ./code-migrate/site-process.php */