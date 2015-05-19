<?php

function errorpage_title($errorpage)
{
    return $errorpage ? $errorpage->getText('errorpage.headline') : '';
}

function errorpage_image_url($errorpage)
{
    return $errorpage ? $errorpage->getImage('errorpage.image')->getMain()->getUrl() : '';
}

function errorpage_description($errorpage)
{
    return $errorpage ? $errorpage->getText('errorpage.description') : '';
}

function notfound()
{
    global $WPGLOBAL;
    if (isset($WPGLOBAL['notfound'])) {
        return $WPGLOBAL['notfound'];
    }

    return;
}

function notfound_title()
{
    return errorpage_title(notfound());
}

function notfound_image_url()
{
    return errorpage_image_url(notfound());
}

function notfound_description()
{
    return errorpage_description(notfound());
}

?>
