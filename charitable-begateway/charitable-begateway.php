<?php
/**
 * Plugin Name: BeGateway Payment Gateway for WP Charitable
 * Plugin URI: https://github.com/beGateway/charitable-begateway-payment-gateway
 * Description: BeGateway Payment Gateway module to accept donations by means of bank cards and alternative payment methods.
 * Author: eComCharge
 * Author URI: https://www.ecomcharge.com/
 * Version: 1.0.0
 * Requires PHP: 5.6
 * License: GPLv3
 * Domain Path: /includes/languages/
 * Text Domain: charitable-begateway
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * Load plugin class, but only if Charitable is found and activated.
 *
 */
function charitable_begateway_load() {
	require_once( 'includes/class-charitable-begateway.php' );

	$has_dependencies = true;

	/* Check for Charitable */
	if ( ! class_exists( 'Charitable' ) ) {

		if ( ! class_exists( 'Charitable_Extension_Activation' ) ) {

			require_once 'includes/activation.php';

		}

		$activation = new Charitable_Extension_Activation( plugin_dir_path( __FILE__ ), basename( __FILE__ ) );
		$activation = $activation->run();

		$has_dependencies = false;
	}
	else {

		new Charitable_BeGateway( __FILE__ );

	}
}

add_action( 'plugins_loaded', 'charitable_begateway_load', 1 );
