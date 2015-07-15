<?php

function page_url()
{
    global $WPGLOBAL, $loop;
    $prismic = $WPGLOBAL['prismic'];
    $doc = $loop->current_post();
    if (!$doc) {
        return;
    }
    $scheme = "http://";
    $serverName = array_key_exists('HTTP_HOST', $_SERVER) ? $_SERVER['HTTP_HOST'] : '';
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

function get_StructuredText_with_title_from_SliceZone($sliceZone) {
    foreach($sliceZone->getSlices() as $slice) {
       foreach($slice->getValue()->getArray() as $group) {
            foreach($group->getFragments() as $sliceItem) {
                if($sliceItem instanceof Prismic\Fragment\StructuredText) {
                 if($sliceItem->getFirstHeading()) return $sliceItem;
                }  
            }
        }
    }
}

function get_StructuredText_with_description_from_SliceZone($sliceZone) {
    foreach($sliceZone->getSlices() as $slice) {
       foreach($slice->getValue()->getArray() as $group) {
            foreach($group->getFragments() as $sliceItem) {
                if($sliceItem instanceof Prismic\Fragment\StructuredText) {
                 if($sliceItem->getFirstParagraph()) return $sliceItem;
                }  
            }
        }
    }
}

function get_Image_from_SliceZone($sliceZone) {
    foreach($sliceZone->getSlices() as $slice) {
       foreach($slice->getValue()->getArray() as $group) {
            foreach($group->getFragments() as $sliceItem) {
                if($sliceItem instanceof Prismic\Fragment\Image) {
                 if($sliceItem->getMain()) return $sliceItem->getMain();
                }  
            }
        }
    }
}

function get_StructuredText_with_title_from_Fragment($fragment) {
    if($fragment instanceof Prismic\Fragment\SliceZone) {
        return get_StructuredText_with_title_from_SliceZone($fragment);
    }
    elseif($fragment instanceof Prismic\Fragment\StructuredText) {
        if($fragment->getFirstHeading()) return $fragment;
    }
}

function get_StructuredText_with_description_from_Fragment($fragment) {
    if($fragment instanceof Prismic\Fragment\SliceZone) {
        return get_StructuredText_with_description_from_SliceZone($fragment);
    }
    elseif($fragment instanceof Prismic\Fragment\StructuredText) {
        if($fragment->getFirstParagraph()) return $fragment;
    }
}

function get_Image_from_fragment($fragment) {
    if($fragment instanceof Prismic\Fragment\SliceZone) {
        return get_Image_from_SliceZone($fragment);
    }
    elseif($fragment instanceof Prismic\Fragment\Image) {
        if($fragment->getMain()) return $fragment->getMain();
    }
    elseif($fragment instanceof Prismic\Fragment\StructuredText) {
        if($fragment->getFirstImage() && $fragment->getFirstImage()->getView()) return $fragment->getFirstImage()->getView();
    }
}

function get_title_from_StructuredText($structuredText) {
    if($structuredText) return $structuredText->getFirstHeading()->getText();
}

function get_description_from_StructuredText($structuredText) {
    if($structuredText) return $structuredText->getFirstParagraph()->getText();
}

function get_image_from_fragment_image($image) {
    if($image) return $image->getUrl();
}

function default_title() {
    global $WPGLOBAL, $loop;
    $prismic = $WPGLOBAL['prismic'];
    $doc = $loop->current_post();
    if (!$doc) {
        return;
    }
    $fragments = $doc->getFragments();
    foreach($fragments as $fragment) {
        $text = get_StructuredText_with_title_from_Fragment($fragment);
        $title = get_title_from_StructuredText($text);
        if($title) return $title;
    }
    return '';
}

function default_description() {
    global $WPGLOBAL, $loop;
    $prismic = $WPGLOBAL['prismic'];
    $doc = $loop->current_post();
    if (!$doc) {
        return;
    }
    $fragments = $doc->getFragments();
    foreach($fragments as $fragment) {
        $text = get_StructuredText_with_description_from_Fragment($fragment);
        $description = get_description_from_StructuredText($text);
        if($description) return $description;
    }
    return '';
}

function default_image() {
    global $WPGLOBAL, $loop;
    $prismic = $WPGLOBAL['prismic'];
    $doc = $loop->current_post();
    if (!$doc) {
        return;
    }
    $fragments = $doc->getFragments();
    foreach($fragments as $fragment) {
        $image = get_Image_from_fragment($fragment);
        $imageUrl = get_image_from_fragment_image($image);
        if($imageUrl) return $imageUrl;
    }
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
    $social = social();
    return !is_null($social) && !empty($social);
}

function socialPluginEnabled() {
    global $WPGLOBAL, $loop;
    $prismic = $WPGLOBAL['prismic'];
    $doc = $loop->current_post();
    if(!$doc) {
        return;
    }
    $socialEnabled = $doc->getText($doc->getType().'.social_cards_enabled');
    return ($socialEnabled == 'Enabled');
}

function open_graph_card_exists() {
    $socialSlices = social();
    if($socialSlices) {
        foreach($socialSlices as $slice) {
            if(is_open_graph_card($slice->getSliceType())) {
                return true;
            }
        }
    }
    return false;
}

function is_open_graph_card($sliceType) {
    return ($sliceType == 'general_card' || $sliceType == 'product_card' || $sliceType == 'place_card');
}

function open_graph_card_type() {
    $socialSlices = social();
    if($socialSlices) {
        foreach($socialSlices as $slice) {
            $sliceType = $slice->getSliceType();
            if(is_open_graph_card($sliceType)) {
                return $sliceType;
            }
        }
    }
}

function general_card() {
    $socialSlices = social();
    if($socialSlices) {
        foreach($socialSlices as $slice) {
            if($slice->getSliceType() == 'general_card') {
                return $slice->getValue();
            }
        }
    }
}
function general_card_title() { return general_card()->getArray()[0]['card_title'] ? general_card()->getArray()[0]['card_title']->getValue() : default_title(); }
function general_card_description() { return general_card()->getArray()[0]['card_description'] ? general_card()->getArray()[0]['card_description']->getValue() : default_description(); }
function general_card_image() { return general_card()->getArray()[0]['card_image'] && general_card()->getArray()[0]['card_image']->getMain() ? general_card()->getArray()[0]['card_image']->getMain()->getUrl() : default_image(); }


function product_card() {
    $socialSlices = social();
    if($socialSlices) {
        foreach($socialSlices as $slice) {
            if($slice->getSliceType() == 'product_card') {
                return $slice->getValue();
            }
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
    if($socialSlices) {
        foreach($socialSlices as $slice) {
            if($slice->getSliceType() == 'place_card') {
                return $slice->getValue();
            }
        }
    }
}
function place_card_title() { return place_card()->getArray()[0]['card_title'] ? place_card()->getArray()[0]['card_title']->getValue() : default_title(); }
function place_card_description() { return place_card()->getArray()[0]['card_description'] ? place_card()->getArray()[0]['card_description']->getValue() : default_description(); }
function place_card_latitude() { return place_card()->getArray()[0]['coordinates'] ? place_card()->getArray()[0]['coordinates']->getLatitude() : ''; }
function place_card_longitude() { return place_card()->getArray()[0]['coordinates'] ? place_card()->getArray()[0]['coordinates']->getLongitude() : ''; }
function place_card_image() { return place_card()->getArray()[0]['card_image'] ? place_card()->getArray()[0]['card_image']->getMain()->getUrl() : default_image(); }

function twitter_card_exists() {
    $socialSlices = social();
    if($socialSlices) {
        foreach($socialSlices as $slice) {
            if(is_twitter_card($slice->getSliceType())) {
                return true;
            }
        }
    }
    return false;
}

function is_twitter_card($sliceType) {
    return ($sliceType == 'twitter_app' || $sliceType == 'twitter_summary' || $sliceType == 'twitter_summary_large');
}

function twitter_card_type() {
    $socialSlices = social();
    if($socialSlices) {
        foreach($socialSlices as $slice) {
            $sliceType = $slice->getSliceType();
            if(is_twitter_card($sliceType)) {
                return $sliceType;
            }
        }
    }
}

function twitter_app() {
    $socialSlices = social();
    if($socialSlices) {
        foreach($socialSlices as $slice) {
            if($slice->getSliceType() == 'twitter_app') {
                return $slice->getValue();
            }
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
    if($socialSlices) {
        foreach($socialSlices as $slice) {
            if($slice->getSliceType() == 'twitter_summary') {
                return $slice->getValue();
            }
        }
    }
}
function twitter_summary_title() { return twitter_summary()->getArray()[0]['card_title'] ? twitter_summary()->getArray()[0]['card_title']->getValue() : default_title(); }
function twitter_summary_description() { return twitter_summary()->getArray()[0]['card_description'] ? twitter_summary()->getArray()[0]['card_description']->getValue() : default_description(); }
function twitter_summary_image() { return twitter_summary()->getArray()[0]['card_image'] ? twitter_summary()->getArray()[0]['card_image']->getMain()->getUrl() : default_image(); }
function twitter_summary_site() { return twitter_summary()->getArray()[0]['twitter_site'] ? twitter_summary()->getArray()[0]['twitter_site']->getValue() : ''; }

function twitter_summary_large() {
    $socialSlices = social();
    if($socialSlices) {
        foreach($socialSlices as $slice) {
            if($slice->getSliceType() == 'twitter_summary_large') {
                return $slice->getValue();
            }
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
    if($socialSlices) {
        foreach($socialSlices as $slice) {
            if($slice->getSliceType() == 'email') {
                return $slice->getValue();
            }
        }
    }
}
function email_title() { return (email() && email()->getArray()[0]['card_title']) ? email()->getArray()[0]['card_title']->getValue() : default_title(); }
function email_description() { return (email() && email()->getArray()[0]['card_description']) ? email()->getArray()[0]['card_description']->getValue() : default_description(); }

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