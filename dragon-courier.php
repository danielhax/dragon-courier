<?php 
/*
* @package DragonCourier
*/

/*
Plugin Name: Dragon Courier Plugin
Plugin URI: http://github.com/danielhax/dragon-courier
Description: Custom plugin for shipping company needs
Version: 0.1
Author: Daniel Viernes
Author URI: http://github.com/danielhax
License: GPL
Text Domain: dragon-courier-plugin
*/

/*
This program is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 3 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program.  If not, see <http://www.gnu.org/licenses/>.
*/

defined('ABSPATH') or die('You don\t have permission to access this file!');

include plugin_dir_path( __FILE__ ) . '/database.php';

class DragonCourier {

    function __construct() {

        $this->register_admin_pages();

        $this->register_scripts();

        $this->register_short_codes();

        if( class_exists( 'DragonDB' ) ) {

            $db = DragonDB::getInstance();
            
        } else {
            echo "There was an error with Dragon Courier plugin. Please try reinstalling or contact the author.";
        }

        add_action( 'admin_post_schedule_delivery', array( $db , 'insert_schedule_request' ) );

        //add_action( 'admin_post_nopriv_schedule_delivery', array( $db , 'insert_schedule_request' ) );

        //add_action( 'init', array( $this, 'register_session') );

    }

    private function register_scripts() {

        add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_admin_scripts' ) );
        add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_user_scripts' ) );

    }

    function enqueue_admin_scripts() {

        //priority
        wp_enqueue_script( 'jquery', 'https://code.jquery.com/jquery-3.2.1.min.js');
        //styles
        wp_enqueue_style( 'bootstrap', 'https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css');
        wp_enqueue_style( 'admin_style', plugins_url( '/css/admin-style.css', __FILE__ ) );
        //scripts
        wp_enqueue_script( 'bootstrap', 'https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js');
        wp_enqueue_script( 'sorttable', plugins_url( '/js/sorttable.js', __FILE__ ) );
        wp_enqueue_script( 'admin_custom_script', plugins_url( '/js/admin.js', __FILE__) );

    }

    function enqueue_user_scripts() {

        wp_enqueue_style( 'user_style', plugins_url( '/css/user-style.css', __FILE__ ) );

    }

    function activate() {

        flush_rewrite_rules( );

    }

    function deactivate() {

        flush_rewrite_rules( );

    }

    // function custom_post_type() {
    //     register_post_type( 'shipping', ['public' => true, 'label' => 'Shipping'] );
    // }

    private function register_admin_pages(){

        add_action( 'admin_menu', array( $this, 'add_admin_pages' ) );

    }

    private function register_short_codes(){

        add_shortcode( 'dragon-delivery-form', array( $this, 'delivery_form_sc' ) );

    }

    function delivery_form_sc(){

        include(plugin_dir_path( __FILE__ ) . "/views/user/delivery_form.php");

    }

    function add_admin_pages() {

        add_menu_page( 'Dragon Courier Panel', 'Shipments', 'manage_options', 'bde_shipping', array( $this, 'admin_index' ), '
        dashicons-book-alt', 110 );

    }

    function admin_index() {

        require_once plugin_dir_path( __FILE__ ) . 'views/admin/index.php';

    }

    function register_session() {

        if( !session_id() ) {
            session_start();
        }

    }

}

if( class_exists( 'DragonCourier' ) ) {

    $dc = new DragonCourier();

} else {
    echo "There was an error with Dragon Courier plugin. Please try reinstalling or contact the author.";
}

// activation
register_activation_hook( __FILE__, array( $dc, 'activate' ) );

//deactivate
register_deactivation_hook( __FILE__ , array( $dc, 'deactivate') );
