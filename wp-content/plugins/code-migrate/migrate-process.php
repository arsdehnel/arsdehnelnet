<?php

	echo '<div class="wrap clearfix">';
	echo '<h2>File Migration Results</h2>';
	echo '</div>';

	foreach( $_POST['files'] as $file ):
		$bits 	= explode( '|', $file );
		$action = $bits[0];
		$path	= $bits[1];
		echo $action.": ".$path.'<br>';
	endforeach;
		
/* End of file */
/* Location: ./code-migrate/migrate-files.php */