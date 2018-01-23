<?php
/*
* @package DragonCourier
*/

class DragonDB {

    private function __construct() {

        $this->create_db();

    }

    static function getInstance() {

        static $db = null;

        if( $db == null ) {

            $db = new self();

        }

        return $db;
    }

    private function get_table_name(){

        global $wpdb;
        return $wpdb->prefix . 'dragon_courier';

    }

    private function table_exists() {

        global $wpdb;
        return $wpdb->get_var( "SHOW TABLES LIKE '{$this->get_table_name()}'" ) == $this->get_table_name();

    }

    function create_db() {
        
        if(!$this->table_exists()) {

            global $wpdb;

            $charset_collate = $wpdb->get_charset_collate();

            $sql = "CREATE TABLE {$this->get_table_name()} (
                id bigint(20) NOT NULL AUTO_INCREMENT,
                user_id bigint(20) NOT NULL,
                schedule_date datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
                pickup_date datetime,
                dispatch_date datetime,
                complete_date datetime,
                tracking_no varchar(12),
                r_first_name varchar(100) NOT NULL,
                r_last_name varchar(100) NOT NULL,
                r_address varchar(255) NOT NULL,
                r_barangay varchar(100) NOT NULL,
                r_city varchar(100) NOT NULL,
                r_postal varchar(5) NOT NULL,
                r_mobile_no varchar(13) NOT NULL,
                r_email varchar(100),
                pkg_weight float(5, 1) NOT NULL,
                pkg_length float(5, 1) NOT NULL,
                pkg_width float(5, 1) NOT NULL,
                pkg_height float(5, 1) NOT NULL,
                pkg_cost float(5, 1) DEFAULT '0.0',
                status varchar(50) DEFAULT 'Pending',
                PRIMARY KEY  (id),
                FOREIGN KEY  (user_id) REFERENCES wp_users(ID),
                UNIQUE (tracking_no)
            ) $charset_collate;";

            require_once( ABSPATH . '/wp-admin/includes/upgrade.php' );
            dbDelta( $sql );

            wp_die();

        }

    }

    function insert_schedule_request() {

        global $wpdb;

        $wpdb->insert( $this->get_table_name(), array(
            'user_id' => get_current_user_id(),
            'schedule_date' => current_time('mysql'),
            'r_first_name' => $_POST['r_first_name'],
            'r_last_name' => $_POST['r_last_name'],
            'r_address' => $_POST['r_address'],
            'r_barangay' => $_POST['r_brgy'],
            'r_city' => $_POST['r_city'],
            'r_postal' => $_POST['r_postal'],
            'r_mobile_no' => $_POST['r_mobile_no'],
            'r_email' => $_POST['r_email'],
            'pkg_weight' => $_POST['pkg_weight'],
            'pkg_length' => $_POST['pkg_length'],
            'pkg_width' => $_POST['pkg_width'],
            'pkg_height' => $_POST['pkg_height'],
            'pkg_cost' => $_POST['pkg_cost']
        ), array(
            '%d',
            '%s',
            '%s',
            '%s',
            '%s',
            '%s',
            '%s',
            '%s',
            '%s',
            '%s',
            '%f',
            '%f',
            '%f',
            '%f',
            '%f'
        ));

        //echo $wpdb->show_errors();

        $url = wp_nonce_url( home_url( 'success-page' ), 'post_sched' );

        wp_redirect( $url );

    }

    function get_incomplete_transactions() {

        global $wpdb;
        $tname = $this->get_table_name();
        $tmeta = $wpdb->prefix . 'usermeta';

        $result = $wpdb->get_results( "SELECT * FROM {$tname} WHERE status != 'Completed'",
                    ARRAY_A);

        // $result = $wpdb->get_results( "SELECT x.*, 
        //                             CONCAT((SELECT y.meta_value 
        //                                 FROM {$tmeta} as y 
        //                                 WHERE y.user_id = x.user_id AND y.meta_key='last_name'), 
        //                                 ', ', 
        //                                 (SELECT y.meta_value 
        //                                 FROM {$tmeta} as y 
        //                                 WHERE y.user_id = x.user_id AND y.meta_key='first_name')) as customer 
        //                             FROM {$tname} as x
        //                             WHERE x.status = 'Pending';",
        //             ARRAY_A);

        // if( $result == null ) {

        //     echo "There was an error with Dragon Courier plugin. Please contact the author.";

        // }

        if( count( $result ) > 0) {

            $result = $this->merge_meta($result);

        }

        return $result;

        wp_die();

    }

    function get_usermeta($user_id) {

        global $wpdb;

        $result = $wpdb->get_results( "SELECT meta_key, meta_value FROM wp_usermeta 
                                    WHERE user_id = " . $user_id . " AND meta_key = 'first_name' 
                                    OR user_id = " . $user_id . " AND meta_key = 'last_name'
                                    OR user_id = " . $user_id . " AND meta_key = 'street_address'
                                    OR user_id = " . $user_id . " AND meta_key = 'barangay'
                                    OR user_id = " . $user_id . " AND meta_key = 'city'
                                    OR user_id = " . $user_id . " AND meta_key = 'postal_code'",  ARRAY_A );

        return $result;

    }

    function merge_meta($data) {

        $result = array();

        foreach( $data as $row ) {

            $temp = array();
            $meta = $this->get_usermeta($row['user_id']);
            //var_dump($meta);

            foreach ( $meta as $item ) {

                $temp[$item['meta_key']] = $item['meta_value'];

            }

            $new_data = array_merge($row, $temp);
            array_push($result, $new_data);

        }

        return $result;

    }
}

