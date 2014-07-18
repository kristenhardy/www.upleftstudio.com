<?php
/* 
some examples of how to use wp inline comment errors plug-in functions
refer to http://justintadlock.com/archives/2010/07/21/using-the-wordpress-comment-form for more information about the comment form
*/

/*
CONFIGURATION
-------------------------------------------------------------
*/

// arguments for set_config_options overrides default plug-in settings
define('MY_SITE_REQ_MARK','<span class="required">!</span>'); // define your required mark, use it as a constant through out your theme files

$opts = array(
				/*
				change the required mark from the default set in the class. 
				Most likely your theme already has a style for .required.
				*/
				'req_mark' => MY_SITE_REQ_MARK,
			);
do_action('wpice_set_config_options',$opts);


$error_msgs = array(
				'author' 			=> 'Config file message: Please include your name.',
				'email'  			=> 'Config file message: Please include a valid email address.',
				'comment'			=> 'Config file message: Please include a comment.',
				'comment_duplicate' => 'Config file message: This is a duplicate comment please post a new comment.'
			);
do_action('wpice_set_messages',$error_msgs);
?>