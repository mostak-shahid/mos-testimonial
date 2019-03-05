<?php
/*
Plugin Name: Mos Testimonial
Description: Base of future plugin
Version: 0.0.2
Author: Md. Mostak Shahid
*/


if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

// Define MOS_TESTIMONIAL_FILE.
if ( ! defined( 'MOS_TESTIMONIAL_FILE' ) ) {
	define( 'MOS_TESTIMONIAL_FILE', __FILE__ );
}
// Define MOS_TESTIMONIAL_SETTINGS.
if ( ! defined( 'MOS_TESTIMONIAL_SETTINGS' ) ) {
  define( 'MOS_TESTIMONIAL_SETTINGS', admin_url('/edit.php?post_type=testimonial&page=mos_testimonial_settings') );
	//define( 'MOS_TESTIMONIAL_SETTINGS', admin_url('/options-general.php?page=mos_testimonial_settings') );
}
$mos_testimonial_options = get_option( 'mos_testimonial_options' );
$plugin = plugin_basename(MOS_TESTIMONIAL_FILE); 
require_once ( plugin_dir_path( MOS_TESTIMONIAL_FILE ) . 'mos-testimonial-functions.php' );
require_once ( plugin_dir_path( MOS_TESTIMONIAL_FILE ) . 'mos-testimonial-settings.php' );
require_once ( plugin_dir_path( MOS_TESTIMONIAL_FILE ) . 'mos-testimonial-post-types.php' );
require_once ( plugin_dir_path( MOS_TESTIMONIAL_FILE ) . 'mos-testimonial-taxonomy.php' );

require_once( plugin_dir_path( MOS_TESTIMONIAL_FILE ) . 'plugins/metabox/init.php');
require_once( plugin_dir_path( MOS_TESTIMONIAL_FILE ) . 'mos-testimonial-metaboxes.php');

require_once('plugins/update/plugin-update-checker.php');
$pluginInit = Puc_v4_Factory::buildUpdateChecker(
	'https://raw.githubusercontent.com/mostak-shahid/update/master/mos-testimonial.json',
	MOS_TESTIMONIAL_FILE,
	'mos-testimonial'
);


register_activation_hook(MOS_TESTIMONIAL_FILE, 'mos_testimonial_activate');
add_action('admin_init', 'mos_testimonial_redirect');
 
function mos_testimonial_activate() {
    $mos_testimonial_options = array();
    $mos_testimonial_options['custom-template-1'] = array("disable,1", "disable,2", "disable,3", "disable,4", "disable,5", "disable,6", "disable,7" );
    $mos_testimonial_options['custom-template-2'] = array("disable,1", "disable,2", "disable,3", "disable,4", "disable,5", "disable,6", "disable,7" );
    update_option( 'mos_testimonial_options', $mos_testimonial_options, false );
    add_option('mos_testimonial_do_activation_redirect', true);
}
 
function mos_testimonial_redirect() {
    if (get_option('mos_testimonial_do_activation_redirect', false)) {
        delete_option('mos_testimonial_do_activation_redirect');
        if(!isset($_GET['activate-multi'])){
            wp_safe_redirect(MOS_TESTIMONIAL_SETTINGS);
        }
    }
}

// Add settings link on plugin page
function mos_testimonial_settings_link($links) { 
  $settings_link = '<a href="'.MOS_TESTIMONIAL_SETTINGS.'">Settings</a>'; 
  array_unshift($links, $settings_link); 
  return $links; 
} 
add_filter("plugin_action_links_$plugin", 'mos_testimonial_settings_link' );



