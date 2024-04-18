<?php
  // To create pages for custom post types in WP need to add a file called single- + custom post type name.php
  // This php file will handle the rendering of custom post types of events
?>

<?php
  get_header();

  // pageBanner method used to render page banner on page - pass in array of options to customise text displayed
  pageBanner();
?>

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
          <a class="metabox__blog-home-link" href="<?php echo get_post_type_archive_link('event'); // gets the custom post type url - uses value passed into method ?>"><i class="fa fa-home" aria-hidden="true"></i> Events Home</a>
          <span class="metabox__main">
           <?php the_title(); ?>
          </span>
        </p>
      </div>

      <div class="generic-content">
        <?php the_content(); ?>
      </div>

      <?php
        // Advanced custom fields plugin gives access to get_field method to return a custom field set on post in current loop iteration - pass in field name wanting to retrieve
        $relatedPrograms = get_field('related_programs');
        // If wanting to check what get_field method returns can print using print_r function to check whats included
        // print_r($relatedPrograms);
        // var_dump does the same and includes data types
        // var_dump($relatedPrograms);

        // Only run the below loop if the relatedProgram has field name assigned to it
        if($relatedPrograms) {
          echo '<hr class="section-break">';
          echo '<h2 class="headline headline--medium">Related Program(s)</h2>';
          echo "<ul class='link-list min-list'>";
          foreach($relatedPrograms as $program){ ?>
            <li><a href="<?php echo get_the_permalink($program); ?>"><?php echo get_the_title($program); ?></a></li>
        <?php
          }
          echo "</ul>";
        }
      ?>
    </div>

    <?php // Exit php mode here to add HTML inside while loop ?>

  <?php
    // Add new php block here to add closing bracket (}) to end while loop
  }
?>

<?php get_footer(); ?>
