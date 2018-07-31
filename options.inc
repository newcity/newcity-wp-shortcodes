<div class="wrap">
  <h1>NewCity Shortcodes</h1>
  <form method="post" action="options.php">
    <?php settings_fields( 'newcity_shortcodes_options' ); ?>
    <?php do_settings_sections( 'newcity-shortcodes-admin' ); ?>
    <?php submit_button(); ?>
  </form>
</div>