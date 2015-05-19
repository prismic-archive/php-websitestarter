<?php

function single_cat_title($prefix = '', $display = true)
{
    global $WPGLOBAL;
    if (!array_key_exists('category', $WPGLOBAL)) {
        return;
    }
    $category = $WPGLOBAL['category'];
    $result = null;
    if ($category) {
        $result = $prefix.$category->getText('category.name');
    }
    if ($display) {
        echo $result;
    } else {
        return $result;
    }
}

function single_cat_illustration_url()
{
    global $WPGLOBAL;
    if (array_key_exists('category', $WPGLOBAL)) {
        $category = $WPGLOBAL['category'];
        if ($category && $category->getImage('category.illustration')) {
            return $category->getImage('category.illustration')->getView('main')->getUrl();
        }
    }

    return;
}

function single_cat_illustration($display = true)
{
    global $WPGLOBAL;
    $category = $WPGLOBAL['category'];
    $prismic = $WPGLOBAL['prismic'];
    $result = null;
    if ($category && $category->getImage('category.illustration')) {
        $result = $category->getImage('category.illustration')->asHtml($prismic->linkResolver);
    }
    if ($display) {
        echo $result;
    } else {
        return $result;
    }
}

function the_category($separator = '', $parents = '', $post_id = null)
{
    echo get_the_category_list($separator, $parents, $post_id);
}

function get_the_category_list($separator = '', $parents = '', $post_id = null)
{
    global $WPGLOBAL, $loop;
    $prismic = $WPGLOBAL['prismic'];
    $doc = $post_id ? $prismic->get_document($post_id) : $loop->current_post();
    if (!$doc) {
        return;
    }
    if ($doc instanceof Author) {
        return;
    }
    $strings = array();
    $categories = $doc->getGroup('post.categories');
    if (!$categories) {
        return;
    }
    foreach ($doc->getGroup('post.categories')->getArray() as $item) {
        $category = $item->getLink('link');
        if ($category) {
            $url = $prismic->linkResolver->resolve($category);
            $label = $category->getText('category.name');
            array_push($strings, '<a class="category" href="'.$url.'">'.$label.'</a>');
        }
    }

    return '<span class="categories">'.implode($separator, $strings).'</span>';
}

function single_tag_title($prefix = '', $display = true)
{
    global $WPGLOBAL;
    $tag = isset($WPGLOBAL['tag']) ? $WPGLOBAL['tag'] : null;
    if ($display) {
        echo $tag;
    } else {
        return $prefix.$tag;
    }
}

function category_description_text($uid = null)
{
    global $WPGLOBAL;

    $prismic = $WPGLOBAL['prismic'];

    if ($uid != null) {
        $category = $prismic->by_uid('category', $uid);
    } else {
        $category = $WPGLOBAL['category'];
    }

    return $category->getText('category.description');
}

function category_description($uid = null)
{
    global $WPGLOBAL;

    $prismic = $WPGLOBAL['prismic'];

    if ($uid != null) {
        $category = $prismic->by_uid('category', $uid);
    } else {
        $category = $WPGLOBAL['category'];
    }

    $description = $category->getStructuredText('category.description');

    if ($category && $description) {
        $htmlSerializer = function ($element, $content) {
            if ($element instanceof \Prismic\Fragment\Block\ParagraphBlock) {
                return '<p class="category-description">'.$content.'<p>';
            }
        };

        return $description->asHtml($prismic->linkResolver, $htmlSerializer);
    }
}
