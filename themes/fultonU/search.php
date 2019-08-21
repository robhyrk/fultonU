<?php 

  get_header();
  pageBanner(array(
    'title' => 'Welcome to our Blog',
    'subtitle' => 'Latest News'
  ));
  ?>

<div class="container container--narrow page-section">
<?php
  if (have_posts()) :
    while(have_posts()) :
      the_post(); 
      get_template_part('template-parts/content', get_post_type());
      echo paginate_links();
    endwhile;
    else : 

    echo '<h2 class="headline headline--small-plus">No results match that serach</h2>';

  endif; 

  get_search_form();?>

</div>

<?php

  get_footer();

?>