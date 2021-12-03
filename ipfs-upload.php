<?php

/**
 * IPFS Upload
 *
 * @link              https://oberonlai.blog
 * @since             1.0.0
 * @package           ipup
 *
 * @wordpress-plugin
 * Plugin Name:       IPFS Upload
 * Plugin URI:        https://oberonlai.blog
 * Description:       Upload images to InterPlanetary File System
 * Version:           1.0.0
 * Author:            Oberon Lai
 * Author URI:        https://oberonlai.blog
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       ipup
 * Domain Path:       /languages
 *
 * WC requires at least: 5.0
 * WC tested up to: 5.7.1
 */

defined( 'ABSPATH' ) || exit;

define( 'IPUP_VERSION', '1.0.0' );
define( 'IPUP_PLUGIN_URL', plugin_dir_url( __FILE__ ) );
define( 'IPUP_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
define( 'IPUP_PLUGIN_BASENAME', plugin_basename( __FILE__ ) );

/**
 * Autoload
 */
require_once IPUP_PLUGIN_DIR . 'vendor/autoload.php';
\A7\autoload( IPUP_PLUGIN_DIR . 'src' );
