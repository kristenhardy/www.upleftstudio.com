<?php
function theme_styles() {
​wp_enqueue_style('base', get_template_directory_uri() . '/style.css');
}
add_action( 'wp_enqueue_scripts', 'theme_styles');

?>