<?php
  // header.php = content for the head section of website - to be shared and used on all related template theme files
?>

<!DOCTYPE html>
<html <?php language_attributes(); //WP method to auto set lang attribute for site ?>>
<head>
  <meta charset="<?php bloginfo('charset'); // WP method to auto set charset value for site ?>">
  <?php // <meta charset="UTF-8">  ?>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <?php
    // wp_head method allows WP to handle all required elements for head element of site
    // Adds built in style sheet links and add links to custom style sheets for theme
  ?>
  <?php wp_head(); ?>
</head>
<body <?php body_class(); // Adds classes to body element with class names relevant to currant page viewing ?>>
  <header class="site-header">
      <div class="container">
        <h1 class="school-logo-text float-left">
          <a href="
            <?php
              // site_url method returns the url for the current active site - not passing in any arguments returns URL for homepage
              echo site_url();
            ?>
          "><strong>Fictional</strong> University</a>
        </h1>
        <a href="<?php echo esc_url(site_url('/search')) ?>" class="js-search-trigger site-header__search-trigger"
          ><i class="fa fa-search" aria-hidden="true"></i
        ></a>
        <i class="site-header__menu-trigger fa fa-bars" aria-hidden="true"></i>
        <div class="site-header__menu group">
          <nav class="main-navigation">
            <?php
              // wp_nav_menu adds in a dynamic nav bar created in WP admin
              // Need to pass in array with theme_location key and add name of menu added in functions.php file
              // wp_nav_menu(array(
              //   'theme_location' => 'headerMenuLocation'
              // ));
            ?>

            <ul>
              <?php
                /*
                Adding active class to links if nav links are hard coded
                is_page checks what the current slug of page is, pass in string of URL slug wanting to check against if matches = true
                Will dynamically add class value to element if the validation statement is true
                */
              ?>

              <li <?php if(is_page('about-us') or wp_get_post_parent_id(0) == 13) echo 'class="current-menu-item"'; ?>><a href="
                <?php
                  // Use site_url method and pass in page URL as argument will create link to a published page in WP
                  echo site_url('/about-us')
                ?>
              ">About Us</a></li>
              <li <?php if(get_post_type() == 'program') echo 'class="current-menu-item"'; ?>><a href="<?php echo get_post_type_archive_link('program'); ?>">Programs</a></li>
              <li <?php if(get_post_type() == 'event' OR is_page('past-events')) echo 'class="current-menu-item"'; ?>><a href="<?php echo get_post_type_archive_link('event'); ?>">Events</a></li>
              <li <?php if(get_post_type() == 'campus') echo 'class="current-menu-item"' ?>><a href="<?php echo get_post_type_archive_link('campus') ?>">Campuses</a></li>
              <li <?php
                // Checks to see if current page viewed is a post page (blog post) if true adds the active class to li element
                if(get_post_type() == 'post') echo 'class="current-menu-item"';
                ?>>
                  <a href="<?php echo site_url('/blog'); ?>">Blog</a>
              </li>
            </ul>
          </nav>
          <div class="site-header__util">
            <?php if(is_user_logged_in()){ ?>
                <a href="<?php echo esc_url(site_url('/my-notes')); ?>" class="btn btn--small btn--orange float-left push-right">My Notes</a>
                <a href="<?php echo wp_logout_url(); // WP method to log user out of site ?>" class="btn btn--small btn--dark-orange float-left btn--with-photo">
                <span class="site-header__avatar"><?php echo get_avatar(get_current_user_id(), 60) ?></span>
                <span class="btn__text">Log Out</span>
              </a>
            <?php } else { ?>
              <a href="<?php echo wp_login_url(); // Link to load log in registration page ?>" class="btn btn--small btn--orange float-left push-right">Login</a>
              <a href="<?php echo wp_registration_url(); // Link to load registration form for new user sign up ?>" class="btn btn--small btn--dark-orange float-left">Sign Up</a>
           <?php } ?>
            <a href="<?php echo esc_url(site_url('/search')) ?>" class="search-trigger js-search-trigger"
              ><i class="fa fa-search" aria-hidden="true"></i
            ></a>
          </div>
        </div>
      </div>
    </header>

    <?php // Closing body and html tags placed in the footer.php file (last element of theme) ?>
