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

        add_filter('um_account_page_default_tabs_hook', array( $this, 'um_address_tab' ), 100 );
        add_action('um_account_tab__addresstab', array( $this, 'um_account_tab__addresstab' ) );
        add_filter('um_account_content_hook_addresstab', array( $this, 'um_account_content_hook_addresstab') );

        if( class_exists( 'DragonDB' ) ) {

            $db = DragonDB::getInstance();
            
        } else {
            echo "There was an error with Dragon Courier plugin. Please try reinstalling or contact the author.";
        }

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
        wp_enqueue_script( 'bootstrap-js', 'https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js', array(), null, false);
        wp_enqueue_script( 'sorttable', plugins_url( '/js/sorttable.js', __FILE__ ), array(), null, true );
        wp_enqueue_script( 'admin_custom_script', plugins_url( '/js/admin.js', __FILE__), array('jquery'), null, true );
        //localize
        wp_localize_script( 'admin_custom_script', 'ajax_object',
            array( 'ajax_url' => admin_url( 'admin-ajax.php' ) ) );

    }

    function enqueue_user_scripts() {
        //priority
        wp_enqueue_script( 'jquery', 'https://code.jquery.com/jquery-3.2.1.min.js');
        wp_enqueue_script( 'moment', plugins_url( '/bower_components/moment/min/moment.min.js', __FILE__ ), null, false );
        wp_enqueue_script( 'bootstrap-js', 'https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js', array(), null, false);
        wp_enqueue_script( 'bootstrap-datetimepicker-js', plugins_url( '/bower_components/eonasdan-bootstrap-datetimepicker/build/js/bootstrap-datetimepicker.min.js', __FILE__ ), null, false );
        //styles
        wp_enqueue_style( 'user_style', plugins_url( '/css/user-style.css', __FILE__ ) );
        wp_enqueue_style( 'bootstrap', 'https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css');
        wp_enqueue_style( 'bootstrap-datetimepicker', plugins_url( '/bower_components/eonasdan-bootstrap-datetimepicker/build/css/bootstrap-datetimepicker.min.css', __FILE__ ));        
        //scripts
        wp_enqueue_script( 'jquery_ui', 'https://code.jquery.com/ui/1.12.1/jquery-ui.min.js', array( 'jquery' ), true);
        wp_enqueue_script( 'cost_calculator', plugins_url( '/js/cost_calculator.js', __FILE__), array( 'jquery' ) );
        wp_enqueue_script( 'user_custom_script', plugins_url( '/js/user.js', __FILE__), array( 'jquery' ), true );

    }

    /**
     * Ultimate Member custom profile tab
     */
    function um_address_tab( $tabs ) {
        $tabs[800]['addresstab']['icon'] = 'um-faicon-building';
        $tabs[800]['addresstab']['title'] = 'Manage Addresses';
        $tabs[800]['addresstab']['custom'] = true;
        return $tabs;
    }
        
    /* make our new tab hookable */
    function um_account_tab__addresstab( $info ) {
        global $ultimatemember;
        extract( $info );

        $output = $ultimatemember->account->get_tab_output('addresstab');
        if ( $output ) { echo $output; }
    }

    /* Finally we add some content in the tab */
    function um_account_content_hook_addresstab( $output ){
        ob_start();
        
        include( ABSPATH . 'wp-content/plugins/dragon-courier/views/address_table.php');
        include( ABSPATH . 'wp-content/plugins/dragon-courier/views/new_address_modal.php');
        
        $output .= ob_get_contents();
        ob_end_clean();
        return $output;
    }

    function activate() {

        flush_rewrite_rules( );

    }

    function deactivate() {

        flush_rewrite_rules( );

    }

    private function register_admin_pages(){

        add_action( 'admin_menu', array( $this, 'add_admin_pages' ) );

    }

    private function register_short_codes(){

        add_shortcode( 'dragon-delivery-form', array( $this, 'delivery_form_sc' ) );
        add_shortcode( 'dragon-payments', array( $this, 'payments_sc') );

    }

    function delivery_form_sc(){

        include( plugin_dir_path( __FILE__ ) . "/views/user/delivery_form.php" );

    }

    function payments_sc(){

        include( plugin_dir_path( __FILE__ ) . "/views/user/payments.php" );

    }

    function add_admin_pages() {

        add_menu_page( 'Dragon Courier Panel', 'Shipments', 'manage_options', 'bde_shipping', array( $this, 'admin_index' ), '
        dashicons-book-alt', 110 );

    }

    function admin_index() {

        require_once plugin_dir_path( __FILE__ ) . 'views/admin/index.php';

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
