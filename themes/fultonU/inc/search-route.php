<?php 

function uniSearch() {
    register_rest_route('uni/v1', 'search', array(
        'methods' => WP_REST_SERVER::READABLE,
        'callback' => 'uniSearchResults'
    ));
}

add_action('rest_api_init', 'uniSearch');

function uniSearchResults($data) {
    $mainSearch = new WP_Query(array(
        'post_type' => array('instructor', 'page', 'post', 'program', 'campuses', 'event'),
        's' => sanitize_text_field($data['term'])
    ));

    $results = array(
        'general' => array(),
        'instructors' => array(),
        'programs' => array(),
        'events' => array(),
        'campuses' => array()
    );

    while($mainSearch->have_posts()) :
        $mainSearch->the_post();

        if (get_post_type() == 'post' || get_post_type() == 'page' ) :

        array_push($results['general'], array(
            'name' => get_the_title(),
            'url' => get_the_permalink()
        ));
    endif;

    if (get_post_type() == 'instructor' ) :

        array_push($results['instructors'], array(
            'name' => get_the_title(),
            'url' => get_the_permalink()
        ));
    endif;

    if (get_post_type() == 'program' ) :

        array_push($results['programs'], array(
            'name' => get_the_title(),
            'url' => get_the_permalink()
        ));
    endif;

    if (get_post_type() == 'event' ) :

        array_push($results['events'], array(
            'name' => get_the_title(),
            'url' => get_the_permalink()
        ));
    endif;

    if (get_post_type() == 'campuses' ) :

        array_push($results['campuses'], array(
            'name' => get_the_title(),
            'url' => get_the_permalink()
        ));
    endif;

    endwhile;

    return $results;
}
