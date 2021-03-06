<?php
/**
 * Plugin Name: Post Access Controller
 * Plugin URI:  http://arsdehnel.net/plugin/post-access-controller/
 * Description: Allow control of access to individual posts by setting individual users or user groups to have access
 * Version:     0.8
 * Author:      Adam Dehnel
 * Author URI:  http://arsdehnel.net/
 * License:     GPLv2 or later
 * 
 * 
 * 
*/

global $postaccesscontroller_statuses;
$postaccesscontroller_statuses = array('publish'=>'Active','trash'=>'Inactive');

add_action( 'admin_menu'                                , 'admin_menu_setup' );
add_action( 'the_post'                                  , 'postaccesscontroller_check'        , 10, 1 );
add_action( 'admin_enqueue_scripts'                     , 'postaccesscontroller_admin_scripts', 10, 1 );
add_action( 'add_meta_boxes'                            , 'postaccesscontroller_meta_boxes'   , 10, 2 );
add_action( 'wp_ajax_post-access-controller--meta-user' , 'postaccesscontroller_meta_user'    , 10 );
add_action( 'wp_ajax_post-access-controller--meta-group', 'postaccesscontroller_meta_group'   , 10 );
add_action( 'save_post'                                 , 'postaccesscontroller_save_postdata' );
add_action( 'admin_init'                                , 'postaccesscontroller_register_settings' );
add_action( 'show_user_profile'                         , 'postaccesscontroller_user_settings' );
add_action( 'edit_user_profile'                         , 'postaccesscontroller_user_settings' );
add_action( 'personal_options_update'                   , 'postaccesscontroller_save_user_settings' );
add_action( 'edit_user_profile_update'                  , 'postaccesscontroller_save_user_settings' );
add_action( 'init'                                      , 'postaccesscontroller_create_posttypes' );

function admin_menu_setup() {
    add_users_page( 'User Group Maintenance', 'User Groups', 'create_users', 'post-access-controller--main', 'postaccesscontroller_init');
    add_submenu_page( null, 'Group Master Maintenance', 'Group Master Maintenance', 'create_users', 'post-access-controller--edit', 'postaccesscontroller_edit' );
    add_submenu_page( null, 'Group Master Processing', 'Group Master Processing', 'create_users', 'post-access-controller--process', 'postaccesscontroller_process' );
    add_submenu_page( null, 'Group Master Processing', 'Group Master Processing', 'create_users', 'post-access-controller--archive', 'postaccesscontroller_archive' );
    add_options_page( 'Post Access Control', 'Post Access Control', 'edit_plugins', 'post-access-controller--options', 'postaccesscontroller_options' );
}

function postaccesscontroller_create_posttypes() {
    register_post_type( 'pstacsctrlr_grp',
        array(
            'labels' => array(
                'name'          => __( 'Access Control Groups' ),
                'singular_name' => __( 'Access Control Group' ),
                'add_new_item'  => __( 'Create Access Control Group' ),
                'edit_item'     => __( 'Edit Access Control Group' )
            ),
            'description'           => 'Name of the access control group and an array of users',
            'public'                => false,
            'exclude_from_search'   => true,
            'supports'              => array( 'title' )
        )
    );
    flush_rewrite_rules();
}

function postaccesscontroller_meta_user(){  
    postaccesscontroller_meta_options(array('type'=>'user'));
}

function postaccesscontroller_meta_group(){ 
    postaccesscontroller_meta_options(array('type'=>'group'));
}

function postaccesscontroller_register_settings(){
    register_setting( 'postaccesscontroller-settings-group', 'meta_box_location' );
    register_setting( 'postaccesscontroller-settings-group', 'meta_box_priority' );
    register_setting( 'postaccesscontroller-settings-group', 'post_types' );
    register_setting( 'postaccesscontroller-settings-group', 'access_denied_message' );
}

function postaccesscontroller_init(){   

    require_once plugin_dir_path( __FILE__ ) . 'classes/db.php';
    require_once plugin_dir_path( __FILE__ ) . 'classes/list-table.php';
    require_once plugin_dir_path( __FILE__ ) . 'classes/ui.php';

    $pac_db                     = new postaccesscontroller_db();
    $pac_ui                     = new postaccesscontroller_ui();

    $per_page                   = 10;

    //header
    $data['header_text']        = 'Post Access Controller';
    $data['add_new']            = ' <a href="' . get_bloginfo('wpurl') . '/wp-admin/users.php?page=post-access-controller--edit" class="add-new-h2">Add New</a>';

    $status_counts              = $pac_db->pac_grp_mstr_sts_cnt_lkup();

    $tablenav                   = array(
                                        array( 'label' => 'All'
                                              ,'href'  => get_bloginfo('wpurl') . '/wp-admin/users.php?page=post-access-controller--main'
                                              ,'count' => $status_counts['all'] ),
                                        array( 'label' => 'Active'
                                              ,'href'  => get_bloginfo('wpurl') . '/wp-admin/users.php?page=post-access-controller--main&filters=status_code~A'
                                              ,'count' => $status_counts['publish'] ),
                                        array( 'label' => 'Archived'
                                              ,'href'  => get_bloginfo('wpurl') . '/wp-admin/users.php?page=post-access-controller--main&filters=status_code~I'
                                              ,'count' => $status_counts['trash'] )
                                  );
    
    //build the list table
    $pac_list_table             = new PAC_List_Table( array('top'=>$pac_ui->generate_extra_tablenav( $tablenav ) ) );

    $pac_data                   = $pac_db->group_listing_data_lkup( array( 
                                                                           'page'      => $pac_list_table->get_pagenum()
                                                                          ,'per_page'  => $per_page
                                                                          ,'order_by'  => $_GET['orderby']
                                                                          ,'order'     => $_GET['order']
                                                                          ,'filters'   => $_GET['filters']
                                                                         ) );

    $pac_list_table->prepare_items( array( 'table_data'  => $pac_data['display_data']
                                          ,'per_page'    => $per_page
                                          ,'total_items' => $pac_data['total_items'] ) ); 
    $pac_list_table->search_box('search', 'search_id');

    $data['list_table']         = $pac_list_table;
    
    require_once plugin_dir_path( __FILE__ ) . 'views/groups-list.php';
    
    die();

}

function postaccesscontroller_edit(){   

    //instantiate the db class
    require_once plugin_dir_path( __FILE__ ) . 'classes/db.php';
    $pac_db                     = new postaccesscontroller_db();

    //instantiate the ui class
    require_once plugin_dir_path( __FILE__ ) . 'classes/ui.php';
    $pac_ui                     = new postaccesscontroller_ui();

    //header
    $pac['header_text']         = '<h2>Post Access Controller: Edit Group</h2>';
    
    //external files
    wp_enqueue_style( 'pca-styles', plugins_url().'/post-access-controller/admin-general.css' );
    
    if( $_GET['post_id'] ):
        $post_id = $_GET['post_id'];
    else:
        $post_id = 0;
    endif;
    
    //data
    $data                       = $pac_db->group_master_lkup( array( 'post_id'  => $post_id
                                                                    ,'include_users'    => 'Y' ) );

    $pac['group_master']        = $data['group_master'];
    $pac['users']               = $data['users'];

    //breadcrumbs
    $pac['breadcrumbs']         = $pac_ui->generate_breadcrumbs(array(
                                        array('label'   => 'Home'
                                             ,'href'    => get_bloginfo('wpurl') . '/wp-admin/users.php?page=post-access-controller--main'),
                                        array('label'   => 'Group Maintenance'
                                             ,'href'    => null)
                                  ));

    $data['fields'][]           = $pac_ui->generate_form_table_line( 'Group Name', 'TEXT', array( 'current_value' => $pac['group_master']->post_title
                                                                                                 ,'name'          => 'post_title'
                                                                                                 ,'class'         => 'input-medium' ) );
    $data['fields'][]           = $pac_ui->generate_form_table_line( 'Status', 'DROP_DOWN', array( 'current_value' => $pac['group_master']->post_status
                                                                                                  ,'name'          => 'post_status'
                                                                                                  ,'class'         => 'input-medium'
                                                                                                  ,'values'        => array( 'publish' => 'Active'
                                                                                                                            ,'trash'   => 'Inactive' ) ) );
    $data['fields'][]           = $pac_ui->generate_form_table_line( 'Members', 'CHECKBOX_WELL', array( 'name'     => 'post_content'
                                                                                                       ,'options'  => $pac['users'] ) );
        
    //call the view
    include_once plugin_dir_path( __FILE__ ) . 'views/group-edit.php';
    
}

function postaccesscontroller_process(){    

    require_once plugin_dir_path( __FILE__ ) . 'classes/db.php';

    $pac_db                     = new postaccesscontroller_db();
    
    $form_result_data           = $pac_db->pac_group_form_process( $_POST );
    
    if( array_key_exists( 'error', $form_result_data) ):
        echo '<div id="message" class="error"><p>'.$form_result_data['error'].'</p></div>';
    else:
        echo '<div id="message" class="updated"><p>'.$form_result_data['result'].'</p></div>';
    endif;

    ?>
    <div class="form-control">
        <a href="<?php get_bloginfo('wpurl'); ?>/wp-admin/users.php?page=post-access-controller--main" class="button button-large button-primary">Back to Group Listing</a>
    </div>
    <?php
        
}

function postaccesscontroller_archive(){    

    require_once plugin_dir_path( __FILE__ ) . 'classes/db.php';

    $pac_db                     = new postaccesscontroller_db();
    
    $form_result_data           = $pac_db->pac_group_archive_process( $_GET );
    
    if( array_key_exists( 'error', $form_result_data) ):
        echo '<div id="message" class="error"><p>'.$form_result_data['error'].'</p></div>';
    else:
        echo '<div id="message" class="updated"><p>'.$form_result_data['result'].'</p></div>';
    endif;

    ?>
    <div class="form-control">
        <a href="<?php get_bloginfo('wpurl'); ?>/wp-admin/users.php?page=post-access-controller--main" class="button button-large button-primary">Back to Group Listing</a>
    </div>
    <?php
        
}

function postaccesscontroller_check( $post_obj ){

    require_once plugin_dir_path( __FILE__ ) . 'classes/db.php';
    $pac_db                     = new postaccesscontroller_db();

    if( !$pac_db->post_access_allow_check( $post_obj ) ){
        $msg_type = get_post_meta( $post_obj->ID, 'postaccesscontroller_noacs_msg_type', true);
        $post_obj->post_title   = 'Access Denied';
        $post_obj->post_status  = 'private';
        $post_obj->post_type    = 'noaccess';
        if( $msg_type == 'std' ):
            $post_obj->post_content = get_option('access_denied_message');
        else:
            $post_obj->post_content = get_post_meta( $post_obj->ID, 'postaccesscontroller_noacs_custom_msg', true);
        endif; 
    }
}

function postaccesscontroller_meta_boxes( $post_type, $post ) {

    //check to see if $post_type is one that is configured to have this done
    $post_types    = get_option('post_types');
    
    //add the box   
    if( is_array( $post_types ) && in_array( $post_type, $post_types ) ):
    
        //get the post type object for label, etc.
        $post_type_obj = get_post_type_object( $post_type );
        
        //add the box
        add_meta_box( 
            'postaccesscontroller-meta-box',                                //id
            __( $post_type_obj->labels->singular_name . ' Access' ),        //label
            'postaccesscontroller_meta_box',                                //function
            $post_type,                                                     //type
            get_option('meta_box_location'),                                //placement
            get_option('meta_box_priority')                                 //priority
        );

    endif;
    
}

function postaccesscontroller_meta_box( $post ){

    require_once plugin_dir_path( __FILE__ ) . 'classes/ui.php';

    $pac_ui                     = new postaccesscontroller_ui();
  
    $ctrl_type                  = $pac_ui->generate_form_table_line( 'Control Method', 'DROP_DOWN', array( 'current_value' => get_post_meta( $post->ID, 'postaccesscontroller_ctrl_type', true )
                                                                                                    ,'name'          => 'postaccesscontroller_ctrl_type'
                                                                                                    ,'id'            => 'postaccesscontroller_ctrl_type'
                                                                                                    ,'values'         => array( 'none'  => 'Public'
                                                                                                                                ,'user'  => 'By Individual'
                                                                                                                                ,'group' => 'By Group' ) ) );

    $msg_type                   = $pac_ui->generate_form_table_line( 'No Access Message', 'DROP_DOWN', array( 'current_value' => get_post_meta( $post->ID, 'postaccesscontroller_noacs_msg_type', true )
                                                                                                    ,'name'          => 'postaccesscontroller_noacs_msg_type'
                                                                                                    ,'id'            => 'postaccesscontroller_noacs_msg_type'
                                                                                                    ,'values'         => array( 'std'    => 'Default'
                                                                                                                               ,'custom' => 'Custom' ) ) );

    if( get_post_meta( $post->ID, 'postaccesscontroller_noacs_msg_type', true ) == 'custom' ){
        $custom_msg_class = '';
        $std_msg_class    = 'hide';
    }else if( get_post_meta( $post->ID, 'postaccesscontroller_noacs_msg_type', true ) == 'std' ){
        $custom_msg_class = 'hide';
        $std_msg_class    = '';
    }else{
        $custom_msg_class = 'hide';
        $std_msg_class    = 'hide';
    }

    $data['postaccesscontroller_noacs_custom_msg'] = get_post_meta( $post->ID, 'postaccesscontroller_noacs_custom_msg', true );
    
    //call the view
    include_once plugin_dir_path( __FILE__ ) . 'views/meta-box.php';
    
}

function postaccesscontroller_meta_options( $data ){
                                               
    require_once plugin_dir_path( __FILE__ ) . 'classes/db.php';

    $pac_db                     = new postaccesscontroller_db();

    $data['options']            = $pac_db->meta_options_lkup( $data );

    //print_r( $data['options'] );
    
    if( $data['type'] == 'user' ):
    
        $data['list_label']     = 'Select Users';
        $data['label_field']    = 'display_name';
    
    elseif( $data['type'] == 'group' ):
    
        $data['list_label']     = 'Select Groups';
        $data['label_field']    = 'post_title';
    
    endif;

    //get the current meta data and put it into an array
    $data['current']            = get_post_meta( $_POST['post_id'], 'postaccesscontroller_meta_'.$data['type'], true );
    
    //call the view
    require_once plugin_dir_path( __FILE__ ) . 'views/meta-options.php';

    //need this so the wp_ajax call returns properly    
    die();

}

function postaccesscontroller_save_postdata( $post_id ) {

  // Check if our nonce is set.
  if ( ! isset( $_POST['postaccesscontroller_sec_field_nonce'] ) )
    return $post_id;

  $nonce = $_POST['postaccesscontroller_sec_field_nonce'];

  // Verify that the nonce is valid.
  if ( ! wp_verify_nonce( $nonce, 'postaccesscontroller_sec_field' ) )
      return $post_id;

  // If this is an autosave, our form has not been submitted, so we don't want to do anything.
  if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) 
      return $post_id;

  // Check the user's permissions.
  if ( 'page' == $_POST['post_type'] ) {

    if ( ! current_user_can( 'edit_page', $post_id ) )
        return $post_id;
  
  } else {

    if ( ! current_user_can( 'edit_post', $post_id ) )
        return $post_id;
  }

  /* OK, its safe for us to save the data now. */

  // Sanitize user input.
  $pac_ctrl_type = sanitize_text_field( $_POST['postaccesscontroller_ctrl_type'] );
  $postaccesscontroller_noacs_custom_msg = sanitize_text_field( $_POST['postaccesscontroller_noacs_custom_msg'] );

  // Update the meta field in the database.
  update_post_meta( $post_id, 'postaccesscontroller_ctrl_type', $pac_ctrl_type );
  update_post_meta( $post_id, 'postaccesscontroller_meta_user', $_POST['postaccesscontroller_meta_user'] );
  update_post_meta( $post_id, 'postaccesscontroller_meta_group', $_POST['postaccesscontroller_meta_group'] );
  update_post_meta( $post_id, 'postaccesscontroller_noacs_msg_type', $_POST['postaccesscontroller_noacs_msg_type'] );
  update_post_meta( $post_id, 'postaccesscontroller_noacs_custom_msg', $postaccesscontroller_noacs_custom_msg );
  
  
}

function postaccesscontroller_options(){

    require_once plugin_dir_path( __FILE__ ) . 'classes/ui.php';

    $pac_ui                     = new postaccesscontroller_ui();

    //header
    $data['header_text']        = '<h1>Post Access Controller: Options</h1>';

    $post_types                 = get_post_types( array( 'public' => true ), 'object' );

    foreach( $post_types as $post_type => $post_type_obj ):
        if( in_array( $post_type, get_option('post_types') ) ):
            $selected = 'Y';
        else:
            $selected = 'N';
        endif;
        $well_data[]            = array( 'value'    => $post_type
                                        ,'label'    => $post_type_obj->labels->name
                                        ,'selected' => $selected );
    endforeach;


    $overall['fields'][]        = $pac_ui->generate_form_table_line( 'Post Types', 'CHECKBOX', array( 'name'          => 'post_types'
                                                                                                    ,'options'         => $well_data ) );

    $overall['fields'][]        = $pac_ui->generate_form_table_line( 'Access Denied Message', 'TEXTAREA', array( 'name'          => 'access_denied_message'
                                                                                                                ,'current_value' => get_option('access_denied_message')
                                                                                                                ,'class'         => 'input-large input-textareaheight-medium' ) );

    $post_maint['fields'][]     = $pac_ui->generate_form_table_line( 'Location', 'DROP_DOWN', array( 'current_value' => get_option('meta_box_location')
                                                                                                    ,'name'          => 'meta_box_location'
                                                                                                    ,'values'         => array('normal'    => 'Below post editor field'
                                                                                                                              ,'advanced'  => 'Only as option enabled in the "Screen Options" panel'
                                                                                                                              ,'side'      => 'Along right side') ) );

    $post_maint['fields'][]     = $pac_ui->generate_form_table_line( 'Priority', 'DROP_DOWN', array( 'current_value' => get_option('meta_box_priority')
                                                                                                    ,'name'          => 'meta_box_priority'
                                                                                                    ,'values'         => array('high'      => 'High'
                                                                                                                               ,'core'      => 'Core'
                                                                                                                               ,'default'   => 'Default'
                                                                                                                               ,'low'       => 'Low') ) );
                               
    //external files
    wp_enqueue_style( 'pca-styles', plugins_url().'/post-access-controller/admin-general.css' );

    //call the view
    require_once plugin_dir_path( __FILE__ ) . 'views/options.php';

}

function postaccesscontroller_user_settings(){

    //external files
    wp_enqueue_style( 'pca-styles', plugins_url().'/post-access-controller/admin-general.css' );

    require_once plugin_dir_path( __FILE__ ) . 'classes/db.php';
    require_once plugin_dir_path( __FILE__ ) . 'classes/ui.php';

    $pac_db                     = new postaccesscontroller_db();
    $pac_ui                     = new postaccesscontroller_ui();
    
    if( $_GET['user_id'] ):
        $user_id = $_GET['user_id'];
    else:
        $user_id = get_current_user_id();
    endif;
    $data['groups']             = $pac_db->user_groups_lkup(array( 'user_id' => $user_id ) );   

    $data['group_well']         = $pac_ui->generate_checkbox_well( array( 'name' => 'post_id'
                                                                         ,'options' => $data['groups'] ) );
   
    //call the view
    require_once plugin_dir_path( __FILE__ ) . 'views/user-profile.php';
} 

function postaccesscontroller_save_user_settings( $user_id ){
    
    if ( !current_user_can( 'edit_user', $user_id ) )
        return FALSE;

    require_once plugin_dir_path( __FILE__ ) . 'classes/db.php';
    $pac_db                     = new postaccesscontroller_db();

    $form_result_data           = $pac_db->pac_user_form_process( $_POST );

}

function postaccesscontroller_admin_scripts( $hook ) {

    global $post;

    if ( $hook == 'post-new.php' || $hook == 'post.php' ) {
        wp_enqueue_style(  'postaccesscontroller-admin-general', plugins_url().'/post-access-controller/admin-general.css' );
        wp_enqueue_script( 'postaccesscontroller-meta-box-script', plugins_url().'/post-access-controller/meta-box.js' );
    }
    
}