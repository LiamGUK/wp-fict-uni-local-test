<?php
// Creating a separate file to hold logic which is to also be executed inside functions.php file.
// Rather than adding all logic directly inside functions.php file, can add blocks in a separate file and then import/include into functions.php file to keep code organised

// To create a custom endpoint to WP REST API need to hook into rest_api_init hook to fire function when WP creates REST API
add_action('rest_api_init', 'universityRegisterSearch');

function universityRegisterSearch() {
   // Use WP register_rest_route method to create a new endpoint to be added to REST API - accepts 3 arguments:
   // 1) custom namespace value for endpoint (default is wp - wp-json/'WP'/v2)
   // 2) Route value - last value in REST API endpoint (wp-json/wp/v2/ROUTE_VALUE)
   // 3) Associative array tht describes what happens when someone visits the endpoint
  register_rest_route('university/v1', 'search', array(
    'methods' => WP_REST_SERVER::READABLE, // Built in constant that auto assigns 'GET' method and will work with all hosting types
    'callback' => 'universitySearchResults' // add callback key to array to point to a function that will fire when custom endpoint is visited
  ));
}

  // function to use with above custom endpoint will execute with GET method declared in array
  // will auto get access to any parameters included in endpoint by adding a local parameter to function
function universitySearchResults($data) {
  // function will fire when user visits - http://fictional-university.local/wp-json/university/v1/search
  // WP will auto convert php syntax into JSON format when wanting to return JSON data with custom endpoint
  $mainQuery = new WP_Query(array(
    // pass in post_type key in array to specify which post type you want to pull from - pass in array and include a list of post types that are to be included in custom query
    'post_type' => array('post', 'page', 'professor', 'program', 'campus', 'event'),
    's' => sanitize_text_field($data['term']) // s = search key - can combine with a parameter attached to endpoint to only return JSON data matching param value - use square brackets and include param name inside as string to return value from URL
    // wrap in sanitize_text_field method as extra security method to prevent SQL injection attacks
  ));

   // create main empty array with nested arrays to organise posts into their own categories so data returned in JSON is already organised to be used on frontend
  $results = array(
    'generalInfo' => array(),
    'professors' => array(),
    'programs' => array(),
    'events' => array(),
    'campuses' => array()
  );

  // Use while loop using have_posts method to only loop published posts from above custom query
  while($mainQuery->have_posts()) {
    $mainQuery->the_post(); // use the_post method to grab all post info and auto increment counter

    // Use if statements to only add data into the nested array based on its type
    if (get_post_type() == 'post' OR get_post_type() == 'page') {
       // use php method array_push to add items into an array - pass in array wanting to update and then 2nd argument is value(s) to add to array
      array_push($results['generalInfo'], array(
        'title' => get_the_title(),
        'permalink' => get_the_permalink(),
        'postType' => get_post_type(),
        'authorName' => get_the_author()
      ));
    }

    if (get_post_type() == 'professor') {
      array_push($results['professors'], array(
        'title' => get_the_title(),
        'permalink' => get_the_permalink(),
        'image' => get_the_post_thumbnail_url(0, 'professorLandscape')
      ));
    }

    if (get_post_type() == 'program') {
       // Use get_field method to retrieve advanced custom field values - pass in custom field name wanting to retrieve data from.
      $relatedCampuses = get_field('related_campus');
      // get_field method returns data in an array - need to use a foreach lop to access individual items and add to empty array
      if ($relatedCampuses) {
        foreach($relatedCampuses as $campus) {
          array_push($results['campuses'], array(
            'title' => get_the_title($campus),
            'permalink' => get_the_permalink($campus)
          ));
        }
      }

      array_push($results['programs'], array(
        'title' => get_the_title(),
        'permalink' => get_the_permalink(),
        'id' => get_the_id()
      ));
    }

    if (get_post_type() == 'campus') {
      array_push($results['campuses'], array(
        'title' => get_the_title(),
        'permalink' => get_the_permalink()
      ));
    }

    if (get_post_type() == 'event') {
      $eventDate = new DateTime(get_field('event_date'));
      $description = null;
       // If post/page has a custom excerpt added in the page editor screen in WP admin can use built excerpt method to print it on page rather than trimming text
        // Use if check to test if post has an excerpt added in WP admin can then use excerpt method otherwise defaults to use trim method if no excerpt set
      if (has_excerpt()) {
         // the_excerpt(); -- the_excerpt handles content and styling of block - can use below get_the_excerpt method to just pull the text in
        $description = get_the_excerpt();
      } else {
        $description = wp_trim_words(get_the_content(), 18);
      }

      array_push($results['events'], array(
        'title' => get_the_title(),
        'permalink' => get_the_permalink(),
        'month' => $eventDate->format('M'),
        'day' => $eventDate->format('d'),
        'description' => $description
      ));
    }

  }

  if ($results['programs']) {
    $programsMetaQuery = array('relation' => 'OR');

    foreach($results['programs'] as $item) {
      array_push($programsMetaQuery, array(
        // add meta_query key and with nested associated arrays to include to look at advanced custom field name of related_programs and use LIKE comparison operator to only retrieve matched values
          'key' => 'related_programs',
          'compare' => 'LIKE',
          'value' => '"' . $item['id'] . '"' // value is ID number of custom post type of program - need to concat with double quotes to compare value as a string type
        ));
    }

    // Add another custom query to return data related to related programs key attached to each professor post type to compare against the searched term entered in search input field
    $programRelationshipQuery = new WP_Query(array(
      'post_type' => array('professor', 'event'),
      'meta_query' => $programsMetaQuery
    ));

    // Add another while loop to through custom query above to retrieve only professor posts with a matching program ID against value entered in search input field
    while($programRelationshipQuery->have_posts()) {
      $programRelationshipQuery->the_post();

      if (get_post_type() == 'event') {
        $eventDate = new DateTime(get_field('event_date'));
        $description = null;
        if (has_excerpt()) {
          $description = get_the_excerpt();
        } else {
          $description = wp_trim_words(get_the_content(), 18);
        }

        array_push($results['events'], array(
          'title' => get_the_title(),
          'permalink' => get_the_permalink(),
          'month' => $eventDate->format('M'),
          'day' => $eventDate->format('d'),
          'description' => $description
        ));
      }

      if (get_post_type() == 'professor') {
        array_push($results['professors'], array(
          'title' => get_the_title(),
          'permalink' => get_the_permalink(),
          'image' => get_the_post_thumbnail_url(0, 'professorLandscape')
        ));
      }

    }

    // use php array_unique method to remove any duplicate values returned from both the mainQuery custom query looping all post types and program relationship custom query
    $results['professors'] = array_values(array_unique($results['professors'], SORT_REGULAR)); // wrap array_unique method with array_values method to remove number keys auto added to unique array

    $results['events'] = array_values(array_unique($results['events'], SORT_REGULAR));
  }

  return $results;

   // return $professors->posts; // Inside the professors variable will hold a key called posts which will have all published posts in WP admin linked to the professor post type
}
