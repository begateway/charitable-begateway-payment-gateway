<?php
/**
 * Charitable BeGateway Gateway Hooks.
 *
 * Action/filter hooks used for handling payments through the BeGateway gateway.
 *
 * @package     Charitable BeGateway/Hooks/Gateway
 * @version     1.0.0
 * @author      eComCharge
 * @copyright   Copyright (c) 2020, eComCharge LLC
 * @license     http://opensource.org/licenses/gpl-2.0.php GNU Public License
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/*
* Render the BeGateway donation processing page content.
*
* This is the page that users are redirected to after filling out the donation form.
*
* @see Charitable_Gateway_BeGateway::process_donation()
*/
add_filter('charitable_processing_donation_begateway', array('Charitable_Gateway_BeGateway', 'process_donation'), 10, 2 );
add_filter('charitable_process_donation_begateway', array('Charitable_Gateway_BeGateway', 'redirect_to_processing'), 10, 2 );

add_filter('charitable_donation_form_user_fields', array('Charitable_Gateway_BeGateway', 'remove_unrequired_fields'));
add_filter('charitable_settings_tab_fields_general', array('Charitable_Gateway_BeGateway', 'add_fields'), 6);
add_action('wp_enqueue_scripts', array('Charitable_Gateway_BeGateway', 'charitable_enqueue_begateway_widget_script'));
add_action('charitable_process_ipn_begateway', array( 'Charitable_Gateway_BeGateway', 'process_ipn'));
