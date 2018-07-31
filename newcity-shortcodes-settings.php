<?php

class NewCityShortcodesSettings {
  private $options;
  
  public function __construct()
  {
      add_action( 'admin_menu', array( $this, 'add_plugin_page' ) );
      add_action( 'admin_init', array( $this, 'page_init' ) );
  }
  
  /**
   * Add options page
   */
  public function add_plugin_page()
  {
      // This page will be under "Settings"
      add_options_page(
          'Settings Admin',
          'NewCity Shortcodes',
          'manage_options',
          'newcity-shortcodes-admin',
          array( $this, 'create_admin_page' )
      );
  }
  
  /**
   * Options page callback
   */
  public function create_admin_page()
  {
      $this->options = get_option( 'newcity_shortcodes_options' );
      require_once( NC_SHORTCODES_ABSPATH . 'options.inc' );
  }
  
  /**
   * Register and add settings
   */
  public function page_init()
  {
      register_setting(
          'newcity_shortcodes_options',
          'newcity_shortcodes_options'
      );

      add_settings_section(
          'newcity_shortcodes_options_main',
          'Settings',
          array(),
          'newcity-shortcodes-admin'
      );

      add_settings_field(
        'enable_shortcodes',
        'Enable the following shortcodes',
        array( $this, 'checklist_input_callback' ),
        'newcity-shortcodes-admin',
        'newcity_shortcodes_options_main',
        array( 'id' => 'enabled_shortcodes', 'default' => [], 'available_options'=> [ 'custom_blockquote', 'local_script' ] )
      );

  }
  
  /**
    * field callbacks
    **/
  public function string_input_callback($args)
  {
      printf(
          '<input type="text" id="%s" name="newcity_shortcodes_options[%s]" value="%s" />',
          $args['id'], $args['id'],
          isset( $this->options[$args['id']] ) ? esc_attr( $this->options[$args['id']]) : ''
      );
  }

  public function boolean_input_callback($args)
  {
      $check_state = isset( $this->options[$args['id']] ) ? $this->options[$args['id']] : $args['default'];
      printf(
        '<input type="checkbox" id="%s" name="newcity_shortcodes_options[%s]" value="1" %s />',
        $args['id'], $args['id'],
        checked( TRUE, $check_state, FALSE)
      );
  }

  public function checklist_input_callback($args) {
      foreach( $args['available_options'] as $option ) {
        //   echo "<pre>Available Shortcodes\n";
        // echo (isset( $this->options[$args['id']] ) );
        $check_state = in_array( $option, isset( $this->options[$args['id']] ) ? $this->options[$args['id']] : $args['default']);
        // print_r (isset( $this->options[$args['id']] ) ? $this->options[$args['id']] : $args['default'] );
        // echo('</pre>');
        printf(
            '<div><input type="checkbox" id="%s" name="newcity_shortcodes_options[%s][]" value="%s" %s /> <label>%s</label></div>',
            $args['id'], $args['id'],
            $option,
            checked( TRUE, $check_state, FALSE),
            $option
        );
    }
  }

}