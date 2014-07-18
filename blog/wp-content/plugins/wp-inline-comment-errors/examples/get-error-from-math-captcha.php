<?php
/*
EXAMPLE MOIDIFACATION TO SUPPORT A 3RD PARTY CAPTCHA PLUGIN
code to enable support for 'math captcha' by BestWebSoft

1) remove the filter used by math captcha
2) paste the relevant validation code into your own function
3) call your function from the wpice validate_commentform_metafield filter
*/

remove_filter( 'preprocess_comment', 'cptch_comment_post' ); // remove filter set by math captcha plug-in
add_filter('validate_commentform_metafield','is_math_captcha_correct'); // execute validation from this filter instead
function is_math_captcha_correct($error_msgs){
	// based upon the code from end of cptch_comment_post validation function in plugin code
	global $cptch_options,$str_key;
	
	$user = wp_get_current_user(); // get current user
	// only attempt to validate the captcha for users not logged in and if plug-in installed
	if(!$user->exists() && function_exists('cptch_plugin_init')) { 
		// If captcha is empty
		if ( isset( $_REQUEST['cptch_number'] ) && "" ==  $_REQUEST['cptch_number'] )
			$error_msgs['cptch_number'] = 'Math Captcha Error: Please fill the math form.'; // add the error message to the list
	
		if ( isset( $_REQUEST['cptch_result'] ) && isset( $_REQUEST['cptch_number'] ) && 0 == strcasecmp( trim( decode( $_REQUEST['cptch_result'], $str_key ) ), $_REQUEST['cptch_number'] ) ) {
			// captcha was matched
			return $error_msgs; // pass along any other rror messages, do not add a message for this field
		} else { // incorrect captcha value
			// add the error message to the list
			$error_msgs['cptch_number'] = 'Math Captcha Error: You have entered an incorrect CAPTCHA value. Please try again.';
		}
	}
	return $error_msgs; // return the error messages
}
?>