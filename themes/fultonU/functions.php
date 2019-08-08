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
    add_theme_support('post-thumbnails');
    add_image_size('pageBanner', 1500, 350, true);
    add_image_size('instructorLandscape', 400, 260, true);
    add_image_size('instructorPortriat', 480, 650, true);

}

add_action('after_setup_theme', 'uni_features');

//Filters event archive page to show dates in ascending order that haven't passed
function uni_adjust_queries($query) {
    if(!is_admin() && is_post_type_archive('program') && $query->is_main_query()) {
        $query->set('orderby', 'title');
        $query->set('order', 'ASC');
        $query->set('posts_per_age', -1);
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

?>