<?php
/**
 * @package GOQuoting2020
 * @version 2.0
 */
/*
Plugin Name: GOQuoting2020 by Go Galapagos
Plugin URI: https://www.gogalapagos.com
Description: Updated quoting plugin allows you to have a communication interface with Go Galapagos, in order to give your visitors the chance to quote a cruise for Galapagos Island with Go Galapagos Cruises
Author: @cattanich & The Go Galapagos Team
Version: 1.0
Author URI: www.digicatt.com.ec
*/

/**
 * VALIDACION por si accede directamente a los archivos del plugin
 */ 
if ( ! function_exists( 'add_filter' ) ) {
	header( 'Status: 403 Forbidden' );
	header( 'HTTP/1.1 403 Forbidden' );
	exit();
}

/**
 * Definiciones
 */ 
define('RUTA_PLUGIN_BOOKING', plugin_dir_url( __FILE__ ));
define('PATH_PLUGIN_BOOKING', plugin_dir_path( __FILE__ ));
define('META_PREFIX', 'gg_');

/**
 * Agregar archivos requeridos
 */ 
require_once('admin/goquoting-setup.php');
require_once('admin/admin-functions.php');
require_once('public/booking-system-create-pages.php');
require_once('public/booking-system-shortcodes.php');


// CcM Edit / carga de js 
function goquotingjs_init() {

wp_enqueue_script ( 'goquoting', RUTA_PLUGIN_BOOKING . 'public/scripts/goquoting.js', array ( 'jquery' ), '', true );

}
add_action('wp_enqueue_scripts','goquotingjs_init');

/*

(
    [muplugins_loaded] => 1
    [registered_taxonomy] => 15
    [registered_post_type] => 38
    [load_textdomain] => 18
    [wdev-register-plugin] => 1
    [plugins_loaded] => 1
    [qtranslate_loadConfig] => 1
    [wp_roles_init] => 2
    [auth_cookie_valid] => 2
    [set_current_user] => 1
    [qtranslate_load_front_admin] => 1
    [qtranslate_admin_loadConfig] => 1
    [qtranslate_init_language] => 1
    [sanitize_comment_cookies] => 1
    [setup_theme] => 1
    [unload_textdomain] => 1
    [after_setup_theme] => 1
    [init] => 1
    [widgets_init] => 1
    [qtranslate_widget_init] => 1
    [register_sidebar] => 3
    [wp_register_sidebar_widget] => 19
    [wp_default_scripts] => 1
    [wp_loaded] => 1
    [auth_redirect] => 1
    [_admin_menu] => 1
    [admin_menu] => 1
    [parse_tax_query] => 2
    [parse_query] => 1
    [pre_get_posts] => 1
    [posts_selection] => 1
    [admin_init] => 1
    [wp_default_styles] => 1
    [admin_bar_init] => 1
    [add_admin_bar_menus] => 1
    [update_option] => 4
    [update_option_rewrite_rules] => 2
    [updated_option] => 4
    [generate_rewrite_rules] => 1
    [current_screen] => 1
    [load-update-core.php] => 1
    [update_option__site_transient_update_plugins] => 2
    [update_site_option__site_transient_update_plugins] => 2
    [update_site_option] => 2
    [set_site_transient_update_plugins] => 2
    [setted_site_transient] => 2
    [requests-requests.before_request] => 1
    [requests-curl.before_request] => 1
    [http_api_curl] => 1
    [requests-curl.before_send] => 1
    [requests-request.progress] => 15
    [requests-curl.after_send] => 1
    [requests-curl.after_request] => 1
    [requests-requests.before_parse] => 1
    [requests-requests.before_redirect_check] => 1
    [requests-requests.after_request] => 1
    [http_api_debug] => 1
    [admin_xml_ns] => 2
    [admin_enqueue_scripts] => 1
    [wp_enqueue_media] => 1
    [admin_print_styles-update-core.php] => 1
    [admin_print_styles] => 1
    [admin_print_scripts-update-core.php] => 1
    [admin_print_scripts] => 1
    [wp_print_scripts] => 1
    [admin_head-update-core.php] => 1
    [admin_head] => 1
    [qtranslate_admin_css] => 1
    [qtranslate_css] => 1
    [adminmenu] => 1
    [in_admin_header] => 1
    [admin_bar_menu] => 1
    [wp_before_admin_bar_render] => 1
    [wp_after_admin_bar_render] => 1
    [admin_notices] => 1
    [qtranslate_admin_notices_plugin_conflicts] => 1
    [all_admin_notices] => 1
    [core_upgrade_preamble] => 1
    [in_admin_footer] => 1
    [admin_footer] => 1
    [pre-upload-ui] => 1
    [pre-plupload-upload-ui] => 1
    [post-plupload-upload-ui] => 1
    [post-upload-ui] => 1
    [print_media_templates] => 1
    [admin_print_footer_scripts-update-core.php] => 1
    [admin_print_footer_scripts] => 1
    [admin_footer-update-core.php] => 1
    [shutdown] => 1
)
*/