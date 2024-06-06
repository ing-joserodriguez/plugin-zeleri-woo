<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://https://zeleri.com/
 * @since             1.0.0
 * @package           Zeleri
 *
 * @wordpress-plugin
 * Plugin Name:       Zeleri
 * Plugin URI:        https://https://zeleri.com/
 * Description:       Description plugin zeleri
 * Version:           1.0.0
 * Author:            Zeleri
 * Author URI:        https://https://zeleri.com//
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       zeleri
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Currently plugin version.
 * Start at version 1.0.0 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
define( 'ZELERI_VERSION', '1.0.0' );

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-zeleri-activator.php
 */
function activate_zeleri() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-zeleri-activator.php';
	Zeleri_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-zeleri-deactivator.php
 */
function deactivate_zeleri() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-zeleri-deactivator.php';
	Zeleri_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_zeleri' );
register_deactivation_hook( __FILE__, 'deactivate_zeleri' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-zeleri.php';


$woocommerce_is_present = false;

$all_plugins = apply_filters('active_plugins', get_option('active_plugins'));
if (stripos(implode($all_plugins), 'woocommerce.php')) {
    $woocommerce_is_present = true;
}

/*
 * Check if WooCommerce is active
 */
if ( $woocommerce_is_present ) {
	// En tu-plugin.php
	require_once plugin_dir_path(__FILE__) . 'includes/class-zeleri-woo-oficial-payment-gateways.php';
	function add_zeleri_woo_oficial_payment_gateways( $gateways ) {
        $gateways[] = 'Zeleri_Woo_Oficial_Payment_Gateways';
        return $gateways;
    }
 
    add_filter( 'woocommerce_payment_gateways', 'add_zeleri_woo_oficial_payment_gateways' );
}

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_zeleri() {

	$plugin = new Zeleri();
	$plugin->run();

}
run_zeleri();
