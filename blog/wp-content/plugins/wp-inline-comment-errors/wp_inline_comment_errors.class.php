<?php

/**
 * class for WordPress inline comment errors
 * Version: 1.1
 * @package WordPress
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

if(!class_exists('WP_Inline_Comment_Errors')){
	class WP_Inline_Comment_Errors {
	
		// private properties
		private $error_code = 'comment_err'; // error code for wp_error to identify errors coming from comment form
		private $session_form_data = 'wpice_comment_form_data'; // name of session variable to store comment form $_POST data
		private $session_wp_error = 'wpice_wp_error'; // name of session variable to store wp_error
		
		// public properties, might be useful to other plug-in developers
		public $text_domain = 'wp_inline_comment_errors'; // for localization
		public $comment_err; // assigned to a WP_Error in _construct
		public $comment_post_id; // holds the id of the post for the comment, not set until comment form submitted
		public $pre_comment_on_post_priority = 1; // priority of filter
		
		// user configurable properties
		/*
		array of parameters to configure plug-in behavior
		user can set with 'set' action and get the array with 'get' filter
		*/
		public $config_options = array(	
		
		/* 
		FORM RELATED PROPERTIES ==================================================================================
		set conditions for displaying $_POST data and required mark in author, email, url and comments fields
		functions executed from comment_form_field_{fieldname} use these conditions to add or remove values from these fields
		default to display no post data, show wordpress required mark and do not show wpice required mark
		note that wordpress required mark only works for WordPress generated form, custom HTML form may not parse the same as the 
		wordpress generated form elements
		*/									
										/* 
										'show_post_data' => true | false 
										true - displays $_POST data in form fields after submission contains errors
										false - do not automatically display $_POST data
										*/
										'show_post_data' => false,
										
										/*
										'req_mark_location' => 'after-field' | 'after-label' | 'none'
										after-field - display required mark after field
										after-label - display required mark after label tag text
										none - do not display the required mark 
										*/
										'req_mark_location' => 'none',
										
										/*
										'req_mark' => '<span class="comment-form-required">*</span>'
										html for required mark
										*/
										'req_mark' => '<span class="comment-form-required">*</span>',
										
										/* 
										'hide_wp_req_mark' => true | false
										true - removes wordpress generated required mark from author and email field html
										false - does not remove required mark
										*/
										'hide_wp_req_mark' => false,
										
										/*
										'auto_display_errors' => null
										automatically display the comment error list using the specified comment form action, 
										such as comment_form_top
										set to null in class and 'comment_form_top' in plug-in
										*/
										'auto_display_errors' => null,
										
		/* 
		OTHER PROPERTIES ==========================================================
		*/
										
										/*
										name of URL fragment identifier and anchor tag for scroll down to form feature,
										only used when plug-in configured to automatically display errors
										*/
										'anchor_fragment_name' => 'goto_error_message',
										
										/*
										path to comment error template to display errors if cookies disabled and session get not supported
										*/
										'error_template_path' => null,
								);
		/*
		associative array that stores default error messages, index is name of field and value is error message
		*/
		public $default_error_msgs = array();
		
		/*
		associative array that stores error messages, index is name of field and value is error message
		*/
		public $custom_error_msgs = array();
		
		// names of custom filters
		// DOCUMENT EACH FILTER
		public $author_validation_filter = 'wpice_validate_commentform_author'; // wpdb/$_POST field is 'author', WordPress label is 'name'
		public $email_validation_filter = 'wpice_validate_commentform_email';
		public $url_validation_filter = 'wpice_validate_commentform_url'; // wpdb/$_POST field is 'url', WordPress label is 'website'
		public $comment_validation_filter = 'wpice_validate_commentform_comment';
		public $comment_duplicate_filter = 'wpice_comment_duplicate_trigger_filter';
		public $metafield_validation_filter = 'wpice_validate_commentform_metafield'; 
		public $get_redirect_url = 'wpice_get_redirect_url'; // passes url to and returns url from end user function to modify url before redirect
		
		/**
		 * Construct the object
		 */
		public function __construct(){
			// public property is an WP_Error object, accumulate errors from all user defined filters using this property
			$this->comment_err = new WP_Error();
			
			// init event to load the translation files								
			add_action('init', array($this,'localize_wpice'), 1); 
			
			// init event to enable sessions, which takes place after wordpress resets all php global values
			add_action('init', array($this,'start_session'), 2);
			
			add_action('init', array($this,'set_messages_on_init'), 3); // call on init to merge with default messages
			
			// comment form posts comment data to wp-comments-post.php
			// 'pre_comment_on_post' filter executes after comment form submits and before main script of wp-comments-post.php runs.
			// use this filter to intercept comment data for validation before wp-comments-post.php attempts to validate and save comment
			add_filter( 'pre_comment_on_post', array($this,'validate_comment_formfields'),$this->pre_comment_on_post_priority);
			
			// if the comment is a duplicate, then wordpress executes the 'comment_duplicate_trigger' action
			// generate a message for duplicate errors using get_duplicate_comment_error
			add_action('comment_duplicate_trigger',array($this,'get_duplicate_comment_error'));
			
			
			// The 'wp_die_handler' filter executes before the core wp_die() function.   Use this filter to intercept error messages
			// and terminate the script before wp-comments-post.php runs.  Stores $_POST and error data in a session variable.
			add_filter('wp_die_handler', array($this,'get_comment_err_die_handler'));
			
			// use 'template_redirect' action to call function to get $_POST and error data from session variable
			add_action('template_redirect', array($this,'convert_values_from_session'));
			
			/*
			filters to update comment form fields after submit, with $_POST data, and required mark
			optional filter to add $_POST data to field, and set required mark
			*/
			add_filter( 'comment_form_field_author', array($this,'change_author_field') );
			add_filter( 'comment_form_field_email', array($this,'change_email_field') );
			add_filter( 'comment_form_field_url', array($this,'change_url_field') );
			add_filter( 'comment_form_field_comment', array($this,'change_comment_field') );

			/*
			TEMPLATE TAGS
			use calls to apply_filters('filter name','') from functions.php or template to prevent errors if plug-in deactivated
			use apply_filters for template tags that return a value and do_action for tags that do not return a value
			DOCUMENT each TEMPLATE TAG
			*/
			
			// these TEMPLATE TAGS called from apply_filters('filter name','params')
			add_filter('wpice_get_comment_form_errors_as_list', array( $this, 'get_comment_form_errors_as_list') ); // returns list
			add_filter('wpice_print_comment_form_error_list', array( $this, 'print_comment_form_error_list') ); // prints list
			add_filter('wpice_field_has_error', array( $this, 'field_has_error') ); // returns true or false
			add_filter('wpice_get_comment_form_error_array', array( $this, 'get_comment_form_error_array')); // returns array or empty array
			add_filter('wpice_get_config_options',array( $this, 'get_config_options' )); // returns array of config_option
			add_filter('wpice_get_defined_messages',array( $this, get_defined_messages )); // returns array of custom_error_messages
			
			// these TEMPLATE TAGS called from do_action('action name','params')
			add_action('wpice_set_config_options',array( $this, 'set_config_options' )); // pass array of config_options, call from do_action
			add_action('wpice_set_messages',array( $this, 'set_messages')); // pass array of error messages, call from do_action
			
		} // END public function __construct
		
		/**
		 * localize_wpice
		 * load translation files, called from init event
		 * @since 1.1
		*/
		function localize_wpice(){
			load_plugin_textdomain('wp_inline_comment_errors', false, dirname(plugin_basename( __FILE__ ))  . '/languages/' );
			
			// localize default comment form field error messages
			// do this after loading text domain so that these strings are translated
			$this->default_error_messages = array(
											'author' => __('Name field: Please include your name.','wp_inline_comment_errors'), // author field
											'email' => __('Email field: Please include a valid email.','wp_inline_comment_errors'), // email field
											'comment' => __('Comment field: Please include a comment.','wp_inline_comment_errors'), // comment field
											'comment_duplicate' => __('Comment field: This is a duplicate comment. Please post a new comment.','wp_inline_comment_errors') // comment_duplicate
											);
		}
		
		
		/**
		 * start session
		 * store $_POST from comment form and error data  in session for reuse on template that diplays comment form
		 *
		 * @since 1.0
		 */
		public static function start_session(){
			if(!session_id()) {
				session_start();
			}
		} // END start_session
		
		
		/**
		 * set custom comment error message
		 *
		 * @since 1.0
		 * @param    array    $user_custom_errs    associative array, index is field name, value is error message
		 */
		function set_messages($user_custom_errs){
			if(is_array($user_custom_errs) == true){ // user provides some errors save in custom error messages property
				$this->custom_error_messages = $user_custom_errs;
			}
		} // END set_messages
		
		/**
		 * merge custom error messages with default messages on init.  Due to localization, default messagses are not set until init.
		 * Wait until init to merge messages, in case user has not provided all possible custom messages, use some defaults with 
		 * custom messages.
		 *
		 * @since 1.1
		 */
		 function set_messages_on_init(){
		 	if(is_array($this->custom_error_messages) == true){
		 		$this->custom_error_messages = array_merge($this->default_error_messages,$this->custom_error_messages);
			} else {
				$this->custom_error_messages = $this->default_error_messages;
			}
		 }
		

		/**
		 * get error message set by set_messages, does not return error messages created within validation functions
		 *
		 * @since 1.0
 		 * @return   array   associative array, index is field name, value is error message
		 */
		function get_defined_messages(){
			return $this->custom_error_messages;
		} // END get_defined_messages
		
		
		/**
		 * pass comment form field and error message, store a comment_err message
		 * store the error code, message in the messages array, and  $_POST field name in the data array
		 *
		 * @since 1.0
		 * @param    string    $form_field_name    comment form field, should be the same as the HTML tag name attribute
		 * @param    string    $error_message    error message associated with the field
		 */
		function store_comment_error($form_field_name,$error_message){
			$this->comment_err->add($this->error_code, $error_message); // add error message to wp_error object
			$this->comment_err->error_data[$this->error_code][] = $form_field_name; // add comment form field name to object data array
		} // END store_comment_error
		
		/**
		 * The 'validate_comment_formfields' function executes user defined filters and stores any errors in a WP_Error object.
		 * If there are no user defined fliters then use default validation and error messages.  Exectuted from 'pre_comment_on_post'
		 * WordPress comment form filter.  The pre_comment_on_post assignment is in the __construct function.
		 * 
		 * If there are errors, the function passes the WP_Error object to wp_die(), which will then call the comment_err_die_handler
		 *
		 * @since 1.0
		 * @param    object    $commentdata    comment form field data
		 * @return   object    $commentdata    pass comment form field data back to filter, or call wp_die() with no return value
		 */
		public function validate_comment_formfields( $commentdata ) {	
			/*
			validation for author/name, email and ur/website field
			If 'Comment author must fill out name and e-mail' is checked in Discussion Settings then require validation for author and email
			use default message if no custom message
			
			If user is logged in, then skip this validation because user has already provided name, email and url, 
			follows same check as wp-comments-post.php
			*/
			$user = wp_get_current_user();
			if(!$user->exists()){ // only validate these fields if the user is not logged in
				
				// run custom validation for author field
				if(has_filter($this->author_validation_filter)){ // check for user function
					$error_message = apply_filters($this->author_validation_filter,''); // execute the custom filter
					if($error_message != ''){ // has an error message
						$this->store_comment_error('author',$error_message);
					}
				 
				// does not have user function but still required by discussion settings, so create a default message
				} else if(get_option('require_name_email')){ 
					// check author field
					if(!isset($_POST['author']) || trim($_POST['author']) == ''){ // missing or empty author field
						$this->store_comment_error('author',$this->custom_error_messages['author']); // use stored message
					}
				}
				
				// run custom validation on email field
				if(has_filter($this->email_validation_filter)){
					$error_message = apply_filters($this->email_validation_filter,'');
					if($error_message != ''){
						$this->store_comment_error('email',$error_message);
					}
				 
				// does not have user function but still required by discussion settings, so create a default message
				} else if(get_option('require_name_email')){ 
					// check for missing or malformed email, use wordpress is_email function
					if(!isset($_POST['email']) || (function_exists('is_email') && !is_email($_POST['email']))){
						$this->store_comment_error('email',$this->custom_error_messages['email']); // use stored message
					}			
				}
				
				// custom validation for url field
				if(has_filter($this->url_validation_filter)){ // check for user function
					$error_message = apply_filters($this->url_validation_filter,''); // execute the custom filter
					if($error_message != ''){ // has an error message
						$this->store_comment_error('url',$error_message);
					}
				}
			}
			
			// user must provide comment regardless of logged in or not
			// validation for comment field
			if(has_filter($this->comment_validation_filter)){ // check for user function
				$error_message = apply_filters($this->comment_validation_filter,''); // execute the custom filter
				if($error_message != ''){ // has an error message
					$this->store_comment_error('comment',$error_message);
				}
			} else { // does not have user function but still required, so use a default message
				// check comment field
				if(!isset($_POST['comment']) || trim($_POST['comment']) == ''){
					$this->store_comment_error('comment',$this->custom_error_messages['comment']);
				}
			}
			
			// user may need to provide correct input for meta field regardless of logged in or not, such as captcha field
			// validation for custom comment meta fields
			if(has_filter($this->metafield_validation_filter)){ // execute all custom field validation functions, each will return an error object
				$error_messages = array();
				$error_messages = apply_filters($this->metafield_validation_filter,$error_messages); // store each error message in an array
				foreach ($error_messages as $field_name => $message){ // loop through array
					$this->store_comment_error($field_name,$message); // store error messages and field name in wp error
				}
			}
			
			// check for errors
			// if there are errors then execute the wp_die function, which then calls the 'comment_err_die_handler'
			if(count($this->comment_err->get_error_messages()) > 0){  // comment form has errors
				wp_die($this->comment_err, 'Comment Form Error');  // pass the error object to wp_die, which will execute the custom wp_die_handler
			} else { // no errors
				// pass comment data on to wp-comments-post.php to attempt to save the comment
				return $commentdata;
			}
		} // END validate_comment_formfields
	

		/**
		 * wp-comemnts-post.php checks for duplicate comment and will post the user provided error or the default error message.
		 * Triggered by the WordPress comment_duplicate_trigger core action.  Assignment to this action is in __construct.
		 *
		 * @since 1.0
		 */
		public function get_duplicate_comment_error(){	
			if(has_filter($this->comment_duplicate_filter)){ // check for  user filter
				$error_message = apply_filters($this->comment_duplicate_filter,''); // execute the custom filter
				if($error_message != ''){ // has an error message
					$this->store_comment_error('comment',$error_message);
				}
			} else { // no custom filter, use the default error message
				$this->store_comment_error('comment',$this->custom_error_messages['comment_duplicate']);
			}
			wp_die($this->comment_err, 'Comment Form Error'); 
		}
		
		/**
		 * Use get_comment_err_die_handler intermediary function in case there is a need to remove this filter with remove_filter()
		 *
		 * @since 1.0
		 */
		public function get_comment_err_die_handler(){return array($this,'comment_err_die_handler');}
		
		/**
		 * This err handler function checks for a wp_error object that has code = 'comment_err', 
		 * then stores the post data and error in a session
		 * then redirects the user back to the original post
		 * then die() to prevent remainder of script on wp-comments-post.php from executing
		 * 
		 * If user has cookies disabled, attempt to pass session ID in GET
		 * If user has cookies disabled and php configured for cookies only sessions, then load error template
		 * Custom error template can use $comment_post_id or $redirect_url to build links back to the post
		 * If plug-in cannot find custom error template then use WordPress default error display
		 *
		 * @since 1.0
		 * @param    object    $message    WP_Error object
		 * @param    string    $title      Default title for the page, passed on to the WordPress _default_wp_die_handler()
		 * @param    array     $args       passed on to the WordPress _default_wp_die_handler()
		 */
		 
		public function comment_err_die_handler($message, $title='Comment Form Errors', $args=array()) {
			// check for comment_err error code
			if(is_wp_error($message) && $message->get_error_code() == $this->error_code){  // is a comment error
				// save HTTP POST values from form and wp_error object in session
				$_SESSION[$this->session_form_data] = $_POST;  // copy post data to sessoin variable
				$_SESSION[$this->session_wp_error] = $message; // store wp_error object in session
				
				$this->comment_post_id = $_POST['comment_post_ID'];
				$post_url = get_permalink($this->comment_post_id); // 'comment_post_ID' field from comment form contains original post id
				$redirect_url = $post_url;
				
				// cookies disabled but PHP can support passing session id with GET
				if(!isset($_COOKIE['PHPSESSID']) && ini_get('session.use_only_cookies') == 0){
					$redirect_url = $post_url . '?' . htmlspecialchars(SID); // add session id to url
				}
				
				// allow user defined filter to change url before redirect
				if(has_filter($this->get_redirect_url)){
					$redirect_url = apply_filters($this->get_redirect_url, $redirect_url);
				}
				
				// redirect to original post
				// check for session support
				// when session is disabled show custom error template or use WordPress default error display
				if(isset($_COOKIE['PHPSESSID'])){ // php using cookies for session id
					wp_safe_redirect($redirect_url); // redirect to original post, session id in COOKIE
				} else if(ini_get('session.use_only_cookies') == 0){ // cookies disabled, php supports session id passed in GET
					wp_safe_redirect($redirect_url); // pass session id through GET
					
				// session is disabled because cookies disabled, and php configured to only use cookies for session, no support for GET
				// display comment error in custom template or WordPress default error display
				} else {
					// get the path to the custom die template
  					$template_full_path = get_theme_root() . '/' . get_template() .'/' . $this->config_options['error_template_path'];
					if(is_file($template_full_path)){ // check for user specified template
						$comment_post_id = $this->comment_post_id; // use $comment_post_id within template code to get information about post
						// review example template in examples folder					
						include_once($template_full_path); // load template
					} else { // user specified template does not exist
						_default_wp_die_handler($message,$title, $args); // allow WordPress to use default message display instead
					}
				}
				
				die(); // end script to prevent remainder of wp-comments-post.php from executing and saving the comment
			} else { // not a comment_err, use default wordpress error message display
				_default_wp_die_handler($message,$title, $args); // use default message page instead
			}
		} // END get_comment_err_die_handler
		
		/**
		 * This function copies session variable with form data into $_POST
		 * use 'template_redirect' action to call this function
		 *
		 * @since 1.0
		 */
		public function convert_values_from_session(){
			if(comments_open()){ // any content that allows comments
				global $post;
				$current_post_id = $post->ID; // get the post id of the current page or post
				$stored_post_id = $_SESSION[$this->session_form_data]['comment_post_ID']; // get the post id stored in session
				
				// form has been submitted and redirect to correct post
				if(isset($_SESSION[$this->session_form_data]) && $stored_post_id == $current_post_id) {
					$_POST = $_SESSION[$this->session_form_data]; // copy data back into _POST array
					unset($_SESSION[$this->session_form_data]); // delete the session variable
					
				// user may have navigated to another post without correcting comment form
				// session data carries over to all pages and may display error from a previous comment form on the newly viewed post
				// delete any previous session data stored by this script to avoid displaying error from another post
				} else {
					// delete the session variables
					unset($_SESSION[$this->session_form_data]);
					unset($_SESSION[$this->session_wp_error]); 
				}
			}
		} // END convert_values_from_session
		
		
		/**
		 * returns the error object from the $_SESSION variable or false if the variable does not exist or is not a error object
		 *
		 * @since 1.0
		 * @return WP_error object or false
		 */
		public function get_comment_form_error_obj(){
			if(isset($_SESSION[$this->session_wp_error]) && is_wp_error($_SESSION[$this->session_wp_error])){
				return $_SESSION[$this->session_wp_error];
			} else {
				return false;
			} 
		} // END get_comment_form_error_obj
		
		/**
		 * combine error object arrays into a single associative array with $_POST field name as index and error message as value
		 * return empty array if there are no errors
		 * can also be used as a Template tag, using apply_filters('get_comment_form_error_array','');
		 *
		 * @since 1.0
		 * @return 		array 	associative array with $_POST field name as index and error message as value or empty array
		 */
		public function get_comment_form_error_array(){
			$error_obj = $this->get_comment_form_error_obj();
			if($error_obj == false) { return array(); } // return empty array if there are no errors
				
			// create associative array from fiel names as index and error message as value
			$error_array = array_combine($error_obj->get_error_data($this->error_code),$error_obj->get_error_messages());

			return $error_array;
		} // END get_comment_form_error_array
		
		/**
		 * return true or false if field name has a corresponding error
		 * for meta fields the field name must correspond to the field name used in the validation function
		 * useful for adding a required mark to or changing style of a form HTML element
		 * can also be used as a Template tag, see filters in __construct function
		 *
		 * @since 1.0
		 * @return boolean
		 */
		public function field_has_error($fieldname){
			$err = $this->get_comment_form_error_obj();
			if($err == false){
				return false; // no errors
			} else {
				$err_data = $err->get_error_data($this->error_code);
				return (in_array($fieldname,$err_data)); // true if in array, false if not in array
			}
		} // END field_has_error
		
		
		/**
		 * returns an HTML formatted list of errors or empty string if no errors
		 * style sheet class multiple errors 'ul.comment-form-errors', or for one error 'ul.comment-form-errors comment-form-single-error' 
		 * can also be used as a Template tag
		
		 * $user_args = array( 		'type' 					=> 'ul', // default to 'ul'
									'class' 				=> 'comment-form-errors',
									// add selector when only one error present
									'single-err-selector' 	=> 'comment-form-single-error', 
									// function appends field name to end of prefix so each li has a unique class
									'li-class-prefix' 		=> 'comment-form-', 
									'before-list' 			=> '',
									'after-list' 			=> '',
							);
		 
		 * @since 1.0
		 * @param		array 		$user_args 		associative array that sets the formatting of the HMTL list
		 * @return 		string 						HTML formatted list of errors or empty string for no errors
		 */
		 
		public function get_comment_form_errors_as_list($user_args = array()){
			$default_args = array( 	'type' 					=> 'ul', // default to 'ul'
									'class' 				=> 'comment-form-errors',
									// add selector when only one error present
									'single-err-selector' 	=> 'comment-form-single-error', 
									// function appends field name to end of prefix so each li has a unique class
									'li-class-prefix' 		=> 'comment-form-error-', 
									'before-list' 			=> '',
									'after-list' 			=> '',
							);
			
			// if auto_display_errors is set, then add a message before the list and enclose in <div> tags with
			// div class="comment-form-error-box"			
			if(!is_null($this->config_options['auto_display_errors'])){
				$default_args['before-list'] = '<div class="comment-form-error-box"><!-- wp inline errors -->' . "\n"; // open div
				// initial text of error message
				$default_args['before-list'] .= sprintf(__('%1$sPlease correct the following problems:%2$s','wp_inline_comment_errors'),'<p>','</p>');
				// close the div
				$default_args['after-list'] = '</div><!-- end comment-form-error-box -->';
			}
			
			// overwrite default values with user values, if user_args is not empty				
			$list_args = (empty($user_args)) ? $default_args : array_merge($default_args, $user_args);
			
			// get the errors as an associative array or empty array
			$errors = $this->get_comment_form_error_array();
			if(empty($errors)){ return ''; } // return '' empty string, no errors
			
			if(count($errors) > 0){ // has errors
				$list_elements = '';
				foreach($errors as $field_name => $message){ // loop through associative array
					$li_class = $list_args['li-class-prefix'] . $field_name; // build unique class for each field error
					$list_elements .= "\n\t\t<li class=\"" . $li_class . '">' . $message . '</li>'; // build each li element
				}
				
				// build css class
				$css_class = (count($errors) == 1) ? $list_args['class'] . ' ' . $list_args['single-err-selector'] : $list_args['class'];
				
				// create error list			
				$error_html = "\n\t<" . $list_args['type']  . ' class="' . $css_class . '">' . $list_elements . 
								"\n\t</" . $list_args['type'] . ">\n"; // format list
				
				// add before string
				if(!empty($list_args['before-list'])){
					$error_html = $list_args['before-list'] . "\n" . $error_html;
				}
				
				// append after string
				if(!empty($list_args['after-list'])){
					$error_html = $error_html  . "\n" . $list_args['after-list'];
				}
			} else { // no errors 
				$error_html = ''; // return empty string
			}
			
			return $error_html;
		} // END get_comment_form_errors_as_list
		
		
		/**
		 * pass same array values as get_comment_form_errors_as_list
		 * calls get_comment_form_errors_as_list to format list and prints the HTML returned by the function
		 * prints the error list at the top of the form before the fields
		
		 * $user_args same as get_comment_form_errors_as_list
		 
		 * @since 1.0
		 * @param		array 		$user_args 		associative array that sets the formatting of the HMTL list
		 */
		public function print_comment_form_error_list($user_args = array()){
			$fields_err_list = $this->get_comment_form_errors_as_list($user_args);
			$fields_err_msg = ($fields_err_list != '') ? $fields_err_list : '';
			print $fields_err_msg;
		} // END print_comment_form_error_list
		
		
		/**
		 * pass array with properties corresponding to config_options array
		 * merges user provided array with default values and assigns to config_options
		 * set up automatic display of errors if auto_display_errors is not null
		
		 * see public $config_options property
		 
		 * @since 1.0
		 * @param	  array 	$args	should be associative array with same index and values as public $config_options
		 */
		public function set_config_options($args){
			$previous_wpaction = $this->config_options['auto_display_errors']; // get the previously saved action
			
			// merge or overwrite user option values with pre-existing and pre-set option values
			$this->config_options = array_merge($this->config_options, $args);
			
			/*
			enable automatic display of errors so plug-in works "out of the box" with no required configuration
			show error list based upon user provided or default comment form filter
			add an anchor tag using comment_form_before
			add hash location to redirection url
			*/
			
			// set up action that will automatically display error list
			if(!is_null($this->config_options['auto_display_errors'])){ // only set up this action if action defined by user or plug-in
				add_action($this->config_options['auto_display_errors'],array($this,'print_comment_form_error_list'));
				add_action('comment_form_before',array($this,'add_anchor_tag'));
				add_filter('wpice_get_redirect_url',array($this,'add_anchor_frament'));
			} else {
				// need to remove the previously assigned action to avoid duplicate printing of error message
				remove_action($previous_wpaction,array($this,'print_comment_form_error_list'));
				// remove action that automatically adds anchor tag
				remove_action('comment_form_before',array($this,'add_anchor_tag'));
				// remove filter that automatically adds anchor name as url frament to redirect url
				remove_filter('wpice_get_redirect_url',array($this,'add_anchor_frament'));
			}
		} // END set_config_options
		
		
		/**
		 * return array of all options		
		 * see public $config_options property
		 
		 * @since 1.0
		 * @return  array 	array of all configuration options
		 * @todo should also return just one configuration option, name passed as string instead of array
		 */	 
		public function get_config_options($option = ''){
			/*
			if(isset($this->config_options[$option])){ // return specific option value
				return $this->config_options[$option];
			} else { // if no option specified return all options
				return $this->config_options;
			}
			*/
			return $this->config_options;
		}
		
		/*
		----------------------------------------------------------------------
		METHODS FOR UPDATING COMMENT FORM WITH $_POST values and required mark
		note that these functions may not be able to parse HTML as intended
		if the HTML for the fields is significantly altered from the WordPress
		default HTML for comment form fields
		----------------------------------------------------------------------
		*/
		
		
		/**
		 * only for WordPress core comment form fields; author, email, url and comment
		 * return true or false to determine if field is required
		 * URL field is considered 'required' if there is a user function for it
		 
		 * @since 1.0
		 * @return  boolean		returns true or false to determine if field is required, always returns false for non core fields
		 */	 
		public function is_required_field($name=''){
			// for author and email, check both WordPress options and for user filter
			if($name == 'author'){
				return (has_filter($this->author_validation_filter) || get_option('require_name_email'));
			} else if($name == 'email'){
				return (has_filter($this->email_validation_filter) || get_option('require_name_email'));
			} else if($name == 'url'){
				return (has_filter($this->url_validation_filter)); // only required if there is a user validation function
			} else if($name == 'comment'){ 
				return true; // always required
			} else {
				return false; // field is not one of WordPress core comment form fields
			}
		}
		

		/**
		 * prints anchor tag, called from comment_form_before, only for auto_display_errors is true
		 * adds anchor tag above title of form, browser will scroll to this point in page after redirect
		 
		 * @since 1.0
		 */
		public function add_anchor_tag(){
			print '<a name="' . $this->config_options['anchor_fragment_name'] . '"></a><!-- wp inline errors -->' . "\n";
		} // END add_anchor_tag
		

		/**
		 * adds anchor name to url fragment, called from wpice_get_redirect_url, only for auto_display_errors
	
		 * @since 1.0
		 */
		public function add_anchor_frament($url){
			$url = $url . '#' . $this->config_options['anchor_fragment_name']; // add fragment to url
			return $url;
		} // END add_anchor_frament
		

		/**
		 * loads html string into DOMDocument holder
	
		 * @since 1.0
		 * @param	 string $html HTML string to be loaded into DOMDocument for parsing
		 * @return DOMDocument object
		 * @todo Should probably be a private method
		 */ 
		public function loadHTML($html){
			libxml_use_internal_errors(TRUE); // supress errors
			$doc = new DOMDocument; // create DOM instance
			
			// set encoding to UTF-8 because certain language translations, such as chinese are 
			// appearing as a code rather than as the proper characters
			$doc->loadHTML( '<?xml encoding="UTF-8">' . $html ); // load the html
			
			// set encoding for each item in the HTML fragment
			foreach ($doc->childNodes as $item)
				if ($item->nodeType == XML_PI_NODE)
					$doc->removeChild($item); // remove hack
			$doc->encoding = 'UTF-8'; // insert proper
			
			libxml_clear_errors();
			return $doc;
		} // END loadHTML
		

		/**
		 * build html only from child nodes within the body tag of the document
		 * copy all nodes within body tag into a new DOMDocument object so body tag is excluded not printed into current document
	
		 * @since 1.0
		 * @param	 DOMDocument object 	$doc 	contains the nodes to be printed into the document
		 * @return string 	HTML, minus uneeded body tag to be printed into document
		 * @todo Should probably be a private method
		 */ 
		public function getHTMLFragment($doc){
			$body = $doc->GetElementsByTagName('body')->item(0);
			$fragment = new DOMDocument; // hold the html wihtout extra tags
			foreach ($body->childNodes as $child){
				$fragment->appendChild($fragment->importNode($child, true)); // add the node from passed $doc to $fragment
			}
			return  $fragment->saveHTML();
		} // END getHTMLFragment

		
		/**
		 * Called from comment_form_field_{fieldname} filter, intended to modify input type text HTML tag
		 * pass wordpress generated html for comment form field, field name attribute and new value for field value attribute
		 * updates html with field value attribute set to $value, so that the plug-in can 're-post' the $_POST data to this field
	
		 * @since 1.0
		 * @param			string 	 $html 		wordpress generated html for comment form field
		 * @param			string 	 $name 		name attribute for the field
		 * @param			string 	 $value 	new value for field value attribute
		 * @return 			string 				HTML for the form element with value attribute updated
		 * @todo Should probably be a private method
		 */ 
		public function change_input_tag_value($html,$name,$value){
			if(function_exists('esc_attr')){
				$value = esc_attr($value); // use wordpress function to escape values before printing into a form element
			}
			
			// update value attribute
			// parse $html
			$doc = $this->loadHTML($html); // load $html string
			$xpath = new DomXpath($doc); // create xpath 
			// execute query to find first input tag with name attribute matching the field name
			$input = $xpath->query('//input[@name="' . $name . '"][1]')->item(0); // find first input tag with name attribute
			if ($input instanceof DomElement){ // verify that $input is an object
				$input->setAttribute('value',$value); // set the value attribute to $value
				$input->setAttribute('aria-required','true');  // create the aria-required attribute
			}
			
			$html = $this->getHTMLFragment($doc); // get html
			return $html;
		} // END change_input_tag_value
		
		
		/**
		 * Called from comment_form_field_{fieldname} filter
		 * pass wordpress generated html for comment field
		 * removes the wp generated required mark for author and email fields
		 * return html
	
		 * @since 1.0
		 * @param			string 	 $html 		wordpress generated html for comment form field
		 * @return 			string 				HTML for the form element with value attribute updated
		 * @todo Should probably be a private method
		 */ 
		public function remove_wp_req_mark($html){
			$doc = $this->loadHTML($html); // load $html string
			$xpath = new DomXpath($doc); // create xpath 
			// find wordpress generated required mark
			$wp_req_mark = $xpath->query('//span[@class="required"][1]')->item(0); // find wordpress required mark
			$wp_req_mark->parentNode->removeChild($wp_req_mark); // remove the required mark

			$html = $this->getHTMLFragment($doc); // get html
			return $html;
		} // END remove_wp_req_mark
		

		/**
		 * Called from comment_form_field_{fieldname} filter
		 * pass wordpress generated html for comment field, field name
		 * adds required mark based upon req_mark_location, also adds aria-required=true to tag
		 * return html
	
		 * @since 1.0
		 * @param			string 	 	$html 		wordpress generated html for comment form field
		 * @param			string 	 	$name 		name attribute for the field
		 * @return 			string 					HTML for the form element with value attribute updated
		 * @todo Should probably be a private method
		 */ 
		public function display_req_mark($html,$name){
			$doc = $this->loadHTML($html); // load $html string
			
			$req_mark = $doc->createDocumentFragment(); // container for the required mark
			$req_mark->appendXML($this->config_options['req_mark']); // add the required mark string to the container
			
			$xpath = new DomXpath($doc); // create xpath
			
			// author, email and url fields are input type tags
			if(in_array($name,array('author','email','url'))){
				$input = $xpath->query('//input[@name="' . $name . '"][1]')->item(0); // find first input tag with name attribute
				if ($input instanceof DomElement){ // verify that $input is an object
					if($this->config_options['req_mark_location'] == 'after-field'){
						$input->parentNode->appendChild($req_mark); // display after input tag
					}
					$input->setAttribute('aria-required','true');  // create the aria-required attribute
				}
			
			// comments field is a textarea	
			} else if($name == 'comment'){
				// find first textarea tag with author with name attribute = 'comment
				$textarea = $xpath->query('//textarea[@name="comment"][1]')->item(0);
				if ($textarea instanceof DomElement){ // verify that $textarea is an object
					if($this->config_options['req_mark_location'] == 'after-field'){
						$textarea->parentNode->appendChild($req_mark); // display after tag
					}
					$textarea->setAttribute('aria-required','true');  // create the aria-required attribute
				}
			
			} else { // no field name specified
				return $html;
			}
			
			// if printing required mark after label, find label and append req_mark, type of tag does not matter in this case
			if($this->config_options['req_mark_location'] == 'after-label'){	
				$label = $xpath->query('//label[@for="' . $name . '"][1]')->item(0); // find first lable tag with for='$name' attribute
				if ($label instanceof DomElement){ // verify that $textarea is an object
					$label->nodeValue = trim($label->nodeValue); // trime white space around label text
					$label->nodeValue = $label->nodeValue . ' '; // add single space after label
					$label->appendChild($req_mark); // add required mark to text inside of label tag
				}	
			}
		
			$html = $this->getHTMLFragment($doc); // get html
			return $html; 
		} // END display_req_mark

		
		/**
		 * 'comment_form_field_{fieldname}' calls this function, 
		 * pass html for given field
		 * returns modified html for the field which wordpress will print into the comment form
	
		 * @since 1.0
		 * @param			string 	 	$field 		wordpress generated html for comment form field
		 * @return 			string 					modified HTML for the form element
		 * @todo Should probably be a private method
		 */ 
		public function change_author_field( $field ) {
			// add $_POST value to author field
			if($this->config_options['show_post_data'] == true){ // check for condition
				$field = $this->change_input_tag_value($field,'author',@$_POST['author']);
			}
			
			// remove wordpress generated required mark, 
			// only do this if wordpress require name/email settings is checked true, 
			// which cuases WordPress to display required mark for the name and email comment form fields
			if($this->config_options['hide_wp_req_mark'] == true && get_option('require_name_email')){
				$field = $this->remove_wp_req_mark($field);
			}
			
			// if user intends to display the required mark, this is a required field and (form not yet submitted or there is an error)
			// do not display marks for fields that are not required or that do not have errors
			if($this->config_options['req_mark_location'] != 'none'  
				&& $this->is_required_field('author') 
				&& (!isset($_POST['author']) || $this->field_has_error('author'))){
				
				$field = $this->display_req_mark($field,'author');
			}
			return $field;
		} 
		
		
		/**
		 * 'comment_form_field_{fieldname}' calls this functions, 
		 * pass html for given field
		 * returns modified html for the field which wordpress will print into the comment form
	
		 * @since 1.0
		 * @param			string 	 	$field 		wordpress generated html for comment form field
		 * @return 			string 					modified HTML for the form element
		 * @todo Should probably be a private method
		 */ 
		public function change_email_field( $field ) {
			// add $_POST value to email field
			if($this->config_options['show_post_data'] == true){
				$field = $this->change_input_tag_value($field,'email',@$_POST['email']);
			}
			
			// remove wordpress generated required mark, 
			// only do this if wordpress require name/email settings is checked true, 
			// which cuases WordPress to display required mark for the name and email comment form fields
			if($this->config_options['hide_wp_req_mark'] == true && get_option('require_name_email')){
				$field = $this->remove_wp_req_mark($field);
			}
			
			// intend to display the required mark, this is a required field and (form not yet submitted or there is an error)
			// do not display marks for fields that are not required or that do not have errors
			if($this->config_options['req_mark_location'] != 'none'  
				&& $this->is_required_field('email') 
				&& (!isset($_POST['email']) || $this->field_has_error('email'))){
				
				$field = $this->display_req_mark($field,'email');
			}
			return $field;
		}
		
		/**
		 * 'comment_form_field_{fieldname}' calls this functions, 
		 * pass html for given field
		 * returns modified html for the field which wordpress will print into the comment form
	
		 * @since 1.0
		 * @param			string 	 	$field 		wordpress generated html for comment form field
		 * @return 			string 					modified HTML for the form element
		 * @todo Should probably be a private method
		 */ 
		public function change_url_field( $field ) {
			if($this->config_options['show_post_data'] == true){
				$field = $this->change_input_tag_value($field,'url',@$_POST['url']);
			}
			
			// intend to display the required mark, and field is required,
			// and (form not yet submitted or submitted with an error)
			// do not display marks for fields that are not required or that do not have errors
			if($this->config_options['req_mark_location'] != 'none'  
				&& $this->is_required_field('url')
				&& (!isset($_POST['url']) || $this->field_has_error('url'))){
				
				$field = $this->display_req_mark($field,'url');
			}
			return $field;
		} // END 'comment_form_field_{fieldname}' filter functions
		

		/**
		 * Called from comment_form_field_comment filter, intended to modify textarea
		 * pass wordpress generated html for comment field, field name attribute and new value for field value
		 * option to adds required mark before or after field
		 * updates html with field value attribute set to $value
		 * updates html so that input tag has aria-required='true' attribute
		 * return html
	
		 * @since 1.0
		 * @since 1.0
		 * @param			string 	 	$html 		wordpress generated html for comment form field
		 * @param			string 	 	$name 		name attribute for the field
		 * @param			string 	 	$value 		new value for the text of the textreaa
		 * @return 			string 					HTML for the form element with value attribute updated
		 * @todo Should probably be a private method
		 */ 
		public function change_textarea_tag_value($html,$name,$value){
			if(function_exists('esc_attr')){
				$value = esc_attr($value); // escape
			}
			
			$doc = $this->loadHTML($html); // parse
			$xpath = new DomXpath($doc); // create query obj
			// execute query
			$textarea = $xpath->query('//textarea[@name="' . $name . '"][1]')->item(0); // find first input tag with author with name attribute = 'author'
			if ($textarea instanceof DomElement){ // make sure result is an object
				$textarea->nodeValue = $value;  // set nodeValue of textarea, text inbetween open and close tag, to $value
			}
			
			$html = $doc->saveHTML(); // get the modified html
			return $html;
		} // END change_textarea_tag_value
		
		
		/**
		 * 'comment_form_field_comment' calls this function, 
		 * pass html for comment field
		 * returns modified html for the field which wordpress will print into the comment form
	
		 * @since 1.0
		 * @param			string 	 	$field 		wordpress generated html for comment form field
		 * @return 			string 					modified HTML for the form element
		 * @todo Should probably be a private method
		 */
		public function change_comment_field( $field ) {
			if($this->config_options['show_post_data'] == true){
				$field = $this->change_textarea_tag_value($field,'comment',@$_POST['comment']);
			}
			
			// intend to display the required mark, this is a required field and (form not yet submitted or there is an error)
			// do not display marks for fields that are not required or that do not have errors
			if($this->config_options['req_mark_location'] != 'none'  
				&& $this->is_required_field('comment') 
				&& (!isset($_POST['comment']) || $this->field_has_error('comment'))){
				
				$field = $this->display_req_mark($field,'comment');
			}
			return $field;
			
		} // END change_comment_field
		
	} // END class WP_Inline_Comment_Errors
} // END if(!class_exists('WP_Inline_Comment_Errors'))
?>