<?php

	if( ! class_exists( 'WP_List_Table' ) ) {
	    require_once( ABSPATH . 'wp-admin/includes/class-wp-list-table.php' );
	}
	
	class PAC_List_Table extends WP_List_Table {
		
		public function __construct( $tablenav ){
            $this->tablenav = $tablenav;
            parent::__construct();
		}

		function get_columns(){
		  $columns = array(
		  	'cb'        => '<input type="checkbox" />',
		    'group_desc'    => 'Group',
		    'pac_grp_mstr_id' => 'ID',
		    'status_code'      => 'Status',
		    'user_count'	=> 'User Count'
		  );
		  return $columns;
		}
		
		function prepare_items( $data ) {

			$columns = $this->get_columns();
			$hidden = array();
			$sortable = $this->get_sortable_columns();
			$per_page = $data['per_page'];
			$current_page = $this->get_pagenum();
			$this->process_bulk_action();
			$paged = isset($_REQUEST['paged']) ? max(0, intval($_REQUEST['paged']) - 1) : 0;
			$this->_column_headers = array($columns, $hidden, $sortable);
			$this->data = $data['table_data'];
			$total_items = $data['total_items'];
			$this->set_pagination_args( array(
    											'total_items' => $total_items,
    											'per_page'    => $per_page
  											) );
			$this->items = $this->data;
		
		}
		function extra_tablenav( $which ) {
			if ( $which == "top" ){
				echo $this->tablenav['top'];
			}
			if ( $which == "bottom" ){
				//The code that goes after the table is there
				echo"Hi, I'm after the table";
			}
		}

		function get_sortable_columns() {
		  $sortable_columns = array(
		    'pac_grp_mstr_id'  => array('pac_grp_mstr_id',false),
		    'group_desc' => array('group_desc',false),
		    'status_code' => array('status_code',false),
		    'user_count'   => array('user_count',false)
		  );
		  return $sortable_columns;
		}

		function column_cb($item) {
        return sprintf(
            '<input type="checkbox" name="%1$s[]" value="%2$s" />', 'pac_grp_mstr_id', $item['pac_grp_mstr_id']
        );    
    }

    	function get_bulk_actions() {
  			$actions = array(
    						'archive'    => 'Archive'
  			);
  			return $actions;
		}
	    function process_bulk_action() {

	    	require_once plugin_dir_path( __FILE__ ) . 'db.php';
	    	$pac_db		= new postaccesscontroller_db();

		    //Detect when a bulk action is being triggered...
		    if( 'archive' === $this->current_action() ) {

                $result = '<div id="message" class="updated"><p>Groups archived:</p><ul>';

		    	foreach( $_GET['pac_grp_mstr_id'] as $pac_grp_mstr_id ):
		    		$results = $pac_db->pac_group_archive_process(array('pac_grp_mstr_id'=>$pac_grp_mstr_id));
                    $result .= '<li>'.$results['mstr_rslt'].'</li>';
            	endforeach;
                $result .= '</ul></div>';

		    }

		    echo $result;

		}
		
		function column_default( $item, $column_name ) {
		  switch( $column_name ) { 
		    case 'pac_grp_mstr_id':
		    case 'group_desc':
		    case 'status_code':
		    case 'user_count':
		      return $item[ $column_name ];
		    default:
		      return print_r( $item, true ) ; //Show the whole array for troubleshooting purposes
		  }
		}
		
		function column_group_desc($item) {
		  $actions = array(
		            'edit'      => sprintf('<a href="?page=%s&pac_grp_mstr_id=%s">Edit</a>','post-access-controller--edit',$item['pac_grp_mstr_id']),
		            'delete'    => sprintf('<a href="?page=%s&pac_grp_mstr_id=%s">Archive</a>','post-access-controller--archive',$item['pac_grp_mstr_id']),
		        );
		
		  return sprintf('%1$s %2$s', $item['group_desc'], $this->row_actions($actions) );
		}
				
	}

/* End of file */
/* Location: ./post-access-controller/classes/list-table.php */