<?php
  // template part file to render below block in search.php file inside while loop - will pull block in dynamically using get_post_type method inside get_template_part method
?>

<div class="post-item">
  <h2 class="headline headline--medium headline--post-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>

  <div class="metabox">
    <p>Posted by <?php the_author_posts_link(); // Pulls in the profile name of user who created post (Set a nickname and choose to display publicly using nickname to render on page rather than username) ?> on <?php the_time('j-n-y'); // Pass in string to specify outputted date format ?> in <?php echo get_the_category_list(', '); ?></p>
  </div>

  <div class="generic-content">
    <?php
    // the_excerpt method only pulls the first paragraph of text from post
    the_excerpt();
  ?>
    <p><a class="btn btn--blue" href="<?php the_permalink(); ?>">Continue Reading &raquo;</a></p>
  </div>
</div>
