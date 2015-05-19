<?php

function the_author()
{
    echo get_the_author();
}

function get_the_author()
{
    global $WPGLOBAL, $loop;
    $post = $loop->current_post();
    $author = $post->getLink($post->getType().'.author');
    if (!$author) {
        return;
    }

    return htmlentities($author->getText('author.full_name'));
}

function get_author_posts_url($author_id, $author_nicename = '')
{
    global $WPGLOBAL;
    $prismic = $WPGLOBAL['prismic'];
    if (!$author_id) {
        return;
    }
    $auth = $prismic->get_document($author_id);
    if (!$auth) {
        return;
    }

    return $prismic->linkResolver->resolveDocument($auth);
}

/////

function get_the_author_link_url()
{
    global $WPGLOBAL, $loop;
    $prismic = $WPGLOBAL['prismic'];
    $post = $loop->current_post();
    if (!$post) {
        return;
    }
    $auth = null;
    if ($post->getType() == 'author') {
        $auth = $post;
    } else {
        $auth = $post->getLink($post->getType().'.author');
    }
    if (!$auth) {
        return;
    }

    return $prismic->linkResolver->resolve($auth);
}

function get_the_author_link()
{
    global $WPGLOBAL, $loop;
    $prismic = $WPGLOBAL['prismic'];
    $post = $loop->current_post();
    if (!$post) {
        return;
    }
    $auth = null;
    if ($post->getType() == 'author') {
        $auth = $post;
    } else {
        $auth = $post->getLink($post->getType().'.author');
    }
    if (!$auth) {
        return;
    }

    $author_link = $prismic->linkResolver->resolve($auth);

    return '<a class="author" href = "'.$author_link.'">'.$auth->getText('author.full_name').'</a>';
}

function the_author_link()
{
    echo get_the_author_link();
}

/////

function author_image()
{
    echo get_author_image();
}

function get_author_image()
{
    global $loop;
    $post = $loop->current_post();
    $author = $post->getLink($post->getType().'.author');
    if (!$author) {
        return;
    }
    $photo = $author->getImage('author.photo');
    if ($photo) {
        return $photo->asHtml();
    } else {
        return;
    }
}

/////

function single_author_name()
{
    global $WPGLOBAL;
    if (!array_key_exists('author', $WPGLOBAL)) {
        return;
    }
    $author = $WPGLOBAL['author'];

    return $author->getText('author.full_name');
}

function single_author_bio()
{
    global $WPGLOBAL;
    if (!array_key_exists('author', $WPGLOBAL)) {
        return;
    }
    $author = $WPGLOBAL['author'];

    return $author->getStructuredText('author.bio')->asText();
}

function single_author_links()
{
    global $WPGLOBAL;
    if (!array_key_exists('author', $WPGLOBAL)) {
        return;
    }
    $author = $WPGLOBAL['author'];
    $sites = $author->getGroup('author.sites');
    if ($sites == null) {
        return;
    }
    $result =  '<ul>';
    foreach ($sites->getArray() as $site) {
        $result .= '<li><a href="'.$site['link']->getUrl().'">'.$site['label']->asText().'</a></li>';
    }
    $result .= '</ul>';

    return $result;
}

function single_author_image()
{
    global $WPGLOBAL;
    if (!array_key_exists('author', $WPGLOBAL)) {
        return;
    }
    $author = $WPGLOBAL['author'];
    $photo = $author->getImage('author.photo');
    if ($photo) {
        return $photo->asHtml();
    } else {
        return;
    }
}

function single_author_image_url()
{
    global $WPGLOBAL;
    if (!array_key_exists('author', $WPGLOBAL)) {
        return;
    }
    $author = $WPGLOBAL['author'];
    $photo = $author->getImage('author.photo');
    if ($photo) {
        return $photo->getView('main')->getUrl();
    } else {
        return;
    }
}

function get_the_author_meta($field, $userID = null)
{
    global $WPGLOBAL, $loop;
    $prismic = $WPGLOBAL['prismic'];
    $author = null;
    if ($userID) {
        $author = $prismic->get_document($userID);
    } else {
        $post = $loop->current_post();
        if ($post != null) {
            $author = $post->getLink($post->getType().'.author');
        }
    }
    if ($author == null) {
        return;
    }
    switch ($field) {
        case 'ID': return $author->getId();
        case 'display_name': return $author->getText('author.full_name')->asText();
        case 'bio': return $author->getText('author.bio')->asText();
        default: return;
    }
}

function the_author_meta($field, $userID = null)
{
    echo get_the_author_meta($field, $userID = null);
}
