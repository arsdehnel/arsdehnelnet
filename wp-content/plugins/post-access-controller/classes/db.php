<?php

    class postaccesscontroller_db{
    
        public function __construct(){
            global $wpdb;
            $this->wpdb = &$wpdb;
        }
        
        function db_check(){
        
            require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
    
            //setup the database if it's not already there and adjust the table if needed
            $results = $this->upgrade_table_postaccess_group_master();
            $results = array_merge( $results, $this->upgrade_table_postaccess_group_detail() );
            
            //report any database changes
            if( is_array( $results ) && count( $results ) ):
                $return = '<div id="message" class="updated"><p>Database Upgrades:</p><ul>';
                foreach( $results as $result ):
                    $return .= '<li>'.$result.'</li>';
                endforeach;
                $return .= '</ul></div>';
            else:
                $return = '';
            endif;
            
            return $return;
            
        }
        
        private function upgrade_table_postaccess_group_master(){
            $table_name = $this->wpdb->prefix . "postaccess_group_master"; 
            $sql = "CREATE TABLE $table_name (
                                                pac_grp_mstr_id mediumint(9) NOT NULL AUTO_INCREMENT,
                                                group_desc varchar(100) NOT NULL,
                                                status_code varchar(1) NOT NULL,
                                                date_created datetime NOT NULL,
                                                created_by varchar(30),
                                                date_modified datetime,
                                                modified_by varchar(30),
                                                PRIMARY KEY  (pac_grp_mstr_id)
                    );";
            return dbDelta( $sql );
        }
        private function upgrade_table_postaccess_group_detail(){
            $table_name = $this->wpdb->prefix . "postaccess_group_detail"; 
            $sql = "CREATE TABLE $table_name (
                                                pac_grp_dtl_id mediumint(9) NOT NULL AUTO_INCREMENT,
                                                pac_grp_mstr_id mediumint(9) NOT NULL,
                                                wp_user_id bigint(20) NOT NULL,
                                                status_code varchar(1) NOT NULL,
                                                date_created datetime NOT NULL,
                                                created_by varchar(30),
                                                date_modified datetime,
                                                modified_by varchar(30),
                                                PRIMARY KEY  (pac_grp_dtl_id)
                    );";
            return dbDelta( $sql );
        }
        
        public function pac_group_form_process( $data ){
        
            $mstr_data  = $this->pac_group_master_save( $data );
            
            if( array_key_exists( 'error', $mstr_data ) ):
                return $mstr_data['error'];
            endif;
            
            if( $data['status_code'] == 'I' ):
                return $mstr_data['result'];
            endif;

            $results['mstr_rslt']   = $mstr_data['result'];         
            $results['dtl_ins_cnt'] = 0;
            $results['dtl_upd_cnt'] = 0;
        
            //inactivate all the details
            $this->wpdb->update( 
                                  'wp_postaccess_group_detail', 
                                  array( 
                                        'status_code'   => 'I',
                                        'modified_by'   => get_current_user_id(),
                                        'date_modified' => date('Y-m-d H:i:s')
                                  ),
                                  array( 
                                        'pac_grp_mstr_id' => $mstr_data['pac_grp_mstr_id']
                                  ) 
                                  );//update

            //iterate through details in the form data and insert or update
            if( array_key_exists( 'pac_group_users', $data ) && is_array( $data['pac_group_users'] ) ):

                foreach( $data['pac_group_users'] as $user_id ):
                
                    $dtl_data = $this->pac_group_detail_save( array('wp_user_id'        => $user_id,
                                                                    'pac_grp_mstr_id'   => $mstr_data['pac_grp_mstr_id'], 
                                                                    'status_code'       => $data['status_code'],
                                                                    'cur_user_id'       => get_current_user_id()
                                                                    )
                                                            );
                                                            
                    if( array_key_exists( 'error', $dtl_data ) ):
                        return 'Error saving a detail (user_id: '.$user_id.')';
                    else:
                        if( $dtl_data['txn_type'] == 'INS' ):
                            $results['dtl_ins_cnt']++;
                        else:
                            $results['dtl_upd_cnt']++;
                        endif;
                    endif;
                
                endforeach;

            endif;
            
            return $results;
        
        }

        public function pac_group_archive_process( $data ){

            $master     = $this->group_master_lkup( $data );

            $save_data['pac_grp_mstr_id']  = $master->pac_grp_mstr_id;
            $save_data['group_desc']       = $master->group_desc;
            $save_data['status_code']      = 'I';
        
            $mstr_data  = $this->pac_group_master_save( $save_data );
            
            if( array_key_exists( 'error', $mstr_data ) ):
                return $mstr_data['error'];
            endif;
            
            if( $data['status_code'] == 'I' ):
                return $mstr_data['result'];
            endif;

            $results['mstr_rslt']   = $mstr_data['result'];         
            
            return $results;
        
        }
        
        public function group_master_lkup( $data ){

            //localize
            extract( $data );

            $group_master               = $this->wpdb->get_results( "SELECT * FROM wp_postaccess_group_master WHERE pac_grp_mstr_id = $pac_grp_mstr_id")[0];

            if( $include_users == 'Y' ):
                $group_master->users    = $this->wpdb->get_results( "SELECT * FROM wp_users u LEFT JOIN (SELECT pac_grp_dtl_id
                                                                                                               ,wp_user_id
                                                                                                         FROM wp_postaccess_group_detail
                                                                                                         WHERE pac_grp_mstr_id = $pac_grp_mstr_id
                                                                                                           AND status_code = 'A') d ON u.id = d.wp_user_id");
            endif;

            return $group_master;
        }

        private function pac_group_master_save( $data ){
        
            if( empty( $data['pac_grp_mstr_id'] ) ):

                $result = $this->wpdb->insert( 
                                              'wp_postaccess_group_master', 
                                              array( 
                                                    'group_desc'    => $data['group_desc'], 
                                                    'status_code'   => $data['status_code'],
                                                    'created_by'    => get_current_user_id(),
                                                    'date_created'  => date('Y-m-d H:i:s')
                                              )
                                              );//insert
                                              
                if( $result === false ){
                    $return = array( 'error'            => 'Something happened that was not what we wanted (pac_mstr_ins)'
                                    );
                }else{
                    $return = array( 'pac_grp_mstr_id'  => $this->wpdb->insert_id
                                    ,'result'           => 'Group "' . $data['group_desc'] . '" created successfully'
                                    ,'txn_type'         => 'INS'
                                    );
                }
                
            else:

                $result = $this->wpdb->update( 
                                              'wp_postaccess_group_master', 
                                              array( 
                                                    'group_desc'    => $data['group_desc'], 
                                                    'status_code'   => $data['status_code'],
                                                    'modified_by'   => get_current_user_id(),
                                                    'date_modified' => date('Y-m-d H:i:s')
                                              ),
                                              array( 
                                                    'pac_grp_mstr_id' => $data['pac_grp_mstr_id'] 
                                              ) 
                                              );//update
                if( $result === false ){
                    $return = array( 'error'            => 'Something happened that was not what we wanted (pac_mstr_upd)'.$this->wpdb->print_error()
                                    );
                }else{
                    $return = array( 'pac_grp_mstr_id'  => $data['pac_grp_mstr_id']
                                    ,'result'           => 'Group "' . $data['group_desc'] . '" saved successfully'
                                    ,'txn_type'         => 'UPD'
                                    );
                }

            endif;

            return $return;
            
        }
        
        private function pac_group_detail_save( $data ){
        
            extract( $data );
        
            //try to look it up
            $existing_rec = $this->wpdb->get_results( "SELECT * FROM wp_postaccess_group_detail WHERE pac_grp_mstr_id = $pac_grp_mstr_id AND wp_user_id = $wp_user_id" )[0];

            //print_r( $existing_rec );
            
            if( empty( $existing_rec ) ):
            
                $result = $this->wpdb->insert( 
                                              'wp_postaccess_group_detail', 
                                              array( 
                                                    'pac_grp_mstr_id'   => $pac_grp_mstr_id, 
                                                    'wp_user_id'        => $wp_user_id,
                                                    'status_code'       => $status_code,
                                                    'created_by'        => $cur_user_id,
                                                    'date_created'      => date('Y-m-d H:i:s')
                                              )
                                              );//insert
                        
                if( $result === false){
                    $return = array( 'error'            => 'Something happened that was not what we wanted (pac_dtl_ins)'
                                    );
                }else{
                    $return = array( 'pac_grp_dtl_id'   => $this->wpdb->insert_id
                                    ,'result'           => 'User "' . $wp_user_id . '" added successfully'
                                    ,'txn_type'         => 'INS'
                                    );
                }
                
            else:
            
                $results = $this->wpdb->update( 
                                              'wp_postaccess_group_detail', 
                                              array( 
                                                    'status_code'       => $status_code,
                                                    'modified_by'       => get_current_user_id(),
                                                    'date_modified'     => date('Y-m-d H:i:s')
                                              ),
                                              array( 
                                                    'pac_grp_mstr_id'   => $existing_rec->pac_grp_mstr_id,
                                                    'wp_user_id'        => $wp_user_id
                                              ) 
                                              );//update

                if( $result === false ){
                    $return = array( 'error'            => 'Something happened that was not what we wanted (pac_mstr_upd)'
                                    );
                }else{
                    $return = array( 'pac_grp_dtl_id'   => $existing_rec->pac_grp_dtl_id
                                    ,'result'           => 'User "' . $wp_user_id . '" saved successfully'
                                    ,'txn_type'         => 'UPD'
                                    );
                }
                
            endif;
            
            return $return;

        }

        public function meta_options_lkup( $data ){

            if( $data['type'] == 'user' ):
            
                $options    = $this->wpdb->get_results( "SELECT id
                                                               ,display_name as label
                                                         FROM wp_users");

            elseif( $data['type'] == 'group' ):
            
                $options    = $this->wpdb->get_results( "SELECT pac_grp_mstr_id as id
                                                               ,group_desc as label
                                                         FROM wp_postaccess_group_master
                                                         WHERE status_code = 'A'");

            endif;

            return $options;

        }
        
        public function user_groups_lkup( $data ){

            //localize
            extract( $data );

            $groups               = $this->wpdb->get_results( "SELECT m.pac_grp_mstr_id
                                                                     ,m.group_desc
                                                                     ,m.status_code
                                                                     ,d.pac_grp_dtl_id
                                                                     ,d.wp_user_id
                                                               FROM wp_postaccess_group_master m
                                                                 LEFT JOIN (SELECT *
                                                                            FROM wp_postaccess_group_detail
                                                                            WHERE wp_user_id = $user_id
                                                                              AND status_code = 'A') d ON m.pac_grp_mstr_id = d.pac_grp_mstr_id
                                                               WHERE m.status_code = 'A'
                                                               ORDER BY m.group_desc");

            return $groups;
        }

        public function group_masters_lkup( $data ){

            $group_masters              = $this->wpdb->get_results( "SELECT * FROM wp_postaccess_group_master WHERE status_code = 'A'");

            return $group_masters;
        }

        public function pac_user_form_process( $data ){

            $group_masters              = $this->group_masters_lkup();

            foreach( $group_masters as $group_master ):

                $save_data['pac_grp_mstr_id']   = $group_master->pac_grp_mstr_id;
                $save_data['wp_user_id']        = $data['user_id'];
                $save_data['status_code']       = 'I';
                $save_data['cur_user_id']       = get_current_user_id();
                $this->pac_group_detail_save($save_data);

            endforeach;

            foreach( $data['pac_grp_dtls'] as $pac_grp_mstr_id ):
                $save_data['pac_grp_mstr_id']   = $pac_grp_mstr_id;
                $save_data['wp_user_id']        = $data['user_id'];
                $save_data['status_code']       = 'A';
                $save_data['cur_user_id']       = get_current_user_id();
                $this->pac_group_detail_save($save_data);
            endforeach;

        }

        public function group_listing_data_lkup( $data ){

            //the most basic of queries to do what we need
            $base_sql   = "SELECT m.pac_grp_mstr_id
                                 ,m.group_desc
                                 ,m.status_code
                                 ,coalesce(d.user_count,0) user_count
                           FROM wp_postaccess_group_master m 
                             LEFT JOIN (SELECT pac_grp_mstr_id
                                              ,count(1) user_count
                                        FROM wp_postaccess_group_detail
                                        WHERE status_code = 'A'
                                        GROUP BY pac_grp_mstr_id) d ON m.pac_grp_mstr_id = d.pac_grp_mstr_id";

            if( array_key_exists( 'filters', $data ) && !empty( $data['filters'] ) ):
                $base_sql .= ' WHERE';
                foreach( explode( '|', $data['filters'] ) as $filter ):
                    list( $field, $value ) = explode( '~', $filter );
                    $base_sql .= " " . $field . " = '" . $value . "'"; 
                endforeach;
            endif;

            //run this here so we can get the total count
            $return['total_items'] = count($this->wpdb->get_results( $base_sql, ARRAY_A ));

            if( array_key_exists( 'order_by', $data ) && !empty( $data['order_by'] ) ):
                $base_sql = $base_sql . ' ORDER BY ' . $data['order_by'];
                if( array_key_exists( 'order', $data ) ):
                    $base_sql = $base_sql . ' ' . $data['order'];
                endif;
            endif;
            if( array_key_exists( 'per_page', $data ) ):
                $base_sql = $base_sql . ' LIMIT ' . $data['per_page'];
                if( array_key_exists( 'page', $data ) ):
                    $base_sql = $base_sql . ' OFFSET ' . ( ( $data['page'] - 1 ) * $data['per_page'] );
                endif;
            endif;
            //echo $base_sql;

            $return['display_data'] = $this->wpdb->get_results( $this->wpdb->prepare($base_sql, 3, $paged), ARRAY_A);
            return $return;

        }

        public function pac_grp_mstr_sts_cnt_lkup(){
            $status_counts = $this->wpdb->get_results( "SELECT status_code, count(1) as rec_count FROM wp_postaccess_group_master GROUP BY status_code
                                                        UNION
                                                        SELECT 'ALL', count(1) FROM wp_postaccess_group_master");

            return $status_counts;            
        }

        public function post_access_allow_check( $post_obj ){

            if( get_post_meta( $post_obj->ID, 'postaccesscontroller_ctrl_type', true ) == 'user' ){
                if( is_user_logged_in() ):
                    $users = get_post_meta( $post_obj->ID, 'postaccesscontroller_meta_user', true );
                    if( in_array( get_current_user_id(), $users ) ):
                        return TRUE;
                    else:
                        return FALSE;
                    endif;
                else:
                    return FALSE;
                endif;
            }
            if( get_post_meta( $post_obj->ID, 'postaccesscontroller_ctrl_type', true ) == 'group' ){
                if( is_user_logged_in() ):
                    $pac_grp_mstr_str = implode( ',', get_post_meta( $post_obj->ID, 'postaccesscontroller_meta_group', true ) );
                    $wp_user_id       = get_current_user_id();
                    $rec_count        = $this->wpdb->get_results( "SELECT * 
                                                                   FROM wp_postaccess_group_master m
                                                                     INNER JOIN wp_postaccess_group_detail d ON m.pac_grp_mstr_id = d.pac_grp_mstr_id
                                                                   WHERE m.pac_grp_mstr_id in ($pac_grp_mstr_str)
                                                                     AND d.wp_user_id = $wp_user_id
                                                                     AND d.status_code = 'A'
                                                                     AND m.status_code = 'A'",ARRAY_A);

                    if( count($rec_count) > 0 ):
                        return TRUE;
                    else:
                        return FALSE;
                    endif;
                else:
                    return FALSE;
                endif;
            }
            return TRUE;

        }

    }
    

/* End of file */
/* Location: ./post-access-controller/classes/db.php */