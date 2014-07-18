<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>

<meta charset="<?php bloginfo( 'charset' ); ?>" />
<title>
<?php
/* Print the <title> tag based on what is being viewed. */
global $page, $paged;
wp_title( '|', true, 'right' );
// Add the blog name.
bloginfo( 'name' );
// Add the blog description for the home/front page.
$site_description = get_bloginfo( 'description', 'display' );
if ( $site_description && ( is_home() || is_front_page() ) )
    echo " | $site_description";
// Add a page number if necessary:
if ( $paged >= 2 || $page >= 2 )
    echo ' | ' . sprintf( __( 'Page %s', 'twentyten' ), max( $paged, $page ) );
?>
</title>
<link rel="profile" href="http://gmpg.org/xfn/11" />
<!--grab the stylesheet-->
<link rel="stylesheet" type="text/css" media="all" href="<?php bloginfo( 'stylesheet_url' ); ?>" />
<link rel="stylesheet" type="text/css" media="all" href="http://localhost/www.upleftstudio.com/js/menu_jquery.js" />


<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>" />

	
	
	

<?php //  wp_head(); is required.
    wp_head();
?>

<script>
	$(document).ready(function(){
    $('a[href*="#"]').click(function(){
        $($(this).attr("href")).effect("highlight", {color: '#26ade4'}, 2000);
    });
});
</script>
</head>
<body>
<?php get_template_part('site_logo'); ?>
<?php get_template_part('site_nav'); ?>