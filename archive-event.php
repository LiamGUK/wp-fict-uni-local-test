<?php
  // Separate archive file to use with custom post types
  // File needs to be created as archive- + name of custom post type.php
  // Controls the rendering of custom post type list pages
?>

<?php
  get_header();

  // pageBanner method used to render page banner on page - pass in array of options to customise text displayed - (logic in functions.php file)
  pageBanner(array(
    'title' => 'All Events',
    'subtitle' => 'See whats going on in our world'
  ));
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
      while(have_posts()){
        the_post();

        // get_template_part method will look for specific template files and pull in code block
        // 1st argument is name of folder in theme folder holding files with / + name of file
        // 2nd argument is optional and appends a dash with name in 2nd argument included - below would look for file content-event.php
        get_template_part('template-parts/content', 'event');
      }

      // This will add pagination links to load more posts that go beyond the max number of posts WP can show - will add links to change to a different page and will load more posts - by default WP will load 10 posts in the while loop, can increase or decrease by changing the setting in the Reading settings in WP admin
      echo paginate_links();
    ?>
    <hr class="section-break">
    <p>Looking for a recap of past events? <a href="<?php echo site_url('/past-events'); ?>">Check out our past events archive</a></p>
  </div>

<?php
  get_footer();
?>
