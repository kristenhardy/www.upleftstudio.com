<?php
/*
VALIDATION FUNCTIONS 
-------------------------------------------------------------
*/

/* validate the author */
add_filter('wpice_validate_commentform_author','is_author_valid');
function is_author_valid($error_msg = ''){
	// check author field
	if(!isset($_POST['author']) || trim($_POST['author']) == ''){
		$error_msg = "Validation function message: Please include your name.";
	}
	return $error_msg;
}



/* validate the email */
add_filter('wpice_validate_commentform_email','is_email_valid');
function is_email_valid(){
	if(!isset($_POST['email'])){ // user did not provide an email address
		$error_msg = "Please include an email address.";
	} else if (!is_email($_POST['email'])){ // user email address invalid
		$error_msg = "Validation function message: Please include a valid email address.";
	} else { // no error
		$error_msg = '';
	}
	return $error_msg; // return error message
}

/* validate the url */
add_filter('wpice_validate_commentform_url','is_url_valid');
function is_url_valid($error_msg = ''){
	// check url field
	if(!isset($_POST['url']) || !preg_match("#^https?://.+#", $_POST['url'])){
		$error_msg = "Validation function message: Please include a valid URL to your website.";
	}
	return $error_msg;
}


/* validate the comment */
add_filter('wpice_validate_commentform_comment','is_comment_valid');
function is_comment_valid($error_msg = ''){
	// check comment field
	if(!isset($_POST['comment']) || trim($_POST['comment']) == ''){
		$error_msg = "Validation function message: Please include a comment.";
	}
	return $error_msg;
}

/*
comment duplicate
generate message in case of duplicate comment
wp-comemnts-post.php will handle validation for this situation
*/
add_filter('wpice_comment_duplicate_trigger_filter','get_duplicate_comment_message');
function get_duplicate_comment_message($error_msg = ''){
	$error_msg = "Validation function message: You've already posted this comment. Please post a new comment.";
	return $error_msg;
}

?>