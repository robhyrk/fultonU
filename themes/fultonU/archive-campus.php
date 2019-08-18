<?php 

  get_header();
  pageBanner(array(
    'title' => 'Our Campuses',
    'subtitle' => 'Come in and see what we\'re about'
  ));
  ?>

<div class="container container--narrow page-section">

  <div class="acf-map">
    <?php
      while(have_posts()) :
        the_post();
        $mapLocation = get_field("map_location");?>
            <div data-lat="<?php echo $mapLocation['lat'];?>" data-lng="<?php echo $mapLocation['lng'];?>" class="marker">
              <a href="<?php the_permalink();?>"><?php the_title();?></a>
              <?php echo $mapLocation['address'];?>
            </div>
    <?php endwhile; ?>
  </div>

</div>

<?php

  get_footer();

?>