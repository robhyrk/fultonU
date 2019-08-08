<?php 

  get_header();
  pageBanner(array(
    'title' => 'All Programs',
    'subtitle' => 'What are you interested in learning?'
  ));
  ?>

<div class="container container--narrow page-section">

<ul class="link-list main-list">
<?php
  while(have_posts()) :
    the_post(); ?>
        <li><a href="<?php the_permalink();?>"><?php the_title();?></a></li>
<?php
  endwhile;
  echo paginate_links();
?>
</ul>

</div>

<?php

  get_footer();

?>