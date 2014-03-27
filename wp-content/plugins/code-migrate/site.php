<?php
	global $wpdb;
	if(!isset($wpdb))
	{
	    require_once('../../../wp-config.php');
	    require_once('../../../wp-load.php');
	    require_once('../../../wp-includes/wp-db.php');
	}
	$site = $wpdb->get_results( "SELECT * FROM wp_codemigrate_sites WHERE cd_mgrt_site_id = ".$_GET['cd_mgrt_site_id'])[0];
	
	if( $site->status_code == 'I' ):
		$active = "";
		$inactive = " selected";
	else:
		$active = " selected";
		$inactive = "";
	endif;
?>
<form action="plugins.php?page=code-migrate-site-process" method="post" class="ajax-form">
	<div class="form-control">
		<label for="site_name">Site</label>
		<div class="form-input">
			<input type="text" name="site_name" class="input-medium required" value="<?php echo $site->site_name; ?>" />
		</div>
	</div>
	<div class="form-control">
		<label for="status_code">Status</label>
		<select name="status_code" class="required">
			<option value="A"<?php echo $active; ?>>Active</option>
			<option value="I"<?php echo $inactive; ?>>Inactive</option>
		</select>
	</div>
	<div class="hide">
		<input type="hidden" name="cd_mgrt_site_id" value="<?php echo $site->cd_mgrt_site_id; ?>" />
	</div>
	<div class="form-actions">
		<button type="submit" class="button button-large">Save</button>
	</div>
</form>