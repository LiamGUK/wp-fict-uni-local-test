<?php
  // index.php controls the rendering of blog listing pages - if Reading settings updated in WP admin to homepage displaying a static page and blog pages set to posts page, blog pages controlled in this file and homepage in front-page.php
?>

<?php
  get_header();

  // pageBanner method used to render page banner on page - pass in array of options to customise text displayed
  pageBanner(array(
    'title' => 'Welcome to our blog!',
    'subtitle' => 'Keep up with our latest news'
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
      while(have_posts()){
        the_post(); ?>
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
    <?php
      }

      // This will add pagination links to load more posts that go beyond the max number of posts WP can show - will add links to change to a different page and will load more posts - by default WP will load 10 posts in the while loop, can increase or decrease by changing the setting in the Reading settings in WP admin
      echo paginate_links();
    ?>
  </div>

<?php
  get_footer();
?>
