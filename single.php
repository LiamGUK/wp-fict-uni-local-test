<?php
  /*
    When adding a link to a post in the index.php file that points to a created post in WP will update URL and display post content (if also using the_content method)
  Adding a php file called single.php -> when clicking hyperlink of post will render content added to this file instead

  index.php file = for homepage or blog listing page
  single.php = for individual posts only
  */
?>

<?php
  get_header();

  // pageBanner method used to render page banner on page - pass in array of options to customise text displayed
  pageBanner();
?>

<?php // Adding posts to a individual page  ?>

<?php
  // Built in WP method have_posts - returns a boolean value
  // If there are posts available returns true, when there aren't any posts left = returns false
  while(have_posts()) {
    // the_post method grabs the current page in loop and then iterates the post index value (don't need to create separate count variable and increment)
    the_post(); ?>

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
      <div class="metabox metabox--position-up metabox--with-home-link">
        <p>
          <a class="metabox__blog-home-link" href="<?php echo site_url('/blog'); // Site URL is absolute - will point to blog listing page ?>"><i class="fa fa-home" aria-hidden="true"></i> Blog Home</a>
          <span class="metabox__main">
            Posted by <?php the_author_posts_link(); // Pulls in the profile name of user who created post (Set a nickname and choose to display publicly using nickname to render on page rather than username) ?> on <?php the_time('j-n-y'); // Pass in string to specify outputted date format ?> in <?php echo get_the_category_list(', '); ?>
          </span>
        </p>
      </div>

      <div class="generic-content">
        <?php the_content(); ?>
      </div>
    </div>

    <?php // Exit php mode here to add HTML inside while loop ?>

  <?php
    // Add new php block here to add closing bracket (}) to end while loop
  }
?>

<?php get_footer(); ?>
