<?php

function is_mailgun_loaded()
{
    global $WPGLOBAL;
    $app = $WPGLOBAL['app'];

    return $app->config('mailgun.apikey') &&
           $app->config('mailgun.pubkey') &&
           $app->config('mailgun.domain');
}

function mailgun_pubkey()
{
    global $WPGLOBAL;
    $app = $WPGLOBAL['app'];

    return $app->config('mailgun.pubkey');
}

function mailgun_domain_sha1()
{
    global $WPGLOBAL;
    $app = $WPGLOBAL['app'];
    $domain = $app->config('mailgun.domain');

    return $domain ? sha1($domain) : null;
}

function contact()
{
    global $WPGLOBAL;
    if (isset($WPGLOBAL['contact'])) {
        return $WPGLOBAL['contact'];
    }

    return;
}

function contact_title()
{
    $contact = contact();
    return $contact->getText('contact.headline');
}

function contact_image_url()
{
    $contact = contact();
    return $contact->getImage('contact.image')->getMain()->getUrl();
}

function contact_description()
{
    $contact = contact();
    return $contact->getText('contact.description');
}

function contact_feedback_success()
{
    $contact = contact();
    return $contact->getText('contact.feedback-success');
}

function contact_feedback_error()
{
    $contact = contact();
    return $contact->getText('contact.feedback-error');
}
