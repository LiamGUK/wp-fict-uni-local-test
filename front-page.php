<?php
 // font-page.php = powers content to be rendered only on homepage - to be added if Reading settings in WP admin settings has been changed to display a static page and blog pages set to post pages (Blog pages are added via index.php file)
?>

<?php
  // get_header function = pulls in and uses header.php file added in theme project folder
  get_header();
?>

<?php
  function doubleMe($x){
    // use return keyword to exit function and return the value of function calculation
    // Can call function to validate value or store to a variable outside with function call to store value in memory.
    return $x * 2;
  }

  function tripleMe($x){
    return $x * 3;
  }

  // echo tripleMe(doubleMe(5));

  // methods that include 'the' in name will be functions that handle the echo for you (built into function)
  // the_title();

  // methods with 'get' in name require an argument passed in to return a value from function call - will need to be manually echo'd if wanting to print on page
  // get_the_title();

  // the_ID();
  // get_the_ID();
?>

<div class="page-banner">
  <div
    class="page-banner__bg-image"
    style="background-image: url(<?php
    // get_theme_file_uri = grabs the current active theme directory - pass in location of image file inside directory to load on page
      echo get_theme_file_uri('/images/library-hero.jpg');
    ?>)"
  ></div>
  <div class="page-banner__content container t-center c-white">
    <h1 class="headline headline--large">Welcome!</h1>
    <h2 class="headline headline--medium">
          We think you&rsquo;ll like it here.
    </h2>
    <h3 class="headline headline--small">
          Why don&rsquo;t you check out the <strong>major</strong> you&rsquo;re
          interested in?
    </h3>
    <a href="<?php echo get_post_type_archive_link('program'); ?>" class="btn btn--large btn--blue">Find Your Major</a>
  </div>
</div>

    <div class="full-width-split group">
      <div class="full-width-split__one">
        <div class="full-width-split__inner">
          <h2 class="headline headline--small-plus t-center">
            Upcoming Events
          </h2>

          <?php
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
                )
              )
            ));

            while($homepageEvents->have_posts()){
              $homepageEvents->the_post();
              // get_template_part method will look for specific template files and pull in code block
              // 1st argument is name of folder in theme folder holding files with / + name of file
              // 2nd argument is optional and appends a dash with name in 2nd argument included - below would look for file content-event.php
              get_template_part('template-parts/content', 'event');

              // can combine with get_post_type method to dynamically gab file based on post type if named as such
              // get_template_part('template-parts/content', get_post_type());
            }
               //  if using a custom query in a while loop to render content to page call below function to reset global variables back to state when its default automatic query was based on the current URL
              // call method below while loop where the loop will have finished
              wp_reset_postdata();
          ?>

          <p class="t-center no-margin">
            <a href="<?php echo get_post_type_archive_link('event'); ?>" class="btn btn--blue">View All Events</a>
          </p>

        </div>
      </div>
      <div class="full-width-split__two">
        <div class="full-width-split__inner">
          <h2 class="headline headline--small-plus t-center">From Our Blogs</h2>

          <?php
            // custom queries start by creating a variable - create a new instance of the WP_Query class
            $homepagePosts = new WP_Query(array(
              // pass associative array as a param to WP_Query instance to grab what is needed - this case first 2 blog posts published in WP admin
              'posts_per_page' => 2,
              // 'category_name' => 'awards'
              // 'post_type' => 'page'
            ));

            // have_posts and the_posts are default WP methods that are tide to the current URL of page
            // Use new custom query variable instead to grab required posts
            // -> will look in new class instance and call the methods to the right of it
            while($homepagePosts->have_posts()){
              // calling the_post method using custom query ensures it looks for posts under the blog list (under blog slug) rather than the homepage URL (that this php file controls) which the_posts method defaults to
              $homepagePosts->the_post(); ?>

              <div class="event-summary">
                <a class="event-summary__date event-summary__date--beige t-center" href="<?php the_permalink(); ?>">
                  <span class="event-summary__month"><?php the_time('M'); ?></span>
                  <span class="event-summary__day"><?php the_time('d'); ?></span>
                </a>
                <div class="event-summary__content">
                  <h5 class="event-summary__title headline headline--tiny">
                    <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                  </h5>
                  <p>
                    <?php
                    // If post/page as a custom excerpt added in the page editor screen in WP admin can use built excerpt method to print it on page rather than trimming text
                    // Use if check to test if post has an excerpt added in WP admin can then use excerpt method otherwise defaults to use trim method if no excerpt set
                    if(has_excerpt()){
                      // the_excerpt(); -- the_excerpt handles content and styling of block - can use below get_the_excerpt method to just pull the text in
                      echo get_the_excerpt();
                    } else {
                      // wp method accepts a group of text content and 2nd argument determines how many words it should render (wp trims/removes the rest of the text)
                      echo wp_trim_words(get_the_content(), 18);
                    } ?>
                    <a href="<?php the_permalink(); ?>" class="nu gray">Read more</a>
                  </p>
                </div>
              </div>
           <?php
           }
          //  if using a custom query in a while loop to render content to page call below function to reset global variables back to state when its default automatic query was based on the current URL
          // call method below while loop where the loop will have finished
           wp_reset_postdata();
          ?>

          <p class="t-center no-margin">
            <a href="<?php echo site_url('/blog'); // passing in /blog will append it to domain url grabbed from site_url method - will then direct to blog page ?>" class="btn btn--yellow">View All Blog Posts</a>
          </p>
        </div>
      </div>
    </div>

    <div class="hero-slider">
      <div data-glide-el="track" class="glide__track">
        <div class="glide__slides">
          <div
            class="hero-slider__slide"
            style="background-image: url(<?php echo get_theme_file_uri('/images/bus.jpg'); ?>)"
          >
            <div class="hero-slider__interior container">
              <div class="hero-slider__overlay">
                <h2 class="headline headline--medium t-center">
                  Free Transportation
                </h2>
                <p class="t-center">
                  All students have free unlimited bus fare.
                </p>
                <p class="t-center no-margin">
                  <a href="#" class="btn btn--blue">Learn more</a>
                </p>
              </div>
            </div>
          </div>
          <div
            class="hero-slider__slide"
            style="background-image: url(<?php echo get_theme_file_uri('/images/apples.jpg'); ?>)"
          >
            <div class="hero-slider__interior container">
              <div class="hero-slider__overlay">
                <h2 class="headline headline--medium t-center">
                  An Apple a Day
                </h2>
                <p class="t-center">
                  Our dentistry program recommends eating apples.
                </p>
                <p class="t-center no-margin">
                  <a href="#" class="btn btn--blue">Learn more</a>
                </p>
              </div>
            </div>
          </div>
          <div
            class="hero-slider__slide"
            style="background-image: url(<?php echo get_theme_file_uri('/images/bread.jpg'); ?>)"
          >
            <div class="hero-slider__interior container">
              <div class="hero-slider__overlay">
                <h2 class="headline headline--medium t-center">Free Food</h2>
                <p class="t-center">
                  Fictional University offers lunch plans for those in need.
                </p>
                <p class="t-center no-margin">
                  <a href="#" class="btn btn--blue">Learn more</a>
                </p>
              </div>
            </div>
          </div>
        </div>
        <div
          class="slider__bullets glide__bullets"
          data-glide-el="controls[nav]"
        ></div>
      </div>
    </div>

<?php
  // get_footer method pulls in content added to the footer.php file in current theme directory
  get_footer();
?>
