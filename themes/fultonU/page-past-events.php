<?php 

get_header();
pageBanner(array(
  'title' => 'Past Events',
  'subtitle' => 'Recap of past events'
));
?>

<div class="container container--narrow page-section">
<?php

$today = date('Ymd');
    $pastEvents = new WP_Query(array(
    'post_type' => 'event',
    'paged' => get_query_var('paged', 1),
    'posts_per_page' => 1,
    'meta_key' => 'event_date',
    'order_by' => 'meta_value_num',
    'order' => 'ASC',
    'meta_query' => array(
        array(
        'key' => 'event_date',
        'compare' => '<=',
        'value' => $today,
        'type' => 'numeric'
        )
    )
    ));

while($pastEvents->have_posts()) :
    $pastEvents->the_post(); 
    get_template_part('template-parts/content', 'event');
endwhile;
echo paginate_links(array(
    'total' => $pastEvents->max_num_pages
));
?>

</div>

<?php

get_footer();

?>