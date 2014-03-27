<?php

	//header
	echo $admin['header_text'];
	
	//report any database changes
	if( is_array( $admin['database_upgrades'] ) && count( $admin['database_upgrades'] ) ):
		echo '<div id="message" class="updated"><p>Database Upgrades:</p><ul>';
		foreach( $admin['database_upgrades'] as $result ):
			echo '<li>'.$result.'</li>';
		endforeach;
		echo '</ul></div>';
	else:
		echo '<div id="message" class="updated"><p>Current database version confirmed.</p></div>';
	endif;
		
	//build the sites listing
	echo '<table class="codemigrate-data outer" cellpadding="0" cellspacing="0" border="0">';
	echo '<thead><tr><th>ID</th><th>Name</th><th>Status</th><th>Actions</th></tr></thead>';
	if( is_array( $admin['sites'] ) && count( $admin['sites'] ) ):
		echo '<tbody>';
		foreach( $admin['sites'] as $site ):
			echo '<tr>';
			echo '<td>'.$site->cd_mgrt_site_id.'</td>';
			echo '<td>'.$site->site_name.'</td>';
			echo '<td>'.$site->status_code.'</td>';
			echo '<td>';
			echo '<a href="'.plugins_url().'/code-migrate/site.php?width=380&height=170&cd_mgrt_site_id='.$site->cd_mgrt_site_id.'" class="thickbox button button-small"  title="Site Maintenance">Edit</a>';
			echo '<a href="#" class="button button-small inner-table-toggle">Paths</a>';
			if( is_array( $site->paths ) && count( $site->paths ) ):
				echo '<a href="'.plugins_url().'/code-migrate/migrate-select.php?width=380&height=170&cd_mgrt_site_id='.$site->cd_mgrt_site_id.'" class="thickbox button button-small"  title="Configure Migration">Migrate</a>';
			endif;
			echo '</td>';
			echo '</tr>';
			echo '<tr class="hide">';
			echo '<td></td>';
			echo '<td colspan="3">';
			echo '<table class="codemigrate-data inner" cellpadding="0" cellspacing="0" border="0">';
			echo '<thead><tr><th>ID</th><th>Name</th><th>Path</th><th>Status</th><th>Actions</th></tr></thead>';
			if( is_array( $site->paths ) && count( $site->paths ) ):
				echo '<tbody>';
				foreach( $site->paths as $path ):
					echo '<tr>';
					echo '<td>'.$path->cd_mgrt_path_id.'</td>';
					echo '<td>'.$path->path_name.'</td>';
					echo '<td>'.$path->path.'</td>';
					echo '<td>'.$path->status_code.'</td>';
					echo '<td>';
					echo '<a href="'.plugins_url().'/code-migrate/path.php?width=700&height=170&cd_mgrt_path_id='.$path->cd_mgrt_path_id.'" class="thickbox button button-small"  title="Path Maintenance">Edit</a>';
					echo '</td>';
					echo '</tr>';
				endforeach;
				echo '</tbody>';
			endif;
			echo '<tfoot>';
			echo '<tr><td colspan="5">';
			echo '<a href="'.plugins_url().'/code-migrate/path.php?width=700&height=170&cd_mgrt_site_id='.$site->cd_mgrt_site_id.'" class="thickbox button button-small" title="Site Maintenance">Add Path</a>';
			echo '</td></tr>';
			echo '</tfoot>';
			echo '</table>';
			echo '</td>';
			echo '</tr>';
		endforeach;
		echo '</tbody>';
	endif;
	echo '<tfoot><tr><td colspan="4">';
	echo '<a href="'.plugins_url().'/code-migrate/site.php?width=380&height=170" class="thickbox button button-large" title="Site Maintenance">Add Site</a>';
	echo '</td></tr></tfoot></table>';
	
/* End of file */
/* Location: ./code-migrate/admin.php */