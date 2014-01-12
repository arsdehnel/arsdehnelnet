<?php

	echo '<div class="wrap clearfix">';
	echo '<h2>File Migration Listing</h2>';
	echo '<ul class="subsubsub file-options">';
	echo '<li>Select By Category:</li>';
	echo '<li><a href="#" data-file-type="file-add">New Files to be Added</a>|</li>';
	echo '<li><a href="#" data-file-type="file-remove">Existing Files No Longer Present to be Removed</a>|</li>';
	echo '<li><a href="#" data-file-type="file-update">Existing Files to be Updated</a></li>';
	echo '</ul>';
	echo '</div>';
	
	$sce   = $_POST['sce_path'];
	$files = execute_recursive_directory_crawl($sce, $sce, array() );
	$dest  = $_POST['dest_path'];
	$files = execute_recursive_directory_crawl($dest, $dest, $files );
	
	echo '<form action="plugins.php?page=code-migrate-process" method="post">';
	echo '<table border="1" cellpadding="0" cellspacing="0" class="codemigrate-data">';
	echo '<thead><tr><th>File</th><th>Source<br>Timestamp</th><th>Destination<br>Timestamp</th><th>Category</th></tr></thead>';
	foreach( $files as $file ):
	
		//determine a bunch of variables
		if( file_exists( $sce . '/' . $file ) ):
			$sce_ts = filemtime( $sce . '/' . $file );
			$sce_disp = $sce_ts;
		else:
			$sce_ts = null;
			$sce_disp = '&nbsp;';
		endif;
		if( file_exists( $dest . '/' . $file ) ):
			$dest_ts = filemtime( $dest . '/' . $file );
			$dest_disp = $dest_ts;
		else:
			$dest_ts = null;
			$dest_disp = '&nbsp;';
		endif;
		if( empty( $sce_ts ) ):
			$row_class = 'file-remove';
			$ctgry_desc = 'Remove File';
		elseif( empty( $dest_ts ) ):
			$row_class = 'file-add';
			$ctgry_desc = 'Add File';
		elseif( $sce_ts > $dest_ts ):
			$row_class = 'file-update';
			$ctgry_desc = 'Update File';
		elseif( $dest_ts > $sce_ts ):
			$row_class = 'file-none';
			$ctgry_desc = 'Older File';
		elseif( $dest_ts == $sce_ts ):
			$row_class = 'file-none';
			$ctgry_desc = 'Files Match';
		else:
			$row_class = 'file-unknown';
			$ctgry_desc = 'Unexpected Condition';
		endif;
		
	
		echo '<tr class="'.$row_class.'">';

		//filename
		echo '<td class="align-left"><input type="checkbox" name="files[]" value="'.$row_class.'|'.$file.'" />';
		echo $file;
		echo '</td>';
		
		//source timestamp
		echo '<td>';
		echo $sce_disp;
		echo '</td>';
		
		//destination timestamp
		echo '<td>';
		echo $dest_disp;
		echo '</td>';
		
		//comparison
		echo '<td>';
		echo $ctgry_desc;
		echo '</td>';
		
		echo '</tr>';
	endforeach;
	echo '<tfoot><tr><td colspan="4">';
	echo '<button class="button button-large confirm-submit">Begin Migration</a>';
	echo '</td></tr></tfoot>';
	echo '</table>';
	echo '</form>';
	
	function execute_recursive_directory_crawl( $base, $src, $files )
    { 	
		$handle = opendir($src);                      	// Opens source dir. 
		while ($file = readdir($handle)) { 
			if (($file!=".") and ($file!="..")) {       // Skips . and .. dirs 
			
				$srcm 			= $src . "/" . $file; 
				
				if (is_dir($srcm)) {                      // If another dir is found 
					$files = execute_recursive_directory_crawl( $base, $srcm, $files );               // calls itself - recursive WTG 
   				} else { 
   					if( !in_array( $srcm, $files ) ):
	   					$files[] = str_replace( $base, '', $srcm );
	   				endif;
				}                                             // comment out this line 
			} 
		}	 
		closedir($handle); 
		return $files;
	}

/* End of file */
/* Location: ./code-migrate/migrate-files.php */