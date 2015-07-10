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

function page_title()
{
    global $WPGLOBAL, $loop;
    $prismic = $WPGLOBAL['prismic'];
    $doc = $loop->current_post();
    if (!$doc) {
        return;
    }
    $title = $doc->getStructuredText($doc->getType().'.title');
    if ($title) {
        return $title->asText();
    }
}

function default_title() {
    global $WPGLOBAL, $loop;
    $prismic = $WPGLOBAL['prismic'];
    $doc = $loop->current_post();
    if (!$doc) {
        return;
    }
    $body = $doc->getSliceZone($doc->getType().'.body');
    foreach($body->getSlices() as $slice) {
       foreach($slice->getValue()->getArray() as $group) {
            foreach($group->getFragments() as $sliceItem) {
                if($sliceItem instanceof \Prismic\Fragment\StructuredText) {
                    if($sliceItem->getFirstHeading()) return $sliceItem->getFirstHeading()->getText();
                }
            }
        }
    }
    return;
}

function default_description() {
    global $WPGLOBAL, $loop;
    $prismic = $WPGLOBAL['prismic'];
    $doc = $loop->current_post();
    if (!$doc) {
        return;
    }
    $body = $doc->getSliceZone($doc->getType().'.body');
    foreach($body->getSlices() as $slice) {
       foreach($slice->getValue()->getArray() as $group) {
            foreach($group->getFragments() as $sliceItem) {
                if($sliceItem instanceof \Prismic\Fragment\StructuredText) {
                    if($sliceItem->getFirstParagraph()) return $sliceItem->getFirstParagraph()->getText();
                }
            }
        }
    }
    return;
}

function default_image() {
    global $WPGLOBAL, $loop;
    $prismic = $WPGLOBAL['prismic'];
    $doc = $loop->current_post();
    if (!$doc) {
        return;
    }
    $body = $doc->getSliceZone($doc->getType().'.body');
    foreach($body->getSlices() as $slice) {
       foreach($slice->getValue()->getArray() as $group) {
            foreach($group->getFragments() as $sliceItem) {
                if($sliceItem instanceof \Prismic\Fragment\Image) {
                    if($sliceItem->getMain()) return $sliceItem->getMain()->getUrl();
                }
            }
        }
    }
    return;
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

function isShareReady() {
    return social() ? true : false;
}

function socialPluginActivated() {
        global $WPGLOBAL, $loop;
    $prismic = $WPGLOBAL['prismic'];
    $doc = $loop->current_post();
    if(!$doc) {
        return;
    }
    $socialEnabled = $doc->getText($doc->getType().'.social_cards_enabled');
    if($socialEnabled == 'Enabled') {
        return true;
    } else {
        return false;
    }
}

function open_graph_card_exist() {
    $socialSlices = social();
    foreach($socialSlices as $slice) {
        if(is_open_graph_card($slice->getSliceType())) {
            return true;
        }
    }
    return false;
}

function is_open_graph_card($sliceType) {
    if($sliceType == 'general_card' || $sliceType == 'product_card' || $sliceType == 'place_card') {
        return true;
    }
}

function open_graph_card_type() {
    $socialSlices = social();
    foreach($socialSlices as $slice) {
        $sliceType = $slice->getSliceType();
        if(is_open_graph_card($sliceType)) {
            return $sliceType;
        }
    }
}

function general_card() {
    $socialSlices = social();
    foreach($socialSlices as $slice) {
        if($slice->getSliceType() == 'general_card') {
            return $slice->getValue();
        }
    }
}
function general_card_title() { return general_card()->getArray()[0]['card_title'] ? general_card()->getArray()[0]['card_title']->getValue() : default_title(); }
function general_card_description() { return general_card()->getArray()[0]['card_description'] ? general_card()->getArray()[0]['card_description']->getValue() : default_description(); }
function general_card_image() { return general_card()->getArray()[0]['card_image'] && general_card()->getArray()[0]['card_image']->getMain() ? general_card()->getArray()[0]['card_image']->getMain()->getUrl() : default_image(); }


function product_card() {
    $socialSlices = social();
    foreach($socialSlices as $slice) {
        if($slice->getSliceType() == 'product_card') {
            return $slice->getValue();
        }
    }
}
function product_card_title() { return product_card()->getArray()[0]['card_title'] ? product_card()->getArray()[0]['card_title']->getValue() : default_title(); }
function product_card_description() { return product_card()->getArray()[0]['card_description'] ? product_card()->getArray()[0]['card_description']->getValue() : default_description(); }
function product_card_amount() { return product_card()->getArray()[0]['card_amount'] ? product_card()->getArray()[0]['card_amount']->getValue() : ''; }
function product_card_currency() { return product_card()->getArray()[0]['card_currency'] ? product_card()->getArray()[0]['card_currency']->getValue() : ''; }
function product_card_single_image() {return product_card()->getArray()[0]['card_image0'] && product_card()->getArray()[0]['card_image0']->getMain() ? product_card()->getArray()[0]['card_image0']->getMain()->getUrl() : default_image(); }
function product_card_images() { 
    $imagesUrls =[];
    foreach(product_card()->getArray()[0]->getFragments() as $sliceItem) {
        if($sliceItem instanceof \Prismic\Fragment\Image) {
            array_push($imagesUrls, $sliceItem->getMain()->getUrl());
        }
    }
    return '['. implode(',', $imagesUrls) . ']';
}

function place_card() {
    $socialSlices = social();
    foreach($socialSlices as $slice) {
        if($slice->getSliceType() == 'place_card') {
            return $slice->getValue();
        }
    }
}
function place_card_title() { return place_card()->getArray()[0]['card_title'] ? place_card()->getArray()[0]['card_title']->getValue() : default_title(); }
function place_card_description() { return place_card()->getArray()[0]['card_description'] ? place_card()->getArray()[0]['card_description']->getValue() : default_description(); }
function place_card_latitude() { return place_card()->getArray()[0]['card_latitude'] ? place_card()->getArray()[0]['card_latitude']->getValue() : ''; }
function place_card_longitude() { return place_card()->getArray()[0]['card_longitude'] ? place_card()->getArray()[0]['card_longitude']->getValue() : ''; }
function place_card_image() { return place_card()->getArray()[0]['card_image'] ? place_card()->getArray()[0]['card_image']->getMain()->getUrl() : default_image(); }

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
function twitter_app_site() { return twitter_app()->getArray()[0]['twitter_site'] ? twitter_app()->getArray()[0]['twitter_site']->getValue() : ''; }
function twitter_app_creator() { return twitter_app()->getArray()[0]['twitter_creator'] ? twitter_app()->getArray()[0]['twitter_creator']->getValue() : ''; }
function twitter_app_country() { return twitter_app()->getArray()[0]['app_country'] ? twitter_app()->getArray()[0]['app_country']->getValue() : ''; }
function twitter_app_iphone_name() { return twitter_app()->getArray()[0]['iphone_name'] ? twitter_app()->getArray()[0]['iphone_name']->getValue() : ''; }
function twitter_app_iphone_id() { return twitter_app()->getArray()[0]['iphone_id'] ? twitter_app()->getArray()[0]['iphone_id']->getValue() : ''; }
function twitter_app_iphone_url() { return twitter_app()->getArray()[0]['iphone_url'] ? twitter_app()->getArray()[0]['iphone_url']->getValue() : ''; }

function twitter_app_ipad_name() { return twitter_app()->getArray()[0]['ipad_name'] ? twitter_app()->getArray()[0]['ipad_name']->getValue() : ''; }
function twitter_app_ipad_id() { return twitter_app()->getArray()[0]['ipad_id'] ? twitter_app()->getArray()[0]['ipad_id']->getValue() : ''; }
function twitter_app_ipad_url() { return twitter_app()->getArray()[0]['ipad_url'] ? twitter_app()->getArray()[0]['ipad_url']->getValue() : ''; }

function twitter_app_android_name() { return twitter_app()->getArray()[0]['android_name'] ? twitter_app()->getArray()[0]['android_name']->getValue() : ''; }
function twitter_app_android_id() { return twitter_app()->getArray()[0]['android_id'] ? twitter_app()->getArray()[0]['android_id']->getValue() : ''; }
function twitter_app_android_url() { return twitter_app()->getArray()[0]['android_url'] ? twitter_app()->getArray()[0]['android_url']->getValue() : ''; }

function twitter_summary() {
    $socialSlices = social();
    foreach($socialSlices as $slice) {
        if($slice->getSliceType() == 'twitter_summary') {
            return $slice->getValue();
        }
    }
}
function twitter_summary_title() { return twitter_summary()->getArray()[0]['card_title'] ? twitter_summary()->getArray()[0]['card_title']->getValue() : default_title(); }
function twitter_summary_description() { return twitter_summary()->getArray()[0]['card_description'] ? twitter_summary()->getArray()[0]['card_description']->getValue() : default_description(); }
function twitter_summary_image() { return twitter_summary()->getArray()[0]['card_image'] ? twitter_summary()->getArray()[0]['card_image']->getMain()->getUrl() : default_image(); }
function twitter_summary_site() { return twitter_summary()->getArray()[0]['twitter_site'] ? twitter_summary()->getArray()[0]['twitter_site']->getValue() : ''; }
function twitter_summary_creator() { return twitter_summary()->getArray()[0]['twitter_creator'] ? twitter_summary()->getArray()[0]['twitter_creator']->getValue() : ''; }

function twitter_summary_large() {
    $socialSlices = social();
    foreach($socialSlices as $slice) {
        if($slice->getSliceType() == 'twitter_summary_large') {
            return $slice->getValue();
        }
    }
}
function twitter_summary_large_title() { return twitter_summary_large()->getArray()[0]['card_title'] ? twitter_summary_large()->getArray()[0]['card_title']->getValue() : default_title(); }
function twitter_summary_large_description() { return twitter_summary_large()->getArray()[0]['card_description'] ? twitter_summary_large()->getArray()[0]['card_description']->getValue() : default_description(); }
function twitter_summary_large_image() { return twitter_summary_large()->getArray()[0]['card_image'] ? twitter_summary_large()->getArray()[0]['card_image']->getMain()->getUrl() : default_image(); }
function twitter_summary_large_site() { return twitter_summary_large()->getArray()[0]['twitter_site'] ? twitter_summary_large()->getArray()[0]['twitter_site']->getValue() : ''; }
function twitter_summary_large_creator() { return twitter_summary_large()->getArray()[0]['twitter_creator'] ? twitter_summary_large()->getArray()[0]['twitter_creator']->getValue() : ''; }

function email() {
    $socialSlices = social();
    foreach($socialSlices as $slice) {
        if($slice->getSliceType() == 'email') {
            return $slice->getValue();
        }
    }
}
function email_title() { 
    return 'hello';
    $emailTitle = email()->getArray()[0]['card_title']->getValue();
    return $emailTitle ? $emailTitle : open_graph_title(); 
}
function email_description() { 
    return 'hello';
    $emailDescription = email()->getArray()[0]['card_description']->getValue(); 
    return $emailDescription ? $emailDescription : open_graph_description();
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