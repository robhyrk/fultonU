<?php

    get_header();

    while(have_posts() ) :
        the_post(); ?>
    <div class="page-banner">
        <div class="page-banner__bg-image" style="background-image: url(<?php echo get_theme_file_uri('images/ocean.jpg')?>);"></div>
            <div class="page-banner__content container container--narrow">
                <h1 class="page-banner__title"><?php the_title();?></h1>
                <div class="page-banner__intro">
                <p>Replace Later</p>
            </div>
        </div>  
    </div>

    <div class="container container--narrow page-section">
        <div class="metabox metabox--position-up metabox--with-home-link">
            <p><a class="metabox__blog-home-link" href="<?php echo get_post_type_archive_link('program'); ?>">
            <i class="fa fa-home" aria-hidden="true"></i> Programs Home</a> 
            <span class="metabox__main"><?php the_title();?></span></p>
        </div>

        <div class="generic-content"><?php the_content();?></div>
        
    <?php
    //Custom Query to Show Related Instructors
    $relatedInstructors = new WP_Query(array(
        'post_type' => 'instructor',
        'posts_per_page' => -1,
        'order_by' => 'title',
        'order' => 'ASC',
        //Filters query
        'meta_query' => array(
            array(
                'key' => 'related_programs',
                'compare' => 'LIKE',
                'value' => '"' . get_the_id() . '"'
                )
        )
    ));

    if($relatedInstructors->have_posts()) :
        echo '<hr class="section-break">';
        echo '<h2 class="headline headline--medium">' . get_the_title() . ' Professors</h2>';
        echo '<ul class="professor-cards">';
        while($relatedInstructors->have_posts()) :
            $relatedInstructors->the_post();?>
        
            <li class="professor-card__list-item">
            
                <a class="professor-card" href="<?php the_permalink();?>">
                    <img class="professor-card__image" src="<?php the_post_thumbnail_url('instructorLandscape')?>">
                    <span class="professor-card__name"><?php the_title();?></span>

                </a>
            
            </li>
        <?php 
        echo '</ul>';
        endwhile; 
        endif;
        wp_reset_postdata();

          $today = date('Ymd');
          $homepageEvents = new WP_Query(array(
            'post_type' => 'event',
            'posts_per_page' => 2,
            'meta_key' => 'event_date',
            'order_by' => 'meta_value_num',
            //Filters query
            'meta_query' => array(
              array(
                'key' => 'event_date',
                'compare' => '>=',
                'value' => $today,
                'type' => 'numeric'
              ),
              array(
                  'key' => 'related_programs',
                  'compare' => 'LIKE',
                  'value' => '"' . get_the_id() . '"'
              )
            )
          ));

    //Custom Query to Show Related Events
    if($homepageEvents->have_posts()) :
            echo '<hr class="section-break">';
            echo '<h2 class="headline headline--medium">Upcoming ' . get_the_title() . ' Events</h2>';

            while($homepageEvents->have_posts()) :
                $homepageEvents->the_post();
                get_template_part('template-parts/content', 'event');
            endwhile; 
            endif;
            wp_reset_postdata();

            ?>

        </div>
  
        
    <?php endwhile;

    get_footer();
?>