<?php
  // Create a file called searchform.php - WP has built in method to look for a file with this name convention and can pull in form HTML into another php file to share
?>

<?php // Use esc_url as extra security when redirecting to root URL ?>
<form class="search-form" method="get" action="<?php echo esc_url(site_url('/')) ?>">
<?php
  // in action attribute use WP method site_url so form submits to root page - will allow to add query param from input field to homepage URL rather than search page URL
  // Include method with get to allow form submission to add input name to URL as parameter
?>
    <label class="headline headline--medium" for="s">Perform a new search</label>
    <div class="search-form-row">
      <input class="s" id="s" type="search" name="s" placeholder="What are you looking for?" />
        <?php // add name attribute with s value so that on submission will get added as query param and allow to search page links with input value - DOMAIN_NAME?s=INPUT_VALUE  ?>
      <input class="search-submit" type="submit" value="Search">
    </div>
</form>
