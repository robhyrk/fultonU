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

add_action('after_setup_theme', 'uni_features');

//Filters event archive page to show dates in ascending order that haven't passed
function uni_adjust_queries($query) {
    $today = date('Ymd');
    if(!is_admin() && is_post_type_archive('event') && $query->is_main_query() ){
        $query->set('meta_key', 'event_date');
        $query->set('orderby', 'meta_value_num');
        $query->set('order', 'ASC');
        $query->set('meta_query', array(
            array(
              'key' => 'event_date',
              'compare' => '>=',
              'value' => $today,
              'type' => 'numeric'
            )
            ));
    }
}

add_action('pre_get_posts', 'uni_adjust_queries')

?>