<?php
	global $wpdb;
	
	if(!isset($wpdb))
	{
	    require_once('../../../wp-config.php');
	    require_once('../../../wp-load.php');
	    require_once('../../../wp-includes/wp-db.php');
	}
	
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

/* End of file */
/* Location: ./code-migrate/site-process.php */