<?php

/*
* Uninstall BDE Shipping
*
* @package BDEShipping
*/

defined( 'WP_UNINSTALL_PLUGIN' ) or die( 'You don\t ave permission to access this file!' );

// Clear Database stored data
$shippings = get_posts( array( 'post_type' => 'shipping', 'numberposts' => -1 ) );

foreach( $shippings as $shipping ) {
    wp_delete_post( $shippings->ID, true);
}