<?php

/**
 * Tags related to navigation links: previous post, next post, etc.
 */

function is_home()
{
    global $WPGLOBAL;
    $prismic = $WPGLOBAL['prismic'];

    $page = single_post();

    $homeId = $prismic->get_api()->bookmark('home');

    return $page->getId() == $homeId;
}

function is_front_page()
{
    return is_home();
}

/////

function get_previous_posts_link_url()
{
    global $WPGLOBAL, $loop;
    $app = $WPGLOBAL['app'];
    if ($loop->page == 1) {
        return '';
    }
    $qs = $app->request()->params();
    $qs['page'] = ($loop->page - 1);

    return $app->request->getPath().'?'.http_build_query($qs);
}

function get_previous_posts_link($label = '« Previous Page')
{
    $url = get_previous_posts_link_url();

    return $url ? '<a href="'.$url.'">'.htmlentities($label).'</a>' : '';
}

function previous_posts_link($label = '« Previous Page')
{
    echo get_previous_posts_link($label);
}

/////

function get_next_posts_link_url($label = 'Next Page »')
{
    global $WPGLOBAL, $loop;
    $app = $WPGLOBAL['app'];
    if ($loop->page >= $loop->totalPages) {
        return '';
    }
    $qs = $app->request()->params();
    $qs['page'] = ($loop->page + 1);

    return $app->request->getPath().'?'.http_build_query($qs);
}

function get_next_posts_link($label = 'Next Page »')
{
    $url = get_next_posts_link_url();

    return $url ? '<a href="'.$url.'">'.htmlentities($label).'</a>' : '';
}

function next_posts_link($label = 'Next Page »')
{
    echo get_next_posts_link($label);
}

/////

function previous_post_link_title()
{
    global $WPGLOBAL, $loop;
    $prismic = $WPGLOBAL['prismic'];
    $previous = $prismic->get_prev_post($loop->current_post()->getId());
    if ($previous) {
        return $previous->getText($previous->getType().'.title');
    }

    return;
}

function previous_post_link_url()
{
    global $WPGLOBAL, $loop;
    $prismic = $WPGLOBAL['prismic'];
    $previous = $prismic->get_prev_post($loop->current_post()->getId());
    if ($previous) {
        return $prismic->linkResolver->resolveDocument($previous);
    }

    return;
}

function previous_post_link($format = '&laquo; %link', $link = '%title', $in_same_term = false, $excluded_terms = '', $taxonomy = 'category')
{
    $url = previous_post_link();
    if ($url) {
        $label = str_replace('%link', htmlentities($previous->getText('post.title')), $format);
        echo '<a href="'.$url.'">'.$label.'</a>';
    }
}

/////

function next_post_link_title()
{
    global $WPGLOBAL, $loop;
    $prismic = $WPGLOBAL['prismic'];
    $next = $prismic->get_next_post($loop->current_post()->getId());
    if ($next) {
        return $next->getText($next->getType().'.title');
    }

    return;
}

function next_post_link_url()
{
    global $WPGLOBAL, $loop;
    $prismic = $WPGLOBAL['prismic'];
    $next = $prismic->get_next_post($loop->current_post()->getId());
    if ($next) {
        return $prismic->linkResolver->resolveDocument($next);
    }

    return;
}

function next_post_link($format = '%link &raquo;', $link = '%title', $in_same_term = false, $excluded_terms = '', $taxonomy = 'category')
{
    $url = next_post_link_url();
    if ($url) {
        $label = str_replace('%link', htmlentities($next->getText('post.title')), $format);
        echo '<a href="'.$url.'">'.$label.'</a>';
    }
}

/////

function get_adjacent_post($in_same_term = false, $excluded_terms = '', $previous = true, $taxonomy = 'category')
{
    global $WPGLOBAL, $loop;
    $prismic = $WPGLOBAL['prismic'];
    if ($previous) {
        return $prismic->get_prev_post($loop->current_post()->getId());
    } else {
        return $prismic->get_next_post($loop->current_post()->getId());
    }
}

function wp_nav_menu($args)
{
    $p = array_merge(array(
        'menu_class' => null,
        'menu_id' => null,
        'container' => 'div',
        'container_class' => null,
        'container_id' => null,
    ), $args);
    if (!function_exists('cls')) {
        function cls($c)
        {
            return $c ? ' class="'.$c.'"' : '';
        }
        function id($id)
        {
            return $id ? ' id="'.$id.'"' : '';
        }
    }

    echo '<'.$p['container'].cls($p['container_class']).id($p['container_id']).'>';
    echo '<ul'.cls($p['menu_class']).id($p['menu_id']).'>';
    echo '<li>'.home_link('Home').'</li>';
    foreach (get_pages() as $page) {
        if (count($page['children']) > 0) {
            echo '<li>'.page_link($page).'<ul>';
            foreach ($page['children'] as $subpage) {
                echo page_link($subpage);
            }
            echo '</ul></li>';
        } else {
            echo '<li>'.page_link($page).'</li>';
        }
    }

    echo '</ul></'.$p['container'].'>';
}

/////

function get_day_link_url()
{
    $now = new DateTime('now');

    if (!$year) {
        $year = $now->format('Y');
    }

    if (!$month) {
        $month = $now->format('m');
    }

    if (!$day) {
        $day = $now->format('j');
    }

    $date = DateTime::createFromFormat('Y-m-d', $year.'-'.$month.'-'.$day);

    $label = date_format($date, 'F, jS Y');

    return archive_link($year, $month, $date->format('d'));
}

function get_day_link($year, $month, $day)
{
    $url = get_day_link_url();

    return '<a href="'.$url.'">'.$label.'</a>';
}
