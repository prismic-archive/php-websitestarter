<?php
function page_url()
{
    global $WPGLOBAL, $loop;
    $prismic = $WPGLOBAL['prismic'];
    $doc = $loop->current_post();
    if (!$doc) {
        return;
    }
    $scheme = $_SERVER["HTTPS"] == "on" ? "https://" : "http://";
    $serverName = $_SERVER['HTTP_HOST'];
    return $scheme . $serverName . document_url($doc);
}

function social() {
    global $WPGLOBAL, $loop;
    $prismic = $WPGLOBAL['prismic'];
    $doc = $loop->current_post();
    if(!$doc) {
        return;
    }
    $socialData = $doc->getSliceZone($doc->getType().'.social');
    if($socialData) {
        return $socialData->getSlices();
    }
}

function twitter_card_exist() {
    $socialSlices = social();
    foreach($socialSlices as $slice) {
        if(is_twitter_card($slice->getSliceType())) {
            return true;
        }
    }
    return false;
}

function is_twitter_card($sliceType) {
    if($sliceType == 'twitter_app' || $sliceType == 'twitter_summary' || $sliceType == 'twitter_summary_large') {
        return true;
    }
}

function twitter_card_type() {
    $socialSlices = social();
    foreach($socialSlices as $slice) {
        $sliceType = $slice->getSliceType();
        if(is_twitter_card($sliceType)) {
            return $sliceType;
        }
    }
}

function twitter_app() {
    $socialSlices = social();
    foreach($socialSlices as $slice) {
        if($slice->getSliceType() == 'twitter_app') {
            return $slice->getValue();
        }
    }
}
function twitter_app_site() { return twitter_app()->getArray()[0]['twitter_site']->getValue(); }
function twitter_app_creator() { return twitter_app()->getArray()[0]['twitter_creator']->getValue(); }
function twitter_app_country() { return twitter_app()->getArray()[0]['app_country']->getValue(); }
function twitter_app_iphone_name() { return twitter_app()->getArray()[0]['iphone_name']->getValue(); }
function twitter_app_iphone_id() { return twitter_app()->getArray()[0]['iphone_id']->getValue(); }
function twitter_app_iphone_url() { return twitter_app()->getArray()[0]['iphone_url']->getValue(); }
function twitter_app_ipad_name() { return twitter_app()->getArray()[0]['ipad_name']->getValue(); }
function twitter_app_ipad_id() { return twitter_app()->getArray()[0]['ipad_id']->getValue(); }
function twitter_app_ipad_url() { return twitter_app()->getArray()[0]['ipad_url']->getValue(); }
function twitter_app_android_name() { return twitter_app()->getArray()[0]['android_name']->getValue(); }
function twitter_app_android_id() { return twitter_app()->getArray()[0]['android_id']->getValue(); }
function twitter_app_android_url() { return twitter_app()->getArray()[0]['android_url']->getValue(); }

function twitter_summary() {
    $socialSlices = social();
    foreach($socialSlices as $slice) {
        if($slice->getSliceType() == 'twitter_summary') {
            return $slice->getValue();
        }
    }
}
function twitter_summary_title() { return twitter_summary()->getArray()[0]['card_title']->getValue(); }
function twitter_summary_description() { return twitter_summary()->getArray()[0]['card_description']->getValue(); }
function twitter_summary_image() { return twitter_summary()->getArray()[0]['card_image']->getMain()->getUrl(); }
function twitter_summary_site() { return twitter_summary()->getArray()[0]['twitter_site']->getValue(); }
function twitter_summary_creator() { return twitter_summary()->getArray()[0]['twitter_creator']->getValue(); }

function twitter_summary_large() {
    $socialSlices = social();
    foreach($socialSlices as $slice) {
        if($slice->getSliceType() == 'twitter_summary_large') {
            return $slice->getValue();
        }
    }
}
function twitter_summary_large_title() { return twitter_summary_large()->getArray()[0]['card_title']->getValue(); }
function twitter_summary_large_description() { return twitter_summary_large()->getArray()[0]['card_description']->getValue(); }
function twitter_summary_large_image() { return twitter_summary_large()->getArray()[0]['card_image']->getMain()->getUrl(); }
function twitter_summary_large_site() { return twitter_summary_large()->getArray()[0]['twitter_site']->getValue(); }
function twitter_summary_large_creator() { return twitter_summary_large()->getArray()[0]['twitter_creator']->getValue(); }

function open_graph() {
    $socialSlices = social();
    foreach($socialSlices as $slice) {
        if($slice->getSliceType() == 'open_graph') {
            return $slice->getValue();
        }
    }
}
function open_graph_title() { return open_graph()->getArray()[0]['card_title']->getValue(); 
}
function open_graph_description() { return open_graph()->getArray()[0]['card_description']->getValue(); }
function open_graph_image() { return open_graph()->getArray()[0]['card_image']->getMain()->getUrl(); }

function email() {
    $socialSlices = social();
    foreach($socialSlices as $slice) {
        if($slice->getSliceType() == 'email') {
            return $slice->getValue();
        }
    }
}
function email_title() { 
    $emailTitle = email()->getArray()[0]['card_title']->getValue();
    return $emailTitle || open_graph_title(); 
}
function email_description() { 
    $emailDescription = email()->getArray()[0]['card_description']->getValue(); 
    return $emailDescription || open_graph_description();
}

function page_social_cards_image()
{
    global $WPGLOBAL, $loop;
    $prismic = $WPGLOBAL['prismic'];
    $doc = $loop->current_post();
    if (!$doc) {
        return;
    }
    return $doc->getImage($doc->getType().'.cards_image')->getMain()->getUrl();
}

?>