# NewCity Shortcodes Wordpress Plugin

This plugin adds shortcodes and an interface for inserting and editing their contents.
The editing interface depends on the [Shortcake (Shortcode UI) plugin](https://wordpress.org/plugins/shortcode-ui/)

By default, no shortcodes are added or enabled. To enable individual shortcodes, go to `Settings -> NewCity Shortcodes` and check the codes you wish to enable.

The following shortcodes are included:

* custom_blockquote
* local_script
* inline_media

## Setting default enabled plugins for a theme

This plugin sets a Wordpress option called `newcity_shortcodes_options`. It contains
a single array called `enabled_shortcodes[]`. By setting this value with PHP, most likely
in your theme's `functions.php` file, you can either lock the values down permanently or
set up an initial state for the theme.

### Locking down the enabled plugins

```PHP
// Check for existence of newcity-wp-shortcodes plugin
if ( class_exists('NewCityShortcodes')) {

    // Set value for `newcity_shortcodes_options` only if it does not exist yet
    function set_nc_shortcode_options() {
        $options = array(
            'enabled_shortcodes' => array(
                'custom_blockquote',
                'local_script',
                'inline_media',
            )
        );

        update_option('newcity_shortcodes_options', $options, '', 'yes');
    }

    // Disabled checkboxes in settings window by setting `"permanent"` to `true`
    // in the plugin's options
    function lock_shortcodes_settings() {
        $current_options = get_option('newcity_shortcodes_options', false);
        $locked_options = array_merge($current_options, array('permanent' => array('enabled_plugins')));
        update_option('newcity_shortcodes_options', $locked_options, 'yes');
    }

    // Check if you need to set the enabled shortcodes each time a page loads
    add_action('init', 'set_nc_shortcode_option');
    add_action('init', 'lock_shortcodes_settings');
}
```

### Locking down the local scripts path

```PHP
function lock_shortcodes_settings() {
    $current_options = get_option('newcity_shortcodes_options', false);
    $locked_options = array_merge($current_options, array('permanent' => array('script_path')));
    update_option('newcity_shortcodes_options', $locked_options, 'yes');
}
```

### Setting initial state for enabled plugins

```PHP
// Check for existence of newcity-wp-shortcodes plugin
if ( class_exists('NewCityShortcodes')) {

    // Set value for `newcity_shortcodes_options` only if it does not exist yet
    function set_nc_shortcode_options() {
        $options = array(
            'enabled_shortcodes' => array(
                'custom_blockquote',
                'local_script',
                'inline_media',
            )
        );

        if ( ! get_option('newcity_shortcodes_options', false) ) {
            add_option('newcity_shortcodes_options', $options, '', 'yes');
        }
    }

    // Check if you need to set the enabled shortcodes each time a page loads
    add_action('init', 'set_nc_shortcode_option');
}
```


## Shortcode Descriptions

### Custom Blockquote Shortcode

Creates a blockquote that supports a citation field and wraps the quote body in a `<p>`:

```HTML
<blockquote>
    <p>Quote body goes here</p>
    <cite>Citation goes here</cite>
</blockquote>
```

### Local Script Shortcode

Allows the enqueuing of javascript files that are stored in the theme folder's `local-scripts` folder.
This is a more secure alternative to allowing script code to be pasted directly into the content field.

By default, this shortcode will look for the script named in the `source` attribute in the folder set
as the default on the plugin settings page. This folder can be overridden using the `path` attribute,
but all script paths must be located inside the current theme's folder.

#### *Enqueuing `{theme-folder}/js/sample.js` when `js` is the default path*

```
[local_script script="sample" /]
```

#### *Enqueuing `{theme-folder}/custom-path/sample02.js` when `js` is the default path*

```
[local_script script="sample02" path="custom-path"]
```

### Inline Media Shortcode

A replacement for the default media placement WYSIWYG tools. Inserts an image with optional caption,
wrapped in sufficient containers to allow for lots of customization.

```HTML
<div class="media { ALIGNMENT_CLASS }">
    <figure>
        <div class="media__wrapper">
            <img src="{ IMAGE_SOURCE }" alt="{ IMAGE_ALT_TAG }" />
        </div>

        <figcaption>{ IMAGE CAPTION }</figcaption>
    </figure>
</div>
```