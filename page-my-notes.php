<?php
  // page-my-notes.php file to control rendering of content associated with my-notes page - name of file here needs to match slug pf page
?>

<?php
  // my-notes page is a page only for users that are registered and logged in - use is_user_logged_in method to check if user is logged in (will return a boolean)
  if(!is_user_logged_in()){
    // if user isn't logged in add redirect method to direct back to the homepage
    wp_redirect(esc_url(site_url('/')));
    exit; // Use exit call here so that below code isn't executed
  }

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
      <div class="create-note">
        <h2 class="headline headline--medium">Create New Note</h2>
        <input class="new-note-title" placeholder="Title">
        <textarea class="new-note-body" placeholder="Your note here..."></textarea>
        <span class="submit-note">Create Note</span>
        <span class="note-limit-message">Note limit reached: delete an existing note to make room for a new one.</span>
      </div>

      <ul class="min-list link-list" id="my-notes">
        <?php
          // Create new instance of WP_Query to only return posts that we are interested in
          $userNotes = new WP_Query(array(
            'post_type' => 'note',
            'posts_per_page' => -1,
            'author' => get_current_user_id() // use get_current_user_id method to return only posts where author matches current logged in user
          ));

          // use while loop to loop through custom query to return all posts that match the above conditions
          while($userNotes->have_posts()){
            $userNotes->the_post();
            // print_r($userNotes);
            ?>

            <li data-id="<?php the_ID(); // Adds the current post ID in loop and adds value as attribute to li element - use with JS to identify which post in block it is ?>">
              <?php // Add readonly attribute to input and textarea so that the user can't edit the text on page load ?>
              <input readonly class="note-title-field" value="<?php
                // Use str_replace method to remove auto populated Private word to title due to private status being set - replace with empty string to remove
                echo str_replace('Private: ', '', esc_attr(get_the_title())); // wrap in esc_attr for php to escape any HTML attributes contained in title
                ?>">
              <span class="edit-note"><i class="fa fa-pencil" aria-hidden="true"></i> Edit</span>
              <span class="delete-note"><i class="fa fa-trash-o" aria-hidden="true"></i> Delete</span>
              <textarea readonly class="note-body-field">
                <?php
                  // use esc_textarea method to ensure that content added to textarea element is only added as plain text
                  echo esc_textarea(wp_strip_all_tags(get_the_content())); // wrap method in wp_strip_all_tags to remove wp elements included with text
                ?>
              </textarea>

              <span class="update-note btn btn--blue btn--small"><i class="fa fa-arrow-right" aria-hidden="true"></i> Save</span>
            </li>

        <?php
          }
        ?>
      </ul>
    </div>

  <?php
    // Add new php block here to add closing bracket (}) to end while loop
  }
?>

<?php get_footer(); ?>
