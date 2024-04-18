<?php
  // archive-campus template file used for post list of new post type created in university_post_type file in mu-plugins folder
  // Create file name with archive- + post type name + .php and will render content from here otherwise will default to pull content from archive.php template file
?>

<?php
  get_header();

  // pageBanner method used to render page banner on page - pass in array of options to customise text displayed (logic in functions.php file)
  pageBanner(array(
    'title' => 'Our Campuses',
    'subtitle' => 'We have several conveniently located campuses.'
  ));
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

    <div class="acf-map">
    <?php
      while(have_posts()){
        the_post();

        // use get_field method from advanced custom fields to grab google map info set inside custom post type page in WP editor
        $mapLocation = get_field('map_location');
      ?>

        <div class="marker" data-lat="<?php echo $mapLocation['lat'] ?>" data-lng="<?php echo $mapLocation['lng'] ?>">
          <h3><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
          <?php echo $mapLocation['address']; ?>
        </div>

            <?php
              // the_title();
              // $mapLocation = get_field('map_location');
              // echo $mapLocation['lat'];
            ?>
    <?php
      }
    ?>
    </div>

  </div>

<?php
  get_footer();
?>
