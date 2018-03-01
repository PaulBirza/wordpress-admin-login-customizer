<?php
/**
 * @package WP_Customize
 * @version 1.0.8
 */

/*
Plugin Name: Wordpress Admin Customizer
Description: This plugin gives you the power to customize the WordPress login page.
Author: Paul Birza
Version: 1.0.8
Author URI: http://http://paul-stephan-birza.com/
License: GPL2
*/

/*

This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License, version 2, as
published by the Free Software Foundation.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

if ( ! defined( 'ABSPATH' ) ) {
	die();
}

if ( version_compare( $GLOBALS['wp_version'], '3.5', '<' ) ) {
	return;
}

/**
 * ************************************************************
 *                WP ADMIN - INIT/UNINIT PLUGIN
 * ************************************************************
 */

require_once( plugin_dir_path( __FILE__ ) . 'plugin-init.php' );

/* Runs when plugin is activated */
register_activation_hook(__FILE__,'wp_customize_install');

/* Runs on plugin deactivation*/
register_deactivation_hook( __FILE__, 'wp_customize_remove' );

/**
 * ************************************************************
 *            WP ADMIN - CREATE LOGIN PAGE TEMPLATE
 * ************************************************************
 */

require_once( plugin_dir_path( __FILE__ ) . 'page-template.php' );

/**
 * ************************************************************
 *                 WP ADMIN - SCRIPTS AND STYLES
 * ************************************************************
 */

// enqueue javascript for admin pages
function wpcustomize_admin_scripts() {
	wp_enqueue_script( 'jquery' );
	wp_enqueue_script('media-upload');
	wp_enqueue_script('thickbox');
	wp_enqueue_style('thickbox');
	wp_enqueue_script( 'wp-customize-ace-js', plugin_dir_url(__FILE__) . 'js/ace/src-min-noconflict/ace.js', array( 'jquery' ), '1.0', true );
	wp_enqueue_script( 'wp-customize-chosen-js', plugin_dir_url(__FILE__) . 'js/chosen/chosen.jquery.min.js', array( 'jquery' ), '1.4.2', true );
	wp_enqueue_style( 'wp-customize-spectrum', plugin_dir_url(__FILE__) . 'js/spectrum.min.css', null, '1.0.8' );
	wp_enqueue_style( 'wp-customize-chosen', plugin_dir_url(__FILE__) . 'js/chosen/chosen.min.css', null, '1.4.2' );
	wp_enqueue_script( 'wp-customize-spectrum-js', plugin_dir_url(__FILE__) . 'js/spectrum.min.js', array( 'jquery' ), '1.0.8', true );
	wp_enqueue_script( 'wp-customize-js', plugin_dir_url(__FILE__) . 'js/script.min.js', array(
		'wp-customize-chosen-js',
		'wp-customize-ace-js',
		'wp-customize-spectrum-js',
		'jquery',
		'media-upload',
		'thickbox'
	), '1.0.8', true );
}
add_action( 'admin_enqueue_scripts', 'wpcustomize_admin_scripts' );

/**
 * Load media files needed for Uploader
 */
function load_wp_media_files() {
	wp_enqueue_media();
}
add_action( 'admin_enqueue_scripts', 'load_wp_media_files' );

/**
 * ************************************************************
 *                    WP ADMIN - SETTINGS PAGE
 * ************************************************************
 */

require_once( plugin_dir_path( __FILE__ ) . 'settings-page.php' );

/**
 * ************************************************************
 *                          WP ADMIN
 * ************************************************************
 */

/**
 * Add custom CSS to the admin document head
 */
function wpcustomize_admin_styles() {
	wp_enqueue_style( 'wp-customize-admin', plugin_dir_url(__FILE__) . 'css/admin.min.css', null, '1.0.8' );
}
add_action('admin_head', 'wpcustomize_admin_styles');

/**
 * ************************************************************
 *                       ADMIN LOGIN
 * ************************************************************
 */

/**
 * Add a custom logo to the WordPress Admin login page header
 */
function wpcustomize_custom_login_logo() {
	echo '<style type="text/css">';
	if( get_option('wpcustomize_admin_logo') ) {
		echo '#login h1 a {
			background-image:url(' . html_entity_decode(get_option('wpcustomize_admin_logo')) . ') !important;
			background-size: ' . html_entity_decode(get_option('wpcustomize_admin_logo_width')) . 'px ' . html_entity_decode(get_option('wpcustomize_admin_logo_height')) . 'px !important;
			height: ' . html_entity_decode(get_option('wpcustomize_admin_logo_area_height')) . 'px !important;
			width: ' . html_entity_decode(get_option('wpcustomize_admin_logo_area_width')) . 'px !important;
		}';
	}
	if( get_option('wpcustomize_admin_bgcolor', '#000') ) {
		echo 'body { background-color:' . html_entity_decode(get_option('wpcustomize_admin_bgcolor', '#000')) . ' !important; }';
	}
	if( get_option('wpcustomize_admin_linkcolor', '#fff') ) {
		echo '#login #nav a, #login #backtoblog a { color:' . html_entity_decode(get_option('wpcustomize_admin_linkcolor', '#fff')) . ' !important; text-shadow: none !important; }';
	}
	if( get_option('wpcustomize_admin_linkhovercolor', '#cfcfcf') ) {
		echo '#login #nav a:hover, #login #backtoblog a:hover { color: ' . html_entity_decode(get_option('wpcustomize_admin_linkhovercolor', '#cfcfcf')) . ' !important; text-shadow: none !important; }';
	}
	if( get_option('wpcustomize_admin_login_background_url') ) {
		echo 'body {
			background-image: url(' . html_entity_decode(get_option('wpcustomize_admin_login_background_url')) . ') !important;
			background-repeat: ' . html_entity_decode(get_option('wpcustomize_admin_login_background_repeat')) . ';
			background-position: ' . html_entity_decode(get_option('wpcustomize_admin_login_background_position')) . ';
			background-attachment: ' . html_entity_decode(get_option('wpcustomize_admin_login_background_attachment')) . ';
			background-size: ' . html_entity_decode(get_option('wpcustomize_admin_login_background_size')) . ';
		}';
	}
	echo '</style>';
}
add_action('login_head', 'wpcustomize_custom_login_logo');

/**
 * Add custom custom CSS styles to the Wordpress Admin login page header
 */
if( get_option('wpcustomize_admin_loginstyles') ) {
	function wpcustomize_custom_login_styles() {
		?><style type="text/css">
			<?php echo get_option('wpcustomize_admin_loginstyles'); ?>
		</style><?php
	}
	add_action('login_head', 'wpcustomize_custom_login_styles');
}

/**
 * Hide the register and forgot password links from the login form
 */
function wpcustomize_hide_register_forgot_links() {
	if( get_option('wpcustomize_hide_register_forgot_links') ) {
		echo '<style type="text/css">
			p#nav {
				display: none;
			}
		</style>';
	}
}
add_action('login_head', 'wpcustomize_hide_register_forgot_links');

/**
 * Hide the Back to Blog link from the login form
 */
function wpcustomize_hide_back_link() {
	if( get_option('wpcustomize_hide_back_link') ) {
		echo '<style type="text/css">
			p#backtoblog {
				display: none;
			}
		</style>';
	}
}
add_action('login_head', 'wpcustomize_hide_back_link');

/**
 * Check the "Remember me" checkbox by default
 */
if( get_option('wpcustomize_remember_me_by_default') ) {
	function wpcustomize_login_checked_remember_me() {
		add_filter( 'login_footer', 'rememberme_checked' );
	}
	add_action( 'init', 'wpcustomize_login_checked_remember_me' );

	function rememberme_checked() {
		echo "<script>document.getElementById('rememberme').checked = true;</script>";
	}
}

/**
 * Remove the login shake from the login form
 */
if( get_option('wpcustomize_remove_login_shake') ) {
	function wpcustomize_remove_login_shake() {
		remove_action('login_head', 'wp_shake_js', 12);
	}
	add_action('login_head', 'wpcustomize_remove_login_shake');
}

/**
 * Change default error message
 */
function wpcustomize_custom_error_message() {
	return ( get_option('wpcustomize_custom_error_message') ? html_entity_decode(get_option('wpcustomize_custom_error_message')) : 'Incorrect login details. Please try again.' );
}
add_filter('login_errors', 'wpcustomize_custom_error_message');

/**
 * Filter the URL of the header logo on the WordPress login page
 */
function wpcustomize_custom_login_url() {
	if( get_option('wpcustomize_admin_logo_link_url') ) {
		return get_option('wpcustomize_admin_logo_link_url');
	} else {
		return site_url();
	}
}
add_filter('login_headerurl', 'wpcustomize_custom_login_url');

/**
 * Filter the title attribute of the header logo on the WordPress login page
 */
function wpcustomize_login_header_title() {
	if( get_option('wpcustomize_admin_logo_title') ) {
		return get_option('wpcustomize_admin_logo_title');
	} else {
		return get_bloginfo('name');
	}
}
add_filter('login_headertitle', 'wpcustomize_login_header_title');

/**
 * Set a custom logo for the default login page
 */
if( get_option('wpcustomize_admin_logo_image_url') ) {
	function wpcustomize_login_logo() {
		echo '<style type="text/css">
			.login h1 a {
				background-image: url(' . html_entity_decode(get_option('wpcustomize_admin_logo_image_url')) . ');
				background-size: ' . html_entity_decode(get_option('wpcustomize_admin_logo_width')) . 'px ' . html_entity_decode(get_option('wpcustomize_admin_logo_height')) . 'px !important;
				height: ' . html_entity_decode(get_option('wpcustomize_admin_logo_area_height')) . 'px !important;
				width: ' . html_entity_decode(get_option('wpcustomize_admin_logo_area_width')) . 'px !important;
			}
		</style>';
	}
	add_action( 'login_enqueue_scripts', 'wpcustomize_login_logo' );
}

/**
 * Redirect user after successful login
 */
function wpcustomize_login_redirect( $redirect_to, $request, $user ) {
	//is there a user to check?
	global $user;
	if ( isset( $user->roles ) && is_array( $user->roles ) ) {
		if( get_option('wpcustomize_admin_login_redirect') == "1" ) {
			if( get_option('wpcustomize_admin_login_redirect_url') ) {
				return get_option('wpcustomize_admin_login_redirect_url');
			} else {
				return site_url();
			}
		} else {
			return $redirect_to;
		}
	} else {
		return $redirect_to;
	}
}
add_filter( 'login_redirect', 'wpcustomize_login_redirect', 10, 3 );

/**
 * Redirect visits to wp-login.php to our custom login page template
 */
function wpcustomize_login(){
	global $pagenow;
	if (
		( 'wp-login.php' == $pagenow )
		&& $_SERVER['REQUEST_METHOD'] != 'POST'
		&& $_GET['action'] != 'register'
		&& $_GET['action'] != 'lostpassword'
		&& ( !is_user_logged_in() )
	) {
		$redirect_url = home_url( '/login/' );
		wp_redirect( $redirect_url );
		exit();
	}
}
add_action('init','wpcustomize_login');

/**
 * Empty login credentials
 */
function wpcustomize_verify_username_password( $user, $username, $password ) {
	$login_page  = home_url( '/login/' );
	if( $username == "" || $password == "" ) {
		wp_redirect( $login_page . "?login_error" );
		exit;
	}
}
add_filter( 'authenticate', 'wpcustomize_verify_username_password', 1, 3);

/**
 * Incorrect login credentials
 */
function wpcustomize_login_failed( $username ) {
	//redirect to custom login page and append login error flag
	$login_page  = home_url( '/login/' );
	wp_redirect( $login_page . '?login_error' );
	exit;
}
add_action( 'wp_login_failed', 'wpcustomize_login_failed' );

/**
 * ************************************************************
 *                       ADMIN FOOTER
 * ************************************************************
 */

/**
 * Set a new footer in the WordPress Admin
 */
function wpcustomize_remove_footer_admin () {
	$wpcustomize_footer_default_value = 'Thank you for creating with <a href="http://wordpress.org/">WordPress</a>.';
	if(get_option('wpcustomize_admin_footer_contents') == "") {
		echo $wpcustomize_footer_default_value;
	} else {
		echo html_entity_decode(get_option('wpcustomize_admin_footer_contents', htmlentities($wpcustomize_footer_default_value)));
	}
}
add_filter('admin_footer_text', 'wpcustomize_remove_footer_admin');
