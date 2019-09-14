<?php 

require get_theme_file_path('/inc/search-route.php');

//Add custom fields to JSON API data
function uni_custom_rest() {
    register_rest_field('post', 'authorName', array(
        'get_callback' => function(){return get_the_author();}
    ));
}

add_action('rest_api_init', 'uni_custom_rest');

function uni_files() {
    wp_enqueue_script('google-map', "https://maps.googleapis.com/maps/api/js?key=AIzaSyAi-xL3a_LFjXaxiOtUOGsFvI2eFDERBQg", NULL, 1.0, true);
    wp_enqueue_script('main_uni_js', get_theme_file_uri('/js/scripts-bundled.js'), array('jquery'), microtime(), true);

    wp_enqueue_style('uni_main_styles', get_stylesheet_uri(), NULL, microtime());
    wp_enqueue_style('font-awesome', "https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css");
    wp_enqueue_style('google-fonts', "https://fonts.googleapis.com/css?family=Roboto+Condensed:300,300i,400,400i,700,700i|Roboto:100,300,400,400i,700,700i");

    wp_localize_script('main_uni_js', 'uni_data', array(
        'root_url' => get_site_url()
    ));
}

add_action('wp_enqueue_scripts', 'uni_files');

function uni_features() {
    register_nav_menu('headerMenuLocation', 'Header Menu Location');
    register_nav_menu('footerLocation1', 'Footer Location 1');
    register_nav_menu('footerLocation2', 'Footer Location 2');
    add_theme_support('title-tag');
    add_theme_support('post-thumbnails');
    add_image_size('pageBanner', 1500, 350, true);
    add_image_size('instructorLandscape', 400, 260, true);
    add_image_size('instructorPortriat', 480, 650, true);

}

add_action('after_setup_theme', 'uni_features');

//Filters event archive page to show dates in ascending order that haven't passed
function uni_adjust_queries($query) {
    if(!is_admin() && is_post_type_archive('campus') && $query->is_main_query()) {
        $query->set('posts_per_page', -1);
    }
    
    if(!is_admin() && is_post_type_archive('program') && $query->is_main_query()) {
        $query->set('orderby', 'title');
        $query->set('order', 'ASC');
        $query->set('posts_per_page', -1);
    }


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
add_action('pre_get_posts', 'uni_adjust_queries');

function uni_mapKey($api) {
    $api['key'] = 'AIzaSyAi-xL3a_LFjXaxiOtUOGsFvI2eFDERBQg';
    return $api;
}
add_filter('acf/fields/google_map/api', 'uni_mapKey');

//Custom function to dynamically add page banner content
function pageBanner($args = NULL) {
if(!$args['title']) {
    $args['title'] = get_the_title();
}

if (!$args['subtitle']) {
    $args['subtitle'] = get_field('page_banner_subtitle');
}

if (!$args['photo']) {
   if (get_field('page_banner_background_image')) {
       $args['photo'] = get_field('page_banner_background_image')['sizes']['pageBanner'];
   } else {
       $args['photo'] = get_theme_file_uri('/images/ocean.jpg');
   }
}

?>
<div class="page-banner">
        <div class="page-banner__bg-image" 
            style="background-image: url(<?php echo $args['photo']; ?>)">
        </div>
            <div class="page-banner__content container container--narrow">
                <h1 class="page-banner__title"><?php echo $args['title']; ?></h1>
                <div class="page-banner__intro">
                <p><?php echo $args['subtitle'];?></p>
            </div>
        </div>  
    </div>
    <?php
}

//redirect subscriber accounts out of action and onto homepage

function redirectSubsToFrontend() {
    $ourCurrentUser = wp_get_current_user();

    if (count($ourCurrentUser->roles) == 1 AND $ourCurrentUser->roles[0] =='subscriber'):
        wp_redirect(site_url('/'));
        exit;
    endif;
}

add_action('admin_init', 'redirectSubsToFrontend');

function noSubsAdminBar() {
    $ourCurrentUser = wp_get_current_user();

    if (count($ourCurrentUser->roles) == 1 AND $ourCurrentUser->roles[0] =='subscriber'):
        show_admin_bar(false);
    endif;
}

add_action('wp_loaded', 'noSubsAdminBar');

//change login screen stuff
function headerURL() {
    return esc_url(site_url('/'));
}
add_filter('login_headerurl', 'headerURL');

function ourLoginLogo() {
  return get_bloginfo('name');
}
add_filter('login_headertitle', 'ourLoginLogo');

function loginCSS() {
    wp_enqueue_style('uni_main_styles', get_stylesheet_uri(), NULL, microtime());
}
add_action('login_enqueue_scripts', 'loginCSS');

//move to mu-plugins after development
function uni_post_types() {
    //Event Post Type
    register_post_type('campus', array(
        'capability_type' => 'campus',
        'map_meta_cap' => true,
        'has_archive' => true,
        'show_in_rest' => true,
        'supports' => array('title', 'editor', 'excerpt'),
        'rewrite' => array('slug' => 'campuses'),
        'public' => true,
        'labels' => array(
            'name' => 'Campuses',
            'add_new_item' => 'Add New Campus',
            'edit_item' => 'Edit Event',
            'all_items' => 'All Campuses',
            'singular_name' => 'Campus'
        ),
        'menu_icon' => 'dashicons-location-alt'
    ));

    //Event Post Type
    register_post_type('event', array(
        'capability_type' => 'event',
        'map_meta_cap' => true,
        'has_archive' => true,
        'show_in_rest' => true,
        'supports' => array('title', 'editor', 'excerpt'),
        'rewrite' => array('slug' => 'events'),
        'public' => true,
        'labels' => array(
            'name' => 'Events',
            'add_new_item' => 'Add New Event',
            'edit_item' => 'Edit Event',
            'all_items' => 'All Events',
            'singular_name' => 'Event'
        ),
        'menu_icon' => 'dashicons-calendar'
    ));

    //Program Post Type
    register_post_type('program', array(
        'has_archive' => true,
        'show_in_rest' => true,
        'supports' => array('title'),
        'rewrite' => array('slug' => 'programs'),
        'public' => true,
        'labels' => array(
            'name' => 'Programs',
            'add_new_item' => 'Add New Program',
            'edit_item' => 'Edit Program',
            'all_items' => 'All Programs',
            'singular_name' => 'Program'
        ),
        'menu_icon' => 'dashicons-awards'
    ));

    // Instructor Post Type
    register_post_type('instructor', array(
        'show_in_rest' => true,
        'supports' => array('title', 'editor', 'thumbnail'),
        'public' => true,
        'labels' => array(
            'name' => 'Instructors',
            'add_new_item' => 'Add New Instructor',
            'edit_item' => 'Edit Instructor',
            'all_items' => 'All Instructors',
            'singular_name' => 'Instructor'
        ),
        'menu_icon' => 'dashicons-welcome-learn-more'
    ));

    // Note Post Type
    register_post_type('note', array(
        'show_in_rest' => true,
        'supports' => array('title', 'author', 'editor'),
        'public' => false,
        'show_ui' => true,
        'labels' => array(
            'name' => 'Notes',
            'add_new_item' => 'Add New Note',
            'edit_item' => 'Edit Note',
            'all_items' => 'All Notes',
            'singular_name' => 'Note'
        ),
        'menu_icon' => 'dashicons-welcome-write-blog'
    ));
}

add_action('init', 'uni_post_types');

?>