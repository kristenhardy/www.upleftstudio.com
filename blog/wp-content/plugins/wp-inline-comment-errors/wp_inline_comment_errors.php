<?php
/*
Plugin Name: WP Inline Comment Errors
Plugin URI: http://www.aviarts.com
Description: Enables WordPress to display comment form field errors directly in the template that displays the comment form. Highly customizable. Purely PHP solution that does not rely on ajax or redirecting the browser to a error message page template.  Add your own validation functions for author, email, url, comment and custom meta comment form fields.
Version: 1.1
Author: Hayden Porter, aviarts.com
Author URI: http://www.aviarts.com
License: GPL2
*/

/*
Copyright 2013  Hayden Porter  (contact : www.aviarts.com)

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

/**
 * This file instantiates the plug-in, configures the plug-in, loads the file that manages the settings page.
 *
 * @package WordPress
 */

include_once("wp_inline_comment_errors.class.php"); // load class

if(class_exists('WP_Inline_Comment_Errors')){

	// instantiate the plugin class, use this variable throughout
	$wp_inline_comment_errors = new WP_Inline_Comment_Errors();
	
    // set up localization
    if(isset($wp_inline_comment_errors)){		
		/*
		arguments for set_config_options
		plug-in code overrides class values for this array
			enable $_POST back to form, 
			hide the wordpress required mark,
			show wpice required mark,
			display the error list in comment_form_top
		*/
		$opts = array(	'show_post_data'=>true, // $_POST back to form
						'req_mark_location'=>'after-label', // position required mark after the form element label text
						'hide_wp_req_mark'=>true, // remove the wordpress generated required mark
						'auto_display_errors' => 'comment_form_top', // automatically print list of errors in 'comment-form-top' location
						'error_template_path' => 'wpice-comment-error-template.php', // default name for the error template 
					);
					
		// use do_action to call methods and avoid errors after uninstalling or deactivating the plug-in
		do_action('wpice_set_config_options',$opts); // set the config_options array
		
		include_once("wp_inline_comment_errors_settings.php"); // load settings page
    }
}
?>