<?php
/*
* @package DragonCourier
*/

class DragonDB {

    private function __construct() {

        $this->create_main_db();
        $this->create_address_db();

        add_action( 'wp_ajax_update_tracking_no', array( $this, 'update_tracking_no' ) );
        add_action( 'wp_ajax_tracking_no_assigned', array( $this, 'tracking_no_assigned' ) );
        add_action( 'wp_ajax_complete_transaction', array( $this, 'complete_transaction' ) );
        add_action( 'wp_ajax_cancel_transaction', array( $this, 'cancel_transaction' ) );

    }

    static function getInstance() {

        static $db = null;

        if( $db == null ) {

            $db = new self();

        }

        return $db;

    }

    private function main_table_name(){

        global $wpdb;
        return $wpdb->prefix . 'dragon_courier';

    }

    private function address_table_name(){

        global $wpdb;
        return $wpdb->prefix . 'dragon_courier_addresses';

    }

    private function main_table_exists() {

        global $wpdb;
        return $wpdb->get_var( "SHOW TABLES LIKE '{$this->main_table_name()}'" ) == $this->main_table_name();

    }

    private function address_table_exists() {

        global $wpdb;
        return $wpdb->get_var( "SHOW TABLES LIKE '{$this->address_table_name()}'" ) == $this->address_table_name();

    }

    function create_main_db() {
        
        if(!$this->main_table_exists()) {

            global $wpdb;

            $charset_collate = $wpdb->get_charset_collate();

            $sql = "CREATE TABLE {$this->main_table_name()} (
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
                r_region varchar(100) NOT NULL,
                r_mobile_no varchar(13) NOT NULL,
                r_email varchar(100),
                pkg_weight float(5, 1) NOT NULL,
                pkg_length float(5, 1) NOT NULL,
                pkg_width float(5, 1) NOT NULL,
                pkg_height float(5, 1) NOT NULL,
                pkg_cost float(5, 1) DEFAULT '0.0',
                remarks text,
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

    function create_address_db() {

        if(!$this->address_table_exists()) {

            global $wpdb;

            $charset_collate = $wpdb->get_charset_collate();

            $sql = "CREATE TABLE {$this->address_table_name()} (
                id bigint(20) NOT NULL AUTO_INCREMENT,
                user_id bigint(20) NOT NULL,
                address varchar(255) NOT NULL,
                barangay varchar(100) NOT NULL,
                city varchar(100) NOT NULL,
                region varchar(100) NOT NULL,
                PRIMARY KEY  (id),
                FOREIGN KEY  (user_id) REFERENCES wp_users(ID)
            ) $charset_collate;";

            require_once( ABSPATH . '/wp-admin/includes/upgrade.php' );
            dbDelta( $sql );

            wp_die();

        }

    }

    function insert_schedule_request() {

        if ( !wp_verify_nonce( $_POST['_wpnonce'], 'pickup_schedule' ) ) {

            wp_die( 'Nonce could not be verified!' );

        }

        global $wpdb;

        $wpdb->insert( $this->main_table_name(), array(
            'user_id' => get_current_user_id(),
            'schedule_date' => current_time('mysql'),
            'r_first_name' => $_POST['r_first_name'],
            'r_last_name' => $_POST['r_last_name'],
            'r_address' => $_POST['r_address'],
            'r_barangay' => $_POST['r_barangay'],
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

    function update_tracking_no() {
        /*
         *   This function both adds a tracking no. (after checking it's unique)
         *   AND changes the transaction's status from "Pending" to "Dispatched"
         * 
        */

        if( !isset( $_POST['transaction_id'] ) ) {

            echo json_encode(array( 'status' => 'error', 'msg' => 'Illegal action call' ));
            wp_die();

        }

        global $wpdb;

        $id = $_POST['transaction_id'];
        $tracking_no = $this->get_tracking_no( $id );

        if( $this->tracking_no_used( $tracking_no ) ) {

            echo json_encode(array( 'status' => 'fail', 'msg' => 'The tracking number you entered has already been assigned to a different transaction' ));
            wp_die();
            
        }

        if( $tracking_no == null || $tracking_no == '') {

            $result = $wpdb->update( $this->main_table_name(),                             //table
                                    array(                                                //column=>value
                                        'tracking_no' => $_POST['tracking-no'],
                                        'status' => 'Dispatched'),     
                                    array('id' => $id),                                 //where
                                    array(
                                        '%s', 
                                        '%s'),                                               //value format
                                    '%d');                                              //where format

            if( $result === false ) {

                echo json_encode(array( 'status' => 'error', 'msg' => 'There was an error while updating the transaction information' ));
                wp_die();

            }

        } else {

            echo json_encode(array( 'status' => 'error', 'msg' => 'A tracking number is already set. Please contact the author about this error' ));
            wp_die();

        }

        echo json_encode( array( 'status' => 'success', 'msg' => 'Successfully assigned a tracking number' ) );
        wp_die();        

    }

    function complete_transaction() {

        /*
         *
         * Changes a transaction's status to "Completed"
         * 
         */

        if( !isset( $_POST['transaction_id'] ) ) {

            echo json_encode(array( 'status' => 'error', 'msg' => 'Illegal action call' ));
            wp_die();

        }

        global $wpdb;

        $id = $_POST['transaction_id'];

        $result = $wpdb->update( $this->main_table_name(),
                                array(
                                    'status' => 'Completed',
                                    'complete_date' => current_time('mysql')
                                ),
                                array('id' => $id),
                                array(
                                    '%s',
                                    '%s')
                                );

        if( $result === false ) {

            echo json_encode( array( 'status' => 'error', 'msg' => 'An errorr occured while updating transaction status.'));
            wp_die();

        }

        echo json_encode( array( 'status' => 'success', 'msg' => 'Transaction completed!') );
        wp_die();

    }

    function cancel_transaction() {

        /*
         *
         * Changes a transaction's status to "Cancelled"
         * 
         */

        if( !isset( $_POST['transaction_id'] ) ) {

            echo json_encode(array( 'status' => 'error', 'msg' => 'Illegal action call' ));
            wp_die();

        }

        global $wpdb;

        $id = $_POST['transaction_id'];

        $result = $wpdb->update( $this->main_table_name(),
                                array(
                                    'status' => 'Cancelled'
                                ),
                                array('id' => $id),
                                    '%s'
                                );

        if( $result === false ) {

            echo json_encode( array( 'status' => 'error', 'msg' => 'An errorr occured while updating transaction status.'));
            wp_die();

        }

        echo json_encode( array( 'status' => 'success', 'msg' => 'Transaction cancelled!'));
        wp_die();

    }

    function get_transaction_archive() {

        /*
         *
         * Returns transactions with a status of "Completed" or "Cancelled"
         * 
         */

        global $wpdb;
        $tname = $this->main_table_name();
        $tmeta = $wpdb->prefix . 'usermeta';

        $result = $wpdb->get_results( "SELECT * FROM {$tname} WHERE status = 'Completed' OR status = 'Cancelled'",
                    ARRAY_A);

        if( count( $result ) > 0) {

            $result = $this->merge_meta($result);

        }

        return $result;

        wp_die();

    }

    function get_tracking_no( $id ) {

        global $wpdb;
        $tracking_no = $wpdb->get_var( "SELECT tracking_no FROM {$this->main_table_name()} WHERE id='{$id}'");

        return $tracking_no;

    }

    function tracking_no_used( $tracking_no ) {

        global $wpdb;
        $count = $wpdb->get_var( "SELECT COUNT(*) FROM {$this->main_table_name()} WHERE tracking_no='{$tracking_no}'" );

        if( $count == null || $count == 0 ) return false;

        return true;

    }

    function tracking_no_assigned() {

        global $wpdb;
        $tracking_no = $this->get_tracking_no( intval( $_POST['transaction_id'] ) );

        if( $tracking_no == null || $tracking_no == "" ) {

            echo 'false';
            wp_die();

        }

        echo 'true';
        wp_die();
    }

    function get_incomplete_transactions() {

        global $wpdb;
        $tname = $this->main_table_name();
        $tmeta = $wpdb->prefix . 'usermeta';

        $result = $wpdb->get_results( "SELECT * FROM {$tname} WHERE status = 'Pending' OR status = 'Dispatched'",
                    ARRAY_A);

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

            foreach ( $meta as $item ) {

                $temp[$item['meta_key']] = $item['meta_value'];

            }

            $new_data = array_merge($row, $temp);
            array_push($result, $new_data);

        }

        return $result;

    }
}

