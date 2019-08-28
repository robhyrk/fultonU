<?php 

function uni_post_types() {
    //Event Post Type
    register_post_type('campus', array(
        //Allows custom user role capabilties via members plugin
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

    // Post Type
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
}

add_action('init', 'uni_post_types');

?>