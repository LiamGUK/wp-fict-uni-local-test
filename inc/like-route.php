<?php
  // like-route.php file = for creating custom REST API endpoint to update like feature on professor pages

  add_action('rest_api_init', 'universityLikeRoutes');

  function universityLikeRoutes(){
    // Function registers 2 new routes for REST API
    register_rest_route('university/v1', 'manageLike', array(
      // For POST requests to this endpoint will call createLike function below to action request
      'methods' => 'POST',
      'callback' => 'createLike'
    ));

    register_rest_route('university/v1', 'manageLike', array(
      // For DELETE requests to this endpoint will call deleteLike function below to action request
      'methods' => 'DELETE',
      'callback' => 'deleteLike'
    ));
  }

  function createLike($params){
    // data returned in function is what will be available to JS logic making http request as JSON data

    // Adding below condition of only checking if the user is logged in is not enough due to requirements of implementing nonce value to work with REST API
    if(is_user_logged_in()){
      // callback function will auto receive any parameters added to REST endpoint in JS logic - extract using a variable where values are stored in an array
      $professor = sanitize_text_field($params['professorId']); // wrap variable in a sanitize text method to ensure value is added here as plain text

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
            'value' => $professor // add professor ID received via JS logic with endpoint request - use to check if current user has already liked professor post
          )
        )
      ));

      // If user has not liked the professor post fire below block
      if($existQuery->found_posts == 0 && get_post_type($professor) == 'professor'){
        // Create new like post
        // wp_insert_post method inserts or updates an existing post - return in function to return ID value of the post currently viewing
        return wp_insert_post(array(
        // Inside array describe post wanting to update/create
        'post_type' => 'like',
        'post_status' => 'publish',
        'post_title' => '2nd PHP test',
        'meta_input' => array(
          // meta_input key accepts an array value which creates custom meta fields in WP
          'liked_professor_id' => $professor // Will target the custom field created in Advanced Custom Field plugin - professor post ID to be added as value - pass in endpoint param value which will be the professor post ID value
          )
        ));
      } else {
        die('Professor already liked');
      }


    } else {
      die('Only logged in users can create a like');
    }


  }

  function deleteLike($data){
    // data returned in function is what will be available to JS logic making http request as JSON data
    $likeId = sanitize_text_field($data['like']);

    // Need to add a validation check when deleting any posts from WP inside server code as won't do any extra checks for you - below checks if the current user already has like ID value attached to post_author field  and that the ID is a valid ID for the post type like in WP admin - can only then run wp_delete_post method to delete post from WP admin
    if(get_current_user_id() == get_post_field('post_author', $likeId) && get_post_type($likeId) == 'like'){
      wp_delete_post($likeId, true);
      return 'Congrats like deleted';
    } else {
      die('You do not have permission to delete');
    }
  }
