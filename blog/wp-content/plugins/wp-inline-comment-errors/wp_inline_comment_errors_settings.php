<?php
/**
 * This file contains the functions that create the settings page and functionality.
 * Currently the settings page gives a read only display of the plug-in configuration, PHP session configuration 
 * and WordPress option for requiring email and author field
 *
 * @package WordPress
 */


/**
 * register style sheet using admin_init action
 * @since 1.0
 */	 
add_action( 'admin_init', 'wpice_plugin_admin_init' );
function wpice_plugin_admin_init() {
	/* Register stylesheet. */
	wp_register_style( 'wpice-admin-stylesheet', plugins_url('css/wpice-admin.css', __FILE__) );
}


/**
 * action event to create menu 
 * menu information and call back to create options page
 * @since 1.0
 */	
add_action( 'admin_menu', 'wpice_plugin_menu' );
function wpice_plugin_menu() {
	$page = add_options_page(__('WP Inline Comment Errors Options','wp_inline_comment_errors'), __('WP Inline Comment Errors','wp_inline_comment_errors'), 'manage_options', 'wpice', 'wpice_plugin_options' );
	 /* Using registered $page handle to hook stylesheet loading */
	add_action( 'admin_print_styles-' . $page, 'wpice_plugin_admin_styles' );
}


/**
 * enque admin style sheet only for this plug-in admin page
 * @since 1.0
 */	
function wpice_plugin_admin_styles() {
	wp_enqueue_style( 'wpice-admin-stylesheet' );
}


/**
 * Add settings link on plugin page
 * @since 1.0
 */	
add_filter("plugin_action_links_wp-inline-comment-errors/wp_inline_comment_errors.php", 'wpice_plugin_settings_link' );

function wpice_plugin_settings_link($links) { 
	$settings_link = '<a href="options-general.php?page=wpice">Settings</a>'; 
	array_unshift($links, $settings_link); 
	return $links; 
}


/**
 * print the settings page, called from add_options_page action
 * @since 1.0
 */	
function wpice_plugin_options() {
	if ( !current_user_can( 'manage_options' ) )  {
		wp_die(__('You do not have sufficient permissions to access this page.','wp_inline_comment_errors'));
	}
	
	print '<div class="wrap">';
	
	print '<div id="icon-options-general" class="icon32"><br></div><h2>' . __('WP Inline Comment Error Settings','wp_inline_comment_errors') . '</h2>';
	
	/* word press requires name email */
	print wpice_get_wp_require_email_name();
	
	/* php session configuration */
	print wpice_get_php_session_support();
	
	/* plug-in config_options */
	print  wpice_get_configuration_list();
	
	/* plug-in custom_error_messages */
	print wpice_get_message_list();
	
	/* page footer */
	print wpice_admin_footer();
	
	print '</div><!-- end wrap -->';
}


/*
--------------------------------------------------
FUNCTIONS TO PRINT CONTENTS OF SETTINGS PAGE
--------------------------------------------------
*/

/**
 * build message explaining wordpress discussion settings for requiring name and email field in comment form 
 * @since 1.0
 */	
function wpice_get_wp_require_email_name(){
	global $wp_inline_comment_errors;
	
	$wp_require_name_email = get_option('require_name_email'); // display wordpress setting for name and email required
	if($wp_require_name_email == true){
		$config .= '<p>' . __('WordPress disucssion settings are set to require name and email for the comment form.','wp_inline_comment_errors') . '</p>';
	} else {
		$config .= '<p>' .  __('WordPress disucssion settings do not require name and email for the comment form.','wp_inline_comment_errors') . '</p>';
	}
	$msg = "<div class=\"wpice_config_msg_box\">
			<h3>" . __('WordPress Configuration','wp_inline_comment_errors') . "</h3>
			$config
			</div>";
	return $msg;
}


/**
 * build message explaining php support for passing session id in HTTP GET
 * make recommendation about using custom error template
 * @since 1.0
 */	
function wpice_get_php_session_support(){
	global $wp_inline_comment_errors;
	
	$use_only_cookies = ini_get('session.use_only_cookies'); // display PHP setting for session support for cookies and GET
	if($use_only_cookies == true){
		$config = '<p>' . __('The installation of PHP on your server is configured to use only cookies for sessions.  PHP will not support passing session ID in the URL query string as HTTP GET request. Therefore it is recommended that you create a custom comment form error template to display comment form errors, in case someone visits your site with cookies disabled.  Otherwise this plug-in will display comment form errors using the WordPress default error display.','wp_inline_comment_errors') . '</p>';
		
		ini_set('session.use_only_cookies','0'); // attempt to change php setting
		$new_val = ini_get('session.use_only_cookies'); // read value
		if($new_val == 0){
			$config .= '<p>' . __('The installation of PHP on your server can be reconfigured to support session ID in the URL query string as HTTP GET request by calling ini_set("session.use_only_cookies",0).','wp_inline_comment_errors') . '</p>';
			ini_set('session.use_only_cookies','1'); // restore setting
		}
		
	} else{
		$config = '<p>' . __('PHP is configured to use either cookies or passing session ID in the URL query string as HTTP GET request.  You do not need to create a custom comment form error template.','wp_inline_comment_errors') . '</p>';
	}
	$msg = "<div class=\"wpice_config_msg_box\">
			<h3>" . __('PHP Session Configuration','wp_inline_comment_errors') . "</h3>
			$config
			</div>";
	return $msg;
}


/**
 * get config_options array as list
 * @since 1.0
 */	
function wpice_get_configuration_list(){
	global $wp_inline_comment_errors;
	
	$my_config = apply_filters('wpice_get_config_options','');
	
	foreach($my_config as $option => $value){
		if($option == 'req_mark'){
			$content = $option . ' = ' . $value . ' (<code>' . htmlspecialchars($value) . '</code>)';
		} else {
			$content = $option . ' = ' . $value;
		}
		$items .= '<li>' . $content . '</li>';
	}
	$config = '<ul>' . $items . '</ul>';
	
	$msg = "<div class=\"wpice_config_msg_box\">
			<h3>" . __('Plug-in Configuration','wp_inline_comment_errors') . "</h3>
			$config
			</div>";
	return $msg;
}


/**
 * get custom_error_messages array as a list
 * @since 1.0
 */
function wpice_get_message_list(){
	global $wp_inline_comment_errors;
	
	$items = '';
	$my_messages = apply_filters('wpice_get_defined_messages','');
	foreach($my_messages as $option => $value){
		$content = $option . ': "' . $value . '"';
		$items .= '<li>' . $content . '</li>';
	}
	$config = '<ul>' . $items . '</ul>';
	$config .= '<p>' . __('Note that these messages may be overridden by the messages you provide in your own validation functions.','wp_inline_comment_errors') . '</p>';
	
	$msg = "<div class=\"wpice_config_msg_box\">
			<h3>" . __('Defined Error Messages','wp_inline_comment_errors') ."</h3>
			$config
			</div>";
	return $msg;
}

/**
 * print page footer
 * @since 1.0
 */
function wpice_admin_footer(){
	global $wp_inline_comment_errors;
	
	return '<div class="wpice-footer">' . __('WP Inline Errors Plug-in developed by Hayden Porter,','wp_inline_comment_errors') . ' <a href="http://www.aviarts.com">www.aviarts.com</a></div>';
}
?>