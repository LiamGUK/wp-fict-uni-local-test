<?php
  // archive-program template file used for post list of new post type created in university_post_type file in plugins
  // Create file name with archive- + post type name + .php and will render content from here otherwise will default to pull content from archive.php template file
?>

<?php
  get_header();

  // pageBanner method used to render page banner on page - pass in array of options to customise text displayed
  pageBanner(array(
    'title' => 'All Programs',
    'subtitle' => 'There is something for everyone, have a look around.'
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

    <ul class="link-list min-list">
    <?php
      while(have_posts()){
        the_post(); ?>

        <li><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></li>
    <?php
      }

      // This will add pagination links to load more posts that go beyond the max number of posts WP can show - will add links to change to a different page and will load more posts - by default WP will load 10 posts in the while loop, can increase or decrease by changing the setting in the Reading settings in WP admin
      echo paginate_links();
    ?>
    </ul>

  </div>

<?php
  get_footer();
?>
