<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://www.sanil.com.np
 * @since             1.0.0
 * @package           Feed_Override
 *
 * @wordpress-plugin
 * Plugin Name:       Feed Override
 * Plugin URI:        #
 * Description:       This is a short description of what the plugin does. It's displayed in the WordPress admin area.
 * Version:           1.0.0
 * Author:            Sanil Shakya
 * Author URI:        https://www.sanil.com.np
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       feed-override
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
define( 'FEED_OVERRIDE_VERSION', '1.0.0' );

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-feed-override-activator.php
 */
function activate_feed_override() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-feed-override-activator.php';
	Feed_Override_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-feed-override-deactivator.php
 */
function deactivate_feed_override() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-feed-override-deactivator.php';
	Feed_Override_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_feed_override' );
register_deactivation_hook( __FILE__, 'deactivate_feed_override' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-feed-override.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_feed_override() {

	$plugin = new Feed_Override();
	$plugin->run();

}
run_feed_override();




// custom code start here
// add_filter( 'the_author', 'feed_author' );
// function feed_author($article_authors) {
//     if( is_feed() ) {
// 		// $authors = get_field('author', get_the_ID() );
//     //    $author = get_the_terms( get_the_ID(), 'article_author' );
//     //    if ( $author && ! is_wp_error( $author ) ) {  
//     //        $multiple_authors = array();
//     //        foreach ( $author as $author ) {
//     //        $multiple_authors[] = $author->name;
//     //        }   
//     //        $article_authors = join( " & ", $multiple_authors );   
// 	//    }    
	
// 		// $multiple_authors = array();
// 		// foreach( $authors as $author ){
//         //    $multiple_authors[] = $author['display_name'];
// 		// }
		
// 		// $article_authors = join( ", ", $multiple_authors );   
		   
// 		return $article_authors;
//     }
// }