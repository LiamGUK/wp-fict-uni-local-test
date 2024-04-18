<?php
  // page-search.php - used for search page fall back for users who have js disabled and can't use live search option
?>

<?php
  get_header();

  // pageBanner method used to render page banner on page - pass in array of options to customise text displayed
    pageBanner();
?>

<?php
  // Built in WP method have_posts - returns a boolean value
  // Use while loop to loop through all published pages in WP admin and print content to be used in page template. Each page will share this template but while loop will render individual content for each page.
  // If there are posts available returns, when there aren't any posts left = returns false
  while(have_posts()) {
    // the_post method iterates the post index value (don't need to create separate count variable and increment)
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

      <?php
        $theParent = wp_get_post_parent_id(get_the_id());
        // Will only render the below block of HTML if the page in loop has a parent page assigned to it - will return 0 if no parent page found
        if($theParent){ ?>
        <div class="metabox metabox--position-up metabox--with-home-link">
          <p>
            <a class="metabox__blog-home-link" href="<?php echo get_permalink($theParent); ?>"><i class="fa fa-home" aria-hidden="true"></i> Back to <?php echo get_the_title($theParent); // retrieves the posts title of page - pass in page id to grab relevant page title ?></a> <span class="metabox__main">
              <?php the_title(); ?>
            </span>
          </p>
        </div>
       <?php
        }
      ?>

      <?php
      // get_pages method returns all the pages added in WP admin in memory - pass in associative array to return certain pages
      $testArray = get_pages(array(
        // array will ensure method will only return pages that have a child ID - won't return anything if no ID exists
        'child_of' => get_the_ID()
      ));
      if($theParent or $testArray) { ?>
        <div class="page-links">
          <h2 class="page-links__title"><a href="<?php echo get_permalink($theParent); ?>"><?php echo get_the_title($theParent); // use parent variable to check if page rendered is the parent page (will return 0 if so) ?></a></h2>
          <ul class="min-list">
            <?php
              // Associative arrays are items with key value pairs - select a key to pull the value of item
              $associativeArr = array(
                'cat' => 'Meow',
                'dog' => 'Bark',
                'pig' => 'Oink'
              );
              // Extract a value from the associative array using the [] and add key name inside to pull value
              $associativeArr['cat'];

              if($theParent){
                $findChildrenOf = $theParent;
              } else {
                $findChildrenOf = get_the_ID();
              }

              // wp_list_pages method will list out all published pages in WP admin onto the page - pass in associative array to customise which items get rendered on page
              wp_list_pages(array(
                'title_li' => NULL,
                'child_of' => $findChildrenOf,
                // Adding sort_column key will instruct WP to order items in list by the order number set in page attributes in WP admin
                'sort_column' => 'menu_order'
              ));
            ?>
          </ul>
        </div>
      <?php } ?>

      <div class="generic-content">
        <?php
          // WP method that looks for file called searchform.php - will pull in HTML form elements into this php file
          get_search_form();
        ?>
      </div>
    </div>


  <?php
    // Add new php block here to add closing bracket (}) to end while loop
  }
?>

<?php get_footer(); ?>
