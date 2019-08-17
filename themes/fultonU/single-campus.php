<?php

    get_header();
    
    while(have_posts() ) :
        the_post(); 
        pageBanner();?>


    <div class="container container--narrow page-section">
    <div class="metabox metabox--position-up metabox--with-home-link">
        <p><a class="metabox__blog-home-link" href="<?php echo get_post_type_archive_link('campus'); ?>">
        <i class="fa fa-home" aria-hidden="true"></i> All Campuses</a> 
        <span class="metabox__main"><?php the_title();?></span></p>
      </div>

        <div class="generic-content"><?php the_content();?></div>
        <div class="acf-map">
            <?php
            $mapLocation = get_field("map_location");?>
                <div data-lat="<?php echo $mapLocation['lat'];?>" data-lng="<?php echo $mapLocation['lng'];?>" class="marker">
                <?php the_title();?>
                <?php echo $mapLocation['address'];?>
                </div>
            </div>
    <?php endwhile;
        //Custom Query to Show Related Instructors
        $relatedPrograms = new WP_Query(array(
            'post_type' => 'program',
            'posts_per_page' => -1,
            'order_by' => 'title',
            'order' => 'ASC',
            //Filters query
            'meta_query' => array(
                array(
                    'key' => 'related_campuses',
                    'compare' => 'LIKE',
                    'value' => '"' . get_the_id() . '"'
                    )
            )
        ));
        if($relatedPrograms->have_posts()) :
            echo '<hr class="section-break">';
            echo '<h2 class="headline  headline--medium">Programs Available at this Campus</h2>';
            echo '<ul class="min-list link-list">';
            while($relatedPrograms->have_posts()) :
                $relatedPrograms->the_post();?>
            
                <li>          
                    <a href="<?php the_permalink();?>"><?php the_title();?></a>  
                </li>
           <?php endwhile;
           echo '</ul>';
           ?>
        
<?php 
    endif;
    get_footer();
?>