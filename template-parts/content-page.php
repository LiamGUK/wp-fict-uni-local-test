<?php
  // template part file to render below block in search.php file inside while loop - will pull block in dynamically using get_post_type method inside get_template_part method
?>

<div class="post-item">
  <h2 class="headline headline--medium headline--post-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>

  <div class="generic-content">
    <?php
    // the_excerpt method only pulls the first paragraph of text from post
    the_excerpt();
  ?>
    <p><a class="btn btn--blue" href="<?php the_permalink(); ?>">Continue Reading &raquo;</a></p>
  </div>
</div>
