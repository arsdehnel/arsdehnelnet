<?php
	global $wpdb;
	if(!isset($wpdb))
	{
	    require_once('../../../wp-config.php');
	    require_once('../../../wp-load.php');
	    require_once('../../../wp-includes/wp-db.php');
	}
	$paths = $wpdb->get_results( "SELECT * FROM wp_codemigrate_paths WHERE cd_mgrt_site_id = ".$_GET['cd_mgrt_site_id']);
?>
<form action="plugins.php?page=code-migrate-files" method="post">
	<div class="form-control">
		<label for="sce_path">Source</label>
		<select name="sce_path" class="required">
			<?php
				foreach( $paths as $path ):
					echo '<option value="'.$path->path.'">'.$path->path_name.'</option>';
				endforeach;
			?>
		</select>
	</div>
	<div class="form-control">
		<label for="dest_path">Destination</label>
		<select name="dest_path" class="required">
			<?php
				foreach( $paths as $path ):
					echo '<option value="'.$path->path.'">'.$path->path_name.'</option>';
				endforeach;
			?>
		</select>
	</div>
	<div class="form-actions">
		<button type="submit" class="button button-large">Save</button>
	</div>
</form>