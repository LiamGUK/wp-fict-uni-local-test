<?php
  // template part file to render below block in search.php file inside while loop - will pull block in dynamically using get_post_type method inside get_template_part method
?>

<div class="post-item">
  <li class="professor-card__list-item">
    <a class="professor-card" href="<?php the_permalink(); ?>">
      <img class="professor-card__image" src="<?php the_post_thumbnail_url('professorLandscape'); // will use the custom image landscape size set in functions.php ?>" alt="">
      <span class="professor-card__name"><?php the_title(); ?></span>
    </a>
  </li>
</div>
