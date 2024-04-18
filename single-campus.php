<?php
  // single-campus.php used as a template file for the program post type created in university_post_type.php file in plugins folder
  // WP will use this template file to render content for these post types only
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
          <a class="metabox__blog-home-link" href="<?php echo get_post_type_archive_link('campus'); // gets the custom post type url - uses value passed into method ?>"><i class="fa fa-home" aria-hidden="true"></i> All Campuses</a>
          <span class="metabox__main">
           <?php the_title(); ?>
          </span>
        </p>
      </div>

      <div class="generic-content">
        <?php the_content(); ?>
      </div>

      <div class="acf-map">
    <?php
        // use get_field method from advanced custom fields to grab google map info set inside custom post type page in WP editor
        $mapLocation = get_field('map_location');
      ?>

        <div class="marker" data-lat="<?php echo $mapLocation['lat'] ?>" data-lng="<?php echo $mapLocation['lng'] ?>">
          <h3><?php the_title(); ?></h3>
          <?php echo $mapLocation['address']; ?>
        </div>

        <?php
          /*
           <li>
            <a href="<?php the_permalink(); ?>">
              <?php
              the_title();
              $mapLocation = get_field('map_location');
              echo $mapLocation['lat'];
              ?>
            </a>
          </li>
          */
        ?>

    </div>

      <?php
        $relatedPrograms = new WP_Query(array(
              // associative array requests the maximum number of posts to grab (2 posts) and to only grab the post_type of event (setting -1 will show all)
              'posts_per_page' => -1,
              'post_type' => 'program',
              'orderby' => 'title',
              // order will control direction of sort (default is DESC - highest to lowest)
              'order' => 'ASC',
              'meta_query' => array(
                // in meta_query pass in an array which will be used to compare conditions to run and display a condition to render a sorted item
                array(
                  'key'=> 'related_campus',
                  'compare' => 'LIKE',
                  'value' => '"' . get_the_ID() . '"' //Sets the value of ID method and concats into a string when array gets serialized in Database
                )
              )
        ));

        // Will only run the below while loop and render content if there is event linked to program
        if($relatedPrograms->have_posts()){
          echo '<hr class="section-break">';
          echo '<h2 class="headline headline--medium">Programs available at this campus</h2>';

          echo '<ul class="min-list link-list">';
          while($relatedPrograms->have_posts()){
            $relatedPrograms->the_post(); ?>
            <li>
              <a href="<?php the_permalink(); ?>">
                <?php the_title(); ?>
              </a>
            </li>
        <?php
          }
          echo '</ul>';
        }

        // below resets the global posts object back to the default URL query - will allow the below custom query to run like normal
        wp_reset_postdata();
      ?>

    </div>

    <?php // Exit php mode here to add HTML inside while loop ?>

  <?php
    // Add new php block here to add closing bracket (}) to end while loop
  }
?>

<?php get_footer(); ?>
