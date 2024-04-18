<?php
  // single-professor.php file created to handle rendering of new post type content professors
  // pages created under the professor post type will look for content here rather than the default single.php file
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
    the_post();
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

      <div class="generic-content">
        <div class="row group">
          <div class="one-third">
            <?php
              // the_post_thumbnail method will auto pull in featured image set in WP admin for current post type in loop (will use default image version) - pass in name of custom image size set in functions.php for method to use custom sized image
              the_post_thumbnail('professorPortrait');
            ?>
          </div>
          <div class="two-thirds">
            <?php
              $likeCount = new WP_Query(array(
                // Need to use meta_query in this custom query as like post ID needs to match the professor post page ID being viewed
                'post_type' => 'like',
                'meta_query' => array(
                  // need to use inner array in meta_query as acts as a filter to data required to be pulled
                  array(
                    // key is field value looking for in query - field created in advanced custom field plugin
                    'key' => 'liked_professor_id',
                    // compare is comparison operator used for field - '=' is looking for matching value
                    'compare' => '=',
                    // value is what works with comparison operator - professor id key above needs to match/equal to current post ID of page currently viewing
                    'value' => get_the_ID()
                  )
                )
              ));

              $existStatus = 'no';

              if(is_user_logged_in()) {
                // Need to wrap this custom query in an if check when using the get_current_user_id method due to if current_user_id is equal to 0 from not being logged in will be as if author key won't exist inside query - existsStatus variable will then still update to 'yes' below and still update data attribute on like-box element
                $existQuery = new WP_Query(array(
                'author' => get_current_user_id(),
                'post_type' => 'like',
                'meta_query' => array(
                  // need to use inner array in meta_query as acts as a filter to data required to be pulled
                    array(
                      // key is field value looking for in query
                      'key' => 'liked_professor_id',
                      // compare is comparison operator used for field - '=' is looking for matching value
                      'compare' => '=',
                      // value is what works with comparison operator - professor id key above needs to match/equal to current post ID of page currently viewing
                      'value' => get_the_ID()
                    )
                  )
                ));

                if($likeCount->found_posts){
                  $existStatus = 'yes';
                }
              }

            ?>

            <span class="like-box" data-like="<?php if(isset($existQuery->posts[0]->ID)) echo $existQuery->posts[0]->ID; // prints ID of post currently added to page (if liked) ?>" data-professor="<?php the_ID(); //will add the ID of the post currently being viewed ?>" data-exists="<?php echo $existStatus ?>">
              <i class="fa fa-heart-o" aria-hidden="true"></i>
              <i class="fa fa-heart" aria-hidden="true"></i>
              <span class="like-count"><?php echo $likeCount->found_posts; // will ignore any pagination settings and give all pages found ?></span>
            </span>
            <?php
              the_content();
            ?>
          </div>
        </div>
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
          echo '<h2 class="headline headline--medium">Subject(s) Taught</h2>';
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
