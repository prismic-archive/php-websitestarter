<?php

class WP_Query
{
    public $query;
    public $query_vars;
    public $queried_object;
    public $queried_object_id;
    public $posts;
    public $post_count;
    public $found_posts;
    public $max_num_pages;
    public $current_post;
    public $post;
    public $is_single;
    public $is_page;
    public $is_archive;
    public $is_preview;
    public $is_date;
    public $is_year;
    public $is_month;
    public $is_time;
    public $is_author;
    public $is_category;
    public $is_tag;
    public $is_tax;
    public $is_search;
    public $is_feed;
    public $is_comment_feed;
    public $is_trackback;
    public $is_home;
    public $is_404;
    public $is_comments_popup;
    public $is_admin;
    public $is_attachment;
    public $is_singular;
    public $is_robots;
    public $is_posts_page;
    public $is_paged;

    public function __construct($query = '')
    {
        $this->query = $query;
    }

    public function init()
    {
    }

    public function parse_query($query)
    {
    }

    public function parse_query_vars()
    {
    }

    public function get($query_var)
    {
    }

    public function set($query_var, $value)
    {
    }

    public function &get_posts()
    {
    }

    public function next_post()
    {
    }

    public function have_posts()
    {
        // TODO
        return false;
    }

    public function the_post()
    {
        // TODO
        return;
    }

    public function rewind_posts()
    {
    }

    public function &query($query)
    {
    }

    public function get_queried_object()
    {
    }

    public function get_queried_object_id()
    {
    }
}
