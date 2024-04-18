<?php
  // page-past-events file is for custom page past events
  // for any custom page need to name file page- + url slug of page + .php
?>

<?php
  get_header();

  // pageBanner method used to render page banner on page - pass in array of options to customise text displayed
  pageBanner(array(
    'title' => 'Past Events',
    'subtitle' => 'A recap of our past events'
  ))
?>

  <?php
    /*
      LOGIC MOVED TO FUNCTIONS.PHP FILE - BANNER CONTENT GENERATED THERE
        <div class="page-banner">
          <div class="page-banner__bg-image" style="background-image: url(<?php //echo get_theme_file_uri('/images/ocean.jpg') ?>)"></div>
          <div class="page-banner__content container container--narrow">
            <h1 class="page-banner__title">
            All Programs
            </h1>
            <div class="page-banner__intro">
              <p>
                There is something for everyone, have a look around.
              </p>
            </div>
          </div>
      </div>
      */
    ?>

  <div class="container container--narrow page-section">
    <?php
      $today = date('Ymd');
      // custom query - create new instance of WP_Query class so can pass in info of what data we want to query in database
      $pastEvents = new WP_Query(array(
        // paged key sets instructions on what page custom query should be rendered - use get_query_var method to retrieve info of page URL pass in paged string to grab current page number displayed in URL slug to be used to render post in pagination link, add 1 as a 2nd argument as a fall back value if no page number found
        'paged' => get_query_var('paged', 1),
        'post_type' => 'event',
        'meta_key' => 'event_date',
        // orderby allows to sort list in certain order (default is title - will sort by title in alphabetical order, rand = random order on each load)
        // meta_value = want to order by custom metadata value or custom field stated from meta_key above
        'orderby' => 'meta_value_num',
        // order will control direction of sort (default is DESC - highest to lowest)
        'order' => 'ASC',
        'meta_query' => array(
          // in meta_query pass in an array which will be used to compare conditions to run and display a condition to render a sorted item
           array(
            // key is meta_key wanting to check against
            'key' => 'event_date',
            // compare is comparison operator to use in comparison
             'compare' => '<',
            // value is value to check against - this case checking if event_date value is less than today's date - if so will display sorted item on page
            'value' => $today,
            // type key tells WP data type checking against - this case a number value
            'type' => 'numeric'
          )
        )
      ));

      while($pastEvents->have_posts()){
        $pastEvents->the_post();

         // get_template_part method will look for specific template files and pull in code block
        // 1st argument is name of folder in theme folder holding files with / + name of file
        // 2nd argument is optional and appends a dash with name in 2nd argument included - below would look for file content-event.php
        get_template_part('template-parts/content', 'event');
      }

      // This will add pagination links to load more posts that go beyond the max number of posts WP can show - will add links to change to a different page and will load more posts - by default WP will load 10 posts in the while loop, can increase or decrease by changing the setting in the Reading settings in WP admin
      echo paginate_links(array(
        'total'=> $pastEvents->max_num_pages
      ));
    ?>
  </div>

<?php
  get_footer();
?>
