<?php 

function uni_files() {
    wp_enqueue_script('main_uni_js', get_theme_file_uri('/js/scripts-bundled.js'), NULL, microtime(), true);
    
    wp_enqueue_style('uni_main_styles', get_stylesheet_uri(), NULL, microtime());
    wp_enqueue_style('font-awesome', "https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css");
    wp_enqueue_style('google-fonts', "https://fonts.googleapis.com/css?family=Roboto+Condensed:300,300i,400,400i,700,700i|Roboto:100,300,400,400i,700,700i");

}

add_action('wp_enqueue_scripts', 'uni_files');

function uni_features() {
    register_nav_menu('headerMenuLocation', 'Header Menu Location');
    register_nav_menu('footerLocation1', 'Footer Location 1');
    register_nav_menu('footerLocation2', 'Footer Location 2');
    add_theme_support('title-tag');
}

add_action('after_setup_theme', 'uni_features')
;
?>