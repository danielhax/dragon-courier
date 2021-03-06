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
                date_submitted datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
                schedule_date datetime DEFAULT '0000-00-00' NOT NULL,
                pickup_date datetime,
                dispatch_date datetime,
                complete_date datetime,
                tracking_no varchar(16),
                pickup_address bigint(20) NOT NULL,
                r_first_name varchar(100) NOT NULL,
                r_last_name varchar(100) NOT NULL,
                r_address varchar(255) NOT NULL,
                r_barangay varchar(100) NOT NULL,
                r_city varchar(100) NOT NULL,
                r_region varchar(100) NOT NULL,
                r_mobile_no varchar(13) NOT NULL,
                r_email varchar(100),
                pkg_weight float(5, 1),
                pkg_cost float(5, 1) DEFAULT '0.0',
                option_1 varchar(50) DEFAULT 'Regular',
                option_2 varchar(50) NOT NULL,
                remarks text,
                payment_type varchar(50),
                status varchar(50) DEFAULT 'Pending',
                PRIMARY KEY  (id),
                FOREIGN KEY  (user_id) REFERENCES wp_users(ID),
                FOREIGN KEY  (pickup_address) REFERENCES {$this->address_table_name()}(id),
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
        /**
         *      Same Day Delivery (Option 1) and User-inputted weight 
         *      depends on delivery options, sometimes they're disabled
         */
        $option_1 = '';
        $weight = '';
        if ( isset( $_POST['mm-same-day'] ) ) {

            $option_1 = $_POST['mm-same-day'];

        } else {

            $option_1 = 'Regular';

        }

        if ( isset( $_POST['pkg_weight']) ) {

            $weight = $_POST['pkg_weight'];

        } else {

            $weight = 0;

        }

        $trackingno = $this->generate_tracking_no();
        $user_id = get_current_user_id();

        $result = $wpdb->insert( $this->main_table_name(), array(
            'user_id' => $user_id,
            'date_submitted' => current_time('mysql'),
            'schedule_date' => $_POST['pickup_date'],
            'tracking_no' => $trackingno,
            'pickup_address' => $_POST['pickup_address'],
            'r_first_name' => $_POST['r_first_name'],
            'r_last_name' => $_POST['r_last_name'],
            'r_address' => $_POST['r_address'],
            'r_barangay' => $_POST['r_barangay'],
            'r_city' => $_POST['r_city'],
            'r_region' => $_POST['r_region'],
            'r_mobile_no' => $_POST['r_mobile_no'],
            'r_email' => $_POST['r_email'],
            'pkg_weight' => $weight,
            'pkg_cost' => $_POST['pkg_cost'],
            'option_1' => $option_1,
            'option_2' => $_POST['delivery_option'],
            'remarks' => $_POST['remarks'],
            'payment_type' => $_POST['payment']
        ), array(
            '%d',
            '%s',
            '%s',
            '%s',
            '%d',
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
            '%s',
            '%s',
            '%s',
            '%s'
        ));

        if ( $result === false ){ 

            $wpdb->print_error(); 

        } else {

            $points = get_user_meta( $user_id, 'dc_points', true );

            if( $points == '' ) {

                add_user_meta( $user_id, 'dc_points', 0, true );

            }

            $url = wp_nonce_url( home_url( 'success-page' ), 'post_sched' );
            $url .= '&tr=' . $trackingno;
            wp_redirect( $url );

        }

    }

    function insert_new_address() {

        if( !wp_verify_nonce( $_POST['_wpnonce'], 'new_address') ) {

            wp_die('Nonce could not be verified!');

        }

        global $wpdb;

        $wpdb->insert( $this->address_table_name(), array(
            'user_id' => get_current_user_id(),
            'address' => $_POST['address'],
            'barangay' => $_POST['barangay'],
            'city' => $_POST['city'],
            'region' => $_POST['region']
        ));

        wp_redirect( $_SERVER['HTTP_REFERER'] );

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

        $this->add_points( $_POST['user_id'], 1);

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

    function get_pickup_addresses() {

        global $wpdb;

        $id = get_current_user_id();

        $result = $wpdb->get_results( "SELECT * FROM {$this->address_table_name()} WHERE user_id={$id}",
                    ARRAY_A);

        return $result;
        wp_die();
    }

    function get_transaction_pickup_address($id) {

        global $wpdb;

        $result = $wpdb->get_row( "SELECT * FROM {$this->address_table_name()} WHERE id='{$id}'", ARRAY_A);

        if( $result === null ) {

            wp_die( "A database error occured when fetching the associated address" );

        }

        return $result;

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

    function generate_tracking_no() {

        do {

            $trackingno = '8350' . date( 's' );
            $suffix = mt_rand(0, 9999);
            $suffix = sprintf('%04d', $suffix);

            $trackingno .= $suffix;

        } while( $this->tracking_no_used( $trackingno ) );

        return $trackingno;

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
                                    OR user_id = " . $user_id . " AND meta_key = 'middle_initial'
                                    OR user_id = " . $user_id . " AND meta_key = 'mobile_number'",  ARRAY_A );

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

    function add_points( $id, $num ) {

        global $wpdb;

        $result = $wpdb->update( 'wp_usermeta',
                                array(
                                    'meta_value' => 'meta_value' + $num
                                ),
                                array(
                                    'user_id' => $id,
                                    'meta_key' => 'dc_points'
                                ),
                                '%d',
                                array(
                                    '%d',
                                    '%s'
                                ) );

        if( $result === false ) {

            wp_die( 'A database error occured while adding reward points ' );

        }

    }
}

