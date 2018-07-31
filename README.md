# NewCity Shortcodes Wordpress Plugin

This plugin adds shortcodes and an interface for inserting and editing their contents.
The editing interface depends on the [Shortcake (Shortcode UI) plugin](https://wordpress.org/plugins/shortcode-ui/)

By default, no shortcodes are added or enabled. To enable individual shortcodes, go to `Settings -> NewCity Shortcodes` and check the codes you wish to enable.

The following shortcodes are included:

* custom_blockquote
* local_script

## Custom Blockquote Shortcode

Creates a blockquote that supports a citation field and wraps the quote body in a `<p>`:

```HTML
<blockquote>
    <p>Quote body goes here</p>
    <cite>Citation goes here</cite>
</blockquote>
```

## Local Script Shortcode

Allows the enqueuing of javascript files that are stored in the theme folder's `local-scripts` folder.
This is a more secure alternative to allowing script code to be pasted directly into the content field.