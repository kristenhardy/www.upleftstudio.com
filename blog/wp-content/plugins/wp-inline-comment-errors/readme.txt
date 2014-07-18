=== Plugin Name ===
Contributors: aviarts
Donate link: http://www.aviarts.com/wp-inline-comment-errors-plug-in/
Author URI: http://www.aviarts.com/
Plugin URI: http://www.aviarts.com/wp-inline-comment-errors-plug-in/
Tags: comments, comment fields, comments error messages
Requires at least: 3.5.1
Tested up to: 3.8.1
Stable tag: 1.1
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html 

Enables WordPress to display comment form field errors in the page or post with the comment form. Does not require Javascript or error page redirect.

== Description ==

The WP Inline Comment Errors plug-in enables WordPress to display comment form field errors directly in the page or post that displays the comment form. You can add your own validation functions for author, email, url, comment and custom meta comment form fields and customize the way the error messages or error indications appear. This is a purely PHP solution that does not rely on JavaScript or redirecting the browser to a error message page template. 

Out of the box and without any configuration, the plug-in will display a list of errors in between the title and first field of the comment form. The plug-in will also show an asterisk as a required mark next to the label of the name, email and comment fields and repost the comment form data for these fields. You can further customize the behavior of the plug-in with the plug-in functions.

Visit the [WP Inline Comment Errors](http://www.aviarts.com/wp-inline-comment-errors-plug-in/ "WP Inline Comment Errors plug-in web site")  for more information and documentation.

== Installation ==

This section describes how to install the plugin and get it working.  You can install the plug-in using WordPress plug-ins page or you can manually install the plug in.


1. Download the plug-in wp-inline-comment-errors.zip archive
1. Unpack the wp-inline-comment-errors.zip archive
1. Upload the folder named `wp-inline-comment-errors` to the `/wp-content/plugins/` directory
1. Activate the plugin through the 'Plugins' menu in WordPress
1. Review the plug-in Settings page to see how the plug-in is configured
1. Review the plug-in documentation for more information on how to customize the plug-in

== Frequently Asked Questions ==

= Do I need add any code to my site to make this plug-in work? =

No.  The out of the box install should automatically display a list any comment form error messages in the post or page with the form.

= Can I change the way the plug-in displays error information? =

Yes.  The plug-in is very customizable.  You can use plug-in functions to modify just about any aspect of the display of errors.  Note that you will need to at least be familiar with coding cascading style sheets to make changes to the 'out of the box' error display.  If you want to customize beyond that you will need to be familiar with PHP and basic WordPress programing concepts.  Please review the extensive plug-in documentation at the plug-in page.

= Can I change the wording of the error messages? =

Yes.  You will need to add some code to your functions.php in order to set new wording for the message.  Furture version of the plug-in will allow you to set the message wording in the plug-in settings page.

= Does this plug-in use Javascript AJAX to display errors? =

No.  This is a purely PHP based solution that prints errors back into the page or post that someone is trying to post a comment to.  While Javascript AJAX solutions offer a convenient user experience, it is possible for a user to turn off Javascript which will affect the way your web site displays comment form error messages.

= Why does this plug-in use PHP sessions? =

PHP sessions are an idiomatic way to pass data between two different scripts. The plug-in uses a session variable to pass error information and HTTP POST data from the comment form back to the page or post that the user was commenting on. Normally a user submits the comment form which passes data to the WordPress wp-comments-post.php script.  If there is an error the wp-comments-post.php script terminates and displays errors using WordPress's default error display, which is an unformatted page.  The plug-in improves upon this behavior this by storing the error data and form POST data in a session variable, redirecting the user back to the page or post and printing POST data back into the form and printing the error information where you specify.

= What happens if a user has disabled cookies and my server does not support GET based sessions? =

In the rare case where a user has their web browser set up to  block all cookies and your server is set up to handle sessions with cookies only (ie sessions are disabled), then the plug-in will fall back to displaying error messages in an error message template.  You can provide your own customized template so the error messages appear in a page formatted like your web site.  If you do not provide a template then the plug-in will use the WordPress default error display to show any error messages.  Review the documentation for tips on how to create an error message template.

= How do I know how my server supports sessions? =

The plug-in settings page provides information about your PHP sessions settings and will advise you as to whether you should set up a error template or not.

== Screenshots ==

1. Screen shot of WordPress default error message display.
2. Screen shot of the 'out of the box' comment error display for the WP Inline Comment Errors plug-in.
3. Screen shot of the settings page.

== Changelog ==

= 1.1 =
* Corrected an issue where default error messages would not translate.
* Corrected an issue where form field names were not displaying properly when WordPress is set to use Chinese language
* Corrected an issue where plug-in incorrectly displayed name field error message for duplicate comment situation
* Corrected an issue where custom messages set by wpice_set_messages configuration function would not display

= 1.0 =
* First release.

== Upgrade Notice ==

= 1.1 =
* Better support for translation into different languages.
* Provided detailed instructions for how to translate this plug-in and test the translation.
* Fixed 4 errors
* Spanish and Taiwan Chinese language translations

= 1.0 =
* First release.

== Translations ==

* English
* Spanish [Andrew Kurtis WebHostingHub](http://www.webhostinghub.com/)
* Chinese Taiwan

*Note:* The plug-in is localized/ translateable by default. Please contribute your language to the plugin to make it even more useful and I will add your translation credit and a link to your web site on this page.  Read the 'translation-instructions.rtf' document in the plug-in files for detailed instructions on how to translate this plug-in.