<?php

// Some themes check for WP version, let's not bother them
$GLOBALS['wp_version'] = '10.0';

/**
 * Stubs for compatibility with wordpress.
 * That allows some of the themes to work with minimal effort, although it is recommended
 * to remove unsupported tags from the theme when migrating from Wordpress.
 */
function language_attributes()
{
}

function wp_head()
{
    echo '<link rel="stylesheet" href="'.get_template_directory_uri().'/style.css">';
}

function body_class()
{
}

function post_class()
{
}

function esc_url($url)
{
    return $url;
}

function esc_attr($input)
{
    return $input;
}

function _e($input)
{
    return $input;
}

function _x($text, $context, $domain = 'default')
{
    return $text;
}

function esc_attr_e($input)
{
    return $input;
}

function esc_html($input)
{
    return $input;
}

function get_header_image()
{
}

function get_post_format()
{
}

function post_password_required()
{
    return false;
}

function is_attachment()
{
    return false;
}

function comments_open()
{
}

function get_comments_number($post_id = null)
{
    return 0;
}

function __()
{
}

function add_action($name)
{
}

function add_filter($name)
{
}

function edit_post_link()
{
}

function comments_template()
{
}

function get_post_format_strings()
{
    return array();
}

function get_post_format_string()
{
    return;
}

function dynamic_sidebar()
{
}

function do_action()
{
}

function wp_footer()
{
}

function get_object_taxonomies($object, $output = 'names')
{
    return array();
}

function has_nav_menu($location)
{
    return false;
}

function is_admin()
{
    return false;
}

function get_option($option, $default = false)
{
    return $default;
}

function _n_noop($singular, $plural, $domain = null)
{
    return array($singular, $plural);
}

function do_action_ref_array($tag, $args)
{
}
