<?php

	//localize everything down one level from the $data array
	extract( $data );

	//header
	echo $header_text;
	
	//display any db updates
	echo $db_upd_rslt;
	
	echo '<form id="events-filter" method="get">';
	echo '<input type="hidden" name="page" value="'.$_REQUEST['page'].'" />';
	$list_table->display();
	echo '</form>';
	
	echo '<a href="' . get_bloginfo('wpurl') . '/wp-admin/users.php?page=post-access-controller--edit" class="button button-large button-primary" title="Group Master Maintenance">Add Group</a>';

/* End of file */
/* Location: ./post-access-controller/groups-list.php */