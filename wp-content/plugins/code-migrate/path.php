<?php
	global $wpdb;
	if(!isset($wpdb))
	{
	    require_once('../../../wp-config.php');
	    require_once('../../../wp-load.php');
	    require_once('../../../wp-includes/wp-db.php');
	}
	$path = $wpdb->get_results( "SELECT * FROM wp_codemigrate_paths WHERE cd_mgrt_path_id = ".$_GET['cd_mgrt_path_id'])[0];
	
	print_r( $site );
	
	if( !is_object( $path ) ):
		$cd_mgrt_site_id = $_GET['cd_mgrt_site_id'];
	else:
		$cd_mgrt_site_id = $site->cd_mgrt_site_id;
	endif;
	
	if( $path->status_code == 'I' ):
		$active = "";
		$inactive = " selected";
	else:
		$active = " selected";
		$inactive = "";
	endif;
?>
<form action="plugins.php?page=code-migrate-path-process" method="post" class="ajax-form">
	<div class="form-control">
		<label for="path_name">Name</label>
		<input type="text" name="path_name" class="input-large required" value="<?php echo $path->path_name; ?>" />
	</div>
	<div class="form-control">
		<label for="path">Path</label>
		<input type="text" name="path" class="input-large required" value="<?php echo $path->path; ?>" />
	</div>
	<div class="form-control">
		<label for="status_code">Status</label>
		<select name="status_code" class="required">
			<option value="A"<?php echo $active; ?>>Active</option>
			<option value="I"<?php echo $inactive; ?>>Inactive</option>
		</select>
	</div>
	<div class="hide">
		<input type="hidden" name="cd_mgrt_site_id" value="<?php echo $cd_mgrt_site_id; ?>" />
		<input type="hidden" name="cd_mgrt_path_id" value="<?php echo $path->cd_mgrt_path_id; ?>" />
	</div>
	<div class="form-actions">
		<button type="submit" class="button button-large">Save</button>
	</div>
</form>