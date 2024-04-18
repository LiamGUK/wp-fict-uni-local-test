<?php
  // single-program.php used as a template file for the program post type created in university_post_type.php file in plugins folder
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
          <a class="metabox__blog-home-link" href="<?php echo get_post_type_archive_link('program'); // gets the custom post type url - uses value passed into method ?>"><i class="fa fa-home" aria-hidden="true"></i> All Programs</a>
          <span class="metabox__main">
           <?php the_title(); ?>
          </span>
        </p>
      </div>

      <div class="generic-content">
        <?php
          // the_content();
          // pull from the ACF WSYWIG field in programs post type to ensure search queries won't match with any words added in content - only want to match words from title field for programs
          the_field('main_body_content');
        ?>
      </div>

      <?php
        $relatedProfessors = new WP_Query(array(
              // associative array requests the maximum number of posts to grab (2 posts) and to only grab the post_type of event (setting -1 will show all)
              'posts_per_page' => -1,
              'post_type' => 'professor',
              'orderby' => 'title',
              // order will control direction of sort (default is DESC - highest to lowest)
              'order' => 'ASC',
              'meta_query' => array(
                // in meta_query pass in an array which will be used to compare conditions to run and display a condition to render a sorted item
                array(
                  'key'=> 'related_programs',
                  'compare' => 'LIKE',
                  'value' => '"' . get_the_ID() . '"' //Sets the value of ID method and concats into a string when array gets serialized in Database
                )
              )
        ));

        // Will only run the below while loop and render content if there is event linked to program
        if($relatedProfessors->have_posts()){
          echo '<hr class="section-break">';
          echo '<h2 class="headline headline--medium">' . get_the_title() . ' Professors</h2>';

          echo '<ul class="professor-cards">';
          while($relatedProfessors->have_posts()){
            $relatedProfessors->the_post(); ?>
            <li class="professor-card__list-item">
              <a class="professor-card" href="<?php the_permalink(); ?>">
                <img class="professor-card__image" src="<?php the_post_thumbnail_url('professorLandscape'); // will use the custom image landscape size set in functions.php ?>" alt="">
                <span class="professor-card__name"><?php the_title(); ?></span>
              </a>
            </li>
        <?php
          }
          echo '</ul>';
        }

        // below resets the global posts object back to the default URL query - will allow the below custom query to run like normal
        wp_reset_postdata();

        $today = date('Ymd');
        // custom query - create new instance of WP_Query class so can pass in info of what data we want to query in database
        $homepageEvents = new WP_Query(array(
              // associative array requests the maximum number of posts to grab (2 posts) and to only grab the post_type of event (setting -1 will show all)
              'posts_per_page' => -1,
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
                  'compare' => '>=',
                  // value is value to check against - this case checking if event_date value is greater than today's date - if so will display sorted item on page
                  'value' => $today,
                  // type key tells WP data type checking against - this case a number value
                  'type' => 'numeric'
                ),
                array(
                  'key'=> 'related_programs',
                  'compare' => 'LIKE',
                  'value' => '"' . get_the_ID() . '"' //Sets the value of ID method and concats into a string when array gets serialized in Database
                )
              )
        ));

        // Will only run the below while loop and render content if there is event linked to program
        if($homepageEvents->have_posts()){
          echo '<hr class="section-break">';
          echo '<h2 class="headline headline--medium">Upcoming ' . get_the_title() . ' Events</h2>';

          while($homepageEvents->have_posts()){
            $homepageEvents->the_post();

            // get_template_part method will look for specific template files and pull in code block
            // 1st argument is name of folder in theme folder holding files with / + name of file
            // 2nd argument is optional and appends a dash with name in 2nd argument included - below would look for file content-event.php
            get_template_part('template-parts/content', 'event');
          }

        }
        //  if using a custom query in a while loop to render content to page call below function to reset global variables back to state when its default automatic query was based on the current URL
        // call method below while loop where the loop will have finished
        wp_reset_postdata();

        $relatedCampuses = get_field('related_campus');

        if($relatedCampuses){
          echo '<hr class="section-break">';
          echo '<h2 class="headline headline--medium">' . get_the_title() . ' is available at these campuses:</h2>';

          echo '<ul class="min-list link-list">';
          foreach($relatedCampuses as $campus){
            ?>
            <li>
              <a href="<?php echo get_the_permalink($campus); ?>"><?php echo get_the_title($campus); ?></a>
            </li>
          <?php
          }
          echo '</ul>';
        }



        ?>

    </div>

    <?php // Exit php mode here to add HTML inside while loop ?>

  <?php
    // Add new php block here to add closing bracket (}) to end while loop
  }
?>

<?php get_footer(); ?>
