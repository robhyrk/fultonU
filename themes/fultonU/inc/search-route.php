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
        'post_type' => array('instructor', 'page', 'post', 'program', 'campus', 'event'),
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
            'postType' => get_post_type(),
            'title' => get_the_title(),
            'url' => get_the_permalink(),
            'authorName' => get_the_author()
        ));
    endif;

    if (get_post_type() == 'instructor' ) :

        array_push($results['instructors'], array(
            'name' => get_the_title(),
            'url' => get_the_permalink(),
            'img' => get_the_post_thumbnail_url(0, 'instructorLandscape')
        ));
    endif;

    if (get_post_type() == 'program' ) :

        array_push($results['programs'], array(
            'name' => get_the_title(),
            'url' => get_the_permalink(),
            'id' => get_the_id()
        ));
    endif;

    if (get_post_type() == 'event' ) :
        $eventDate = new DateTime(get_field('event_date'));
        $desc = NULL;
        if(has_excerpt()) :
            $desc = get_the_excerpt();
        else :
            $desc = wp_trim_words(get_the_content(), 20);
        endif;
        
        array_push($results['events'], array(
            'name' => get_the_title(),
            'url' => get_the_permalink(),
            'month' => $eventDate->format('M'),
            "day" => $eventDate->format('d'),
            'desc' => $desc
        ));
    endif;

    if (get_post_type() == 'campus' ) :

        array_push($results['campuses'], array(
            'name' => get_the_title(),
            'url' => get_the_permalink()
        ));
    endif;

    endwhile;

    if($results['programs']) :
            $programsMetaQuery = array('relation' => 'OR');

        foreach($results['programs'] as $item) :
            array_push($programsMetaQuery, array(
                'key' => 'related_programs',
                'compare' => 'LIKE',
                'value' => '"'. $item['id'] . '"'
            ));
        endforeach;

        $programRel = new WP_Query(array(
            'post_type' => 'instructor',
            'meta_query' => $programsMetaQuery
        ));

        while($programRel->have_posts()) :
            $programRel->the_post();
            if (get_post_type() == 'instructor' ) :

                array_push($results['instructors'], array(
                    'name' => get_the_title(),
                    'url' => get_the_permalink(),
                    'img' => get_the_post_thumbnail_url(0, 'instructorLandscape')
                ));
            endif;
        endwhile;

        $results['instructors']  = array_values(array_unique($results['instructors'], SORT_REGULAR));
    endif;
    

    return $results;
}
