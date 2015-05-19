<?php

/**
 * Tags related to pages (as in: documents of the "page" type)
 */

function home()
{
    global $WPGLOBAL;
    $prismic = $WPGLOBAL['prismic'];

    return $prismic->home();
}

function page_link($page)
{
    global $WPGLOBAL;
    $app = $WPGLOBAL['app'];
    $classes = array();
    $active = $app->request()->getPath() == $page['url'];
    if ($active) {
        array_push($classes, 'active');
    }
    if ($page['external'] == true) {
        array_push($classes, 'external');
    }

    return '<a href="'.$page['url'].'" class="'.implode(' ', $classes).'">'.$page['label'].'</a>';
}

function slice_content($slice, $linkResolver)
{
    global $WPGLOBAL;
    $sliceFile  = views_dir()
                .'/slices/'
                .$slice->getSliceType();
    $sliceLabelFile = $sliceFile.'-'.$slice->getLabel().'.php';
    $sliceFile = $sliceFile.'.php';
    if (file_exists($sliceLabelFile)) {
        include $sliceLabelFile;
    } elseif (file_exists($sliceFile)) {
        include $sliceFile;
    } else {
        echo $slice->asHtml($linkResolver);
    }
}

function page_content()
{
    global $WPGLOBAL, $loop;
    $prismic = $WPGLOBAL['prismic'];
    $doc = $loop->current_post();
    if (!$doc) {
        return;
    }
    $body = $doc->getSliceZone($doc->getType().'.body');
    if ($body) {
        foreach ($body->getSlices() as $slice) {
            slice_content($slice, $prismic->linkResolver);
        }
    }
}

function get_pages()
{
    $home = home();
    if (array_key_exists('children', $home)) {
        return $home['children'];
    } else {
        return array();
    }
}
