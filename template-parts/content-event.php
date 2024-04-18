<?php
  // get_template_part code - use if wanting to recycle blocks of HTML that doesn't require to pass in parameters to customise content (HTML content remains the same) use template parts rather than custom functions to share code blocks
?>

<div class="event-summary">
  <a class="event-summary__date t-center" href="#">
    <?php
        // new DateTime() = creates a new date object in PHP (not passing in an argument returns the current date/time stamp)
        // pass in get_field method (from advanced custom field plugin) to use date set in WP admin to return a timestamp to format date in way required
        $eventDate = new DateTime(get_field('event_date'));
      ?>
    <span class="event-summary__month">
      <?php echo $eventDate->format('M'); // This will format date object to render just the month in 3 letters uppercase ?>
    </span>
    <span class="event-summary__day">
      <?php echo $eventDate->format('d'); // This will format date object to render just the day number ?>
    </span>
  </a>
  <div class="event-summary__content">
    <h5 class="event-summary__title headline headline--tiny">
      <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
    </h5>
    <p>
      <?php
      // If post/page as a custom excerpt added in the page editor screen in WP admin can use built excerpt method to print it on page rather than trimming text
      // Use if check to test if post has an excerpt added in WP admin can then use excerpt method otherwise defaults to use trim method if no excerpt set
      if(has_excerpt()){
        // the_excerpt(); -- the_excerpt handles content and styling of block - can use below get_the_excerpt method to just pull the text in (need to echo)
        echo get_the_excerpt();
      } else {
        echo wp_trim_words(get_the_content(), 18);
      } ?>
      <a href="<?php the_permalink(); ?>" class="nu gray">Learn more</a>
    </p>
  </div>
</div>
