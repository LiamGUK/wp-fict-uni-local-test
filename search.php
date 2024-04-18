<?php
  // search.php file to control rendering of page content when a user visits a page with the search parameter included in URL - DOMAIN/s=PARAM
  // If file doesn't exist WP will use index.php to render content
?>

<?php
  get_header();

  // pageBanner method used to render page banner on page - pass in array of options to customise text displayed
  // use get_search_query method to add URL param value to concat with text - will auto escape characters of param value as a security measure - wrap inside esc_html method to further enhance security and prevent XXS attacks
  pageBanner(array(
    'title' => 'Search Results',
    'subtitle' => 'You searched for &ldquo;' . esc_html(get_search_query()) . '&rdquo;'
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
      // Use if statement to check if search result returns a post from the while loop, if query doesn't match with any post type to echo out text in else block
      if(have_posts()){
        while(have_posts()){
        the_post();

          // Use template part to import in block of code to use inside this file - first argument is path of file with start of file name, 2nd argument use get_post_type method for WP to use while loop to grab current post type in loop and use to pick out same template file name
          get_template_part('template-parts/content', get_post_type());

          // This will add pagination links to load more posts that go beyond the max number of posts WP can show - will add links to change to a different page and will load more posts - by default WP will load 10 posts in the while loop, can increase or decrease by changing the setting in the Reading settings in WP admin
          echo paginate_links();
        }
      } else {
        echo '<h2 class="headline headline--small-plus">No results match that search.</h2>';
      }

      // WP method that looks for file called searchform.php - will pull in HTML form elements into this php file
      get_search_form();
    ?>

  </div>

<?php
  get_footer();
?>
