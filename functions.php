<?php
  // * functions.php file is a behind the scenes type file - used to work with wordpress system

  // * use require with get_theme_file_path WP method to import in code from search-route.php file to include inside this file - can import in files from any directory in root folder
  require get_theme_file_path('/inc/search-route.php');
  require get_theme_file_path('/inc/like-route.php');

  // Create custom function to be executed with add_action built in method below.
  function university_files(){
    //  wp_enqueue_style = method that loads a specific stylesheet
    //  pass in name of stylesheet to load and src location of stylesheet
    //  get_stylesheet_uri method retrieves the active stylesheet in current theme directory listed as style.css
    //  wp_enqueue_style('university_main_styles', get_stylesheet_uri());

    //  When wanting to load a css library, add library host location as a string as 2nd argument in wp_enqueue_style method
    wp_enqueue_style('custom-google-fonts', '//fonts.googleapis.com/css?family=Roboto+Condensed:300,300i,400,400i,700,700i|Roboto:100,300,400,400i,700,700i');
    wp_enqueue_style('font-awesome', '//maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css');

    //  get_theme_file_uri allows to pull a CSS file in a specific folder in the active directory
    wp_enqueue_style('university_main_styles', get_theme_file_uri('/build/style-index.css'));
    wp_enqueue_style('university_extra_styles', get_theme_file_uri('/build/index.css'));

    //  wp_enqueue_script = method that loads javascript files
    //  pass in name of javascript file and 2nd argument is the location of file in directory
    //  3rd argument is for any dependencies that are needed for JS to run - this case needs jquery link to execute code - add empty array if no dependencies are needed
    //  4th argument is version number
    //  5th argument is position of script to be added near closing body tag - add true to apply this (false will add to head)
    wp_enqueue_script('main-university-js', get_theme_file_uri('/build/index.js'), array('jquery'), '1.0', true);

    //  Google maps script link - needs working API key to work
    //  wp_enqueue_script('googleMap', '//maps.googleapis.com/maps/api/js?key=API_KEY', [], '1.0', true);

    // wp_localisze_script method converts javascript file to work for registered site URL address when being viewed (will know if its a local hosted site or live hosted site) - to use when needing to write JS logic based on URL of site (will work for both situations)
    //  pass in name of script file linked in functions.php file (name given to enqueue_script above - index.js)
    //  2nd argument name for reference to this method call (can be anything) - will be added as variable to page to reference to
    //  3rd argument pass in associative array to link the root_url to the current site URL address
    //  Will add to page in script tags at bottom of page
    wp_localize_script('main-university-js', 'universityData', array(
      'root_url' => get_site_url(),
      'nonce' => wp_create_nonce('wp_rest') // WP method to generate a unique number value for the current user - to be used with REST API to allow to make update requests (DELETE & UPDATE posts)
    ));
  }

  // add_action adds a callback function to an action hook - instructs WP to perform a certain action on active page
  // Actions are the hooks that the WordPress core launches at specific points
  // Accepts 2 arguments:
  // 1) type of instruction giving WP
  // 2) name of function wanting to run - added as a string (function not called)
  // wp_enqueue_scripts = instructs WP want to run a specific script or CSS file on page load
  add_action('wp_enqueue_scripts', 'university_files'); // this will execute function defined above to load stylesheets

  function university_features(){
    // add_theme_support method allows to set actions based on current active theme
    // Passing in title-tag allows WP to auto update the title tag for each page to its page title - can be updated in WP admin settings
    add_theme_support('title-tag');

    // Adds the option to include linked images (featured image) to a post in WP block editor
    // Can only set one featured image for one post
    add_theme_support('post-thumbnails');

    // add_image_size method allows to specify a custom image size WP should also include when uploading an image to repo - 1st argument is nickname of custom image size, 2nd argument is width of image, 3rd argument is height of image and 4th argument is if wanting to crop image (set to true if needing to crop)
    add_image_size('professorLandscape', 400, 260, true);
    add_image_size('professorPortrait', 480, 650, true);
    add_image_size('pageBanner', 1500, 350, true);

    // If wanting more control over how image is cropped instead of passing a boolean value include an array with values for crop position on the X and Y axis
    // Can use manual image crop plugin to control this instead
    add_image_size('customCrop', 480, 650, array('left', 'top'));

    // WP method to add a Menus option under the appearance tab in WP admin - 1st argument location slug (can be any name), 2nd argument description text to appear for menu
    // 1st argument string added to wp_nav_menu function in header.php file
    register_nav_menu('headerMenuLocation', 'Header Menu Location');

    // Adding additional nav_menus to WP admin will list them under menu settings -> display location check boxes
    register_nav_menu('footerLocationOne', 'Footer Location One');

    register_nav_menu('footerLocationTwo', 'Footer Location Two');
  }

  // This action hook executes after WP has setup the theme - will call the university features function above
  add_action('after_setup_theme', 'university_features');

  // Pass in param which is given to function after pre_get_posts hook executes
  function university_adjust_queries($query){
    //$query->set('posts_per_page', '1'); // Firing this method like this would result in only displaying 1 post for all posts and custom post types in WP - use validation to target specific URLs to only apply this.
    // below if check checks if the page viewing isn't the WP admin site, is only the event post type page and is the default query type via the is_main_query method
    if(!is_admin() AND is_post_type_archive('event') AND $query->is_main_query()){
      $today = date('Ymd');
      // $query->set('posts_per_page', '1');
      $query->set('meta_key', 'event_date');
      $query->set('orderby', 'meta_value_num');
      $query->set('order', 'ASC');
      $query->set('meta_query', array(
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
              ));
    }

    if(!is_admin() AND is_post_type_archive('program') AND $query->is_main_query()){
      $query->set('orderby', 'title');
      $query->set('order','ASC');
      $query->set('posts_per_page', -1);
    }

    if(!is_admin() AND is_post_type_archive('campus') AND $query->is_main_query()){
      // Settings posts_per_page to -1 ensures that it will pull in all posts (map data) into page
      $query->set('posts_per_page', -1);
    }
  }

  // action hook executes function before it attempts to load posts from WP admin
  // Use this function to set up pagination for a custom post type if not needing to add a new instance of the WP_Query inside template file
  add_action('pre_get_posts', 'university_adjust_queries');

  function universityMapKey($api){
    $api['key'] = 'API_KEY';
    return $api;
  }

  // Required to activate Google Map api inside advanced custom field plugin and set locations in WP editor - returns api key to connect to Googles server from plugin
  add_filter('acf/fields/google_map/api', 'universityMapKey');

  // function to add custom key fields to existing WP REST API
  function university_custom_rest(){
    // register_rest_field method takes 3 arguments
    // 1) post type wanting to customise (page, post or custom post type)
    // 2) is field name (key) wanting to add to rest api JSON response
    // 3) array that describes how you want to manage field (key) in JSON response
    register_rest_field('post', 'authorName', array(
      // with get_callback key add function which will return data to be displayed in JSON response
      'get_callback' => function(){ return get_the_author(); }
    ));

    // Create a new custom REST API field that keeps track of total number of posts user has
    register_rest_field('note', 'userNoteCount', array(
      // with get_callback key add function which will return data to be displayed in JSON response
      'get_callback' => function(){ return count_user_posts(get_current_user_id(), 'note'); }
      // Use count_user_posts method to return a total number of posts user has made - pass in ID of user and post type to return total value needed
    ));

    // can register as many custom fields as needed to REST API - below adds custom fields to pages page type
    register_rest_field('page', 'newFieldData', array(
      'get_callback' => function(){ return 'This is a test field added to REST API!'; }
    ));
  }

  // Use action hook to listen for REST API set up when site loads - fires above function to add custom keys to WP REST API
  add_action('rest_api_init', 'university_custom_rest');

  // Redirect subscriber accounts out of admin and onto homepage
  add_action('admin_init', 'redirectSubsToFrontend');

  function redirectSubsToFrontend() {
    // Use get_current_user method to grab current user information (role type etc)
    $ourCurrentUser = wp_get_current_user();
    // Check if logged in user only has one role assigned to them and they are a subscriber
    if (count($ourCurrentUser->roles) == 1 AND $ourCurrentUser->roles[0] == 'subscriber') {
      // use wp_redirect method to redirect user when they log in to the homepage of site instead of admin dashboard
      wp_redirect(site_url('/'));
      // use php exit method to exit out of function and stop after redirection instruction
      exit;
    }
  }

  add_action('wp_loaded', 'noSubsAdminBar');

  function noSubsAdminBar() {
    // Use get_current_user method to grab current user information (role type etc)
    $ourCurrentUser = wp_get_current_user();
    // Check if logged in user only has one role assigned to them and they are a subscriber
    if (count($ourCurrentUser->roles) == 1 AND $ourCurrentUser->roles[0] == 'subscriber') {
      // show_admin_bar method determines if the WP admin bar should be displayed to user on site - pass in false to hide it from view
      show_admin_bar(false);
    }
  }

  // Customise log in screen - use add_filter hook to update/change existing element in WP
  // login_headerurl hook will look to fire function when loading the login form image link on page

  add_filter('login_headerurl', 'ourHeaderUrl');

  function ourHeaderUrl() {
    // In function return a new url string which will replace the existing link on WP registration login page
    return esc_url(site_url('/'));
  }

   // To update logo image need to add own CSS to replace background image
  //  hook into action login_enqueue_scripts to load custom css on the login screen page
  add_action('login_enqueue_scripts', 'ourLoginCss');

  function ourLoginCss(){
    // Use wp_enqueue_style method to load a custom css file - use get_theme_file_uri method to point to file in root folder
    wp_enqueue_style('university_main_styles', get_theme_file_uri('/build/style-index.css'));
    wp_enqueue_style('university_extra_styles', get_theme_file_uri('/build/index.css'));
  }

  // Use add_filter method to update/change existing element in WP
  // Use hook login_headertitle to change the text displayed above form on login page
  add_filter('login_headertitle', 'loginTitle');

  function loginTitle(){
    // In function use get_bloginfo method to grab info of site from DB pass in name argument to retrieve name of site to print above login form
    return get_bloginfo('name');
  }

  // Force note posts to be private - Safer way than adding to JS code
  // wp_insert_post_data method allows to intercept data before its added to database - will allow to change status field from publish to private for content set for individual user
  // Add value of 2 at end of add_filter hook arguments to declare want to use 2 parameters in function call - $data and $postarr
  add_filter('wp_insert_post_data', 'makeNotePrivate', 10, 2);

  function makeNotePrivate($data, $postarr){
    // $data param supplied from wp_insert_post_data method - data to be sent to database
    // $postarr param includes info on the content post - will include ID to check if it exists already in the database
    if($data['post_type'] == 'note'){
      // Add if check to check number of posts a user has made under note post type and that if the post doesn't exist inside DB with a value for ID not assigned
      if(count_user_posts(get_current_user_id(), 'note') > 4 && !$postarr['ID']){
        // count_user_posts methods takes arguments of id of user (use WP method to grab current user) and post type wanting to check against
        die('You have reached your note limit.'); // If number of posts made to note post type is greater than 4 will exit out of function with die method (allows to return message upon function exit)
      }

      // use sanitize methods from WP to filter out and remove all and any html tags that could be included with content from textarea or input fields - ensures all note content is stored as plain text in database
      $data['post_content'] = sanitize_textarea_field($data['post_content']);
      $data['post_title'] = sanitize_text_field($data['post_title']);
    }

    // Use if check to see if post type in data array is a note and that the status is not deleting to trash - (only want to action for new note creation and edits)
    if($data['post_type'] == 'note' && $data['post_status'] != 'trash'){
      $data['post_status'] = 'private'; // Modifies the status of post to private from publish (safer way of implementing rather than in JS code)
    }

    return $data; // All new note posts will now auto be given a status of private
  }

  // Filter hook for the WP migration plugin - allows to customise migration and instruct plugin to ignore files/folders in directory and not bundle them
  add_filter('ai1wm_exclude_content_from_export', 'ignoreCertainFiles');

  function ignoreCertainFiles($exclude_filters){
    // Pass in path of file/folder yuo want to ignore in bundle process during export
    $exclude_filters[] = 'themes/fictional-university-theme/node_modules'; // node_modules will not be included in export
    return $exclude_filters;
  }

?>

<?php
  // Create re-usable php functions here - can be used to share same block of HTML on different pages with custom options
  // add parameter to function that accepts an array of options to allow to customise below content for different pages
  // Add a default value to parameter to equal to NULL if no array passed into function
  function pageBanner($args = NULL){
    // default values if no values are added to array passed into function
    // use isset method to check if array has set keys inside (required for newer versions of PHP)
    if(!isset($args['title'])){
      $args['title'] = get_the_title();
    }

    if(!isset($args['subtitle'])) {
      $args['subtitle'] = get_field('page_banner_subtitle');
    }

    if(!isset($args['photo'])){
      // With images need to check if an image was uploaded to the repo - use get_field method to check if image was selected in custom field
      if(get_field('page_banner_background_image') && !is_archive() && !is_home()){
        $args['photo'] = get_field('page_banner_background_image')['sizes']['pageBanner'];
      } else {
        // if no image was selected in custom field use image added in images folder inside theme folder
        $args['photo'] = get_theme_file_uri('/images/ocean.jpg');
      }
    }
?>
  <div class="page-banner">
      <?php // Dynamic background image set via custom field ?>
      <div class="page-banner__bg-image" style="background-image: url(
        <?php
          // To pull image set in custom field first use get_field method and store to a variable
          // get_field will return an array so need to grab value in array under url key which will hold src link of uploaded image
          // $pageBannerImage = get_field('page_banner_background_image');
          // to use custom sized image set in functions.php pull sizes key and then use name of custom image size used in add_image_size function in functions.php
          // echo $pageBannerImage['sizes']['pageBanner'];

          echo $args['photo'];
        ?>
      );"></div>
       <?php
          // Can use print_r method and pass in get_field variable to inspect contents of array and check what data it holds.
          // print_r($pageBannerImage);
          // var_dump($pageBannerImage);
        ?>
      <div class="page-banner__content container container--narrow">
        <h1 class="page-banner__title">
          <?php
            // Renders the page tile set in WP admin of page at current loop index in while loop
            // the_title();

            // use parameter of function to grab the title key passed into function to display that content on page.
            echo $args['title'];
          ?>
        </h1>
        <div class="page-banner__intro">
          <p>
            <?php
              // Use the field method (built into Advanced custom field plugin) to pull custom field value set in block editor - use field_name value to pull content
              // the_field('page_banner_subtitle');

              echo $args['subtitle'];
            ?>
          </p>
        </div>
      </div>
    </div>
<?php
  }
?>

<?php
  /*
  ! BELOW CODE MOVED TO MU-PLUGINS FOLDER - use as a plugin instead to share with other themes
  function university_post_types(){
    //  Use the WP method register_post_type to create a new page type in WP (rather than just page and post) 1st argument is custom name for page type, 2nd argument is an associative array of options for page type
    register_post_type('event', array(
      // public with true value will mean page type will be visible to everyone
      'public' => true,
      'labels' => array(
        'name' => 'Events'
      ),
      'menu_icon' => 'dashicons-calendar'
    ));
    // Downside to adding new post type and activating in functions.php file is that menu will only be accessible with this theme only. If activating another theme custom post type will not exist for it and won't be able to see custom posts
    // Better to implement in a plugin instead - create a new folder under wp-content in root and name mu-plugins (must use plugins)
  }

  // Action hook will execute at the first point of initialization with init type
  add_action('init', 'university_post_types');
*/
?>
