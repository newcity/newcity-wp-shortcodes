# NewCity Shortcodes Wordpress Plugin

This plugin adds shortcodes (and a TinyMCE plugin) used in multiple NewCity projects.

## NewCity Blockquote Shortcode

Creates a replacement for the default `blockquote` button in TinyMCE. Instead of simply inserting
or wrapping a `<blockquote>` tag around WYSIWYG content, it opens a dialog that allows a quote
body and a separate citation field. That dialog generates a shortcode, which in turn is converted
to HTML in the following format:

```HTML
<blockquote>
    <p>Quote body goes here</p>
    <cite>Citation goes here</cite>
</blockquote>
```

The `newcity_blockquote` button must be added to toolbars separately using Wordpress' `tiny_mce` hooks.
The separate [newcity-toolbars](https://github.com/newcity/newcity-wp-wysiwyg) plugin does this for you,
for both standard content sections and ACF wysiwyg fields.