<?php
/*
* @package DragonCourier
*/

class DragonDB {

    function __construct() {

        $this->create_db();

    }

    private function get_table_name(){
        global $wpdb;
        return $wpdb->prefix . 'dragon_courier';
    }

    private function db_exists() {

        global $wpdb;
        return $wpdb->get_var("SHOW TABLES LIKE '{$this->get_table_name()}'") == $this->get_table_name();

    }

    function create_db() {
        
        if(!$this->db_exists()) {

            global $wpdb;

            $charset_collate = $wpdb->get_charset_collate();

            $sql = "CREATE TABLE {$this->get_table_name()} (
                id bigint(20) NOT NULL AUTO_INCREMENT,
                user_id bigint(20) NOT NULL,
                pickup_date datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
                dispatch_date datetime DEFAULT '0000-00-00 00:00:00',
                complete_date datetime DEFAULT '0000-00-00 00:00:00',
                tracking_no varchar(12),
                first_name varchar(100) NOT NULL,
                last_name varchar(100) NOT NULL,
                delivery_address varchar(255) NOT NULL,
                delivery_city varchar(100) NOT NULL,
                delivery_postal varchar(5) NOT NULL,
                mobile_no varchar(13) NOT NULL,
                email varchar(100),
                pkg_weight float(5, 1) NOT NULL,
                pkg_length float(5, 1) NOT NULL,
                pkg_width float(5, 1) NOT NULL,
                pkg_height float(5, 1) NOT NULL,
                delivery_cost float(5, 1) DEFAULT '0.0',
                status varchar(50) DEFAULT 'Pending',
                PRIMARY KEY  (id),
                FOREIGN KEY  (user_id) REFERENCES wp_users(ID),
                UNIQUE (tracking_no)
            ) $charset_collate;";

            require_once( ABSPATH . '/wp-admin/includes/upgrade.php' );
            dbDelta( $sql );

        }

    }
}