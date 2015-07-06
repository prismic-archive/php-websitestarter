<?php

function social() {
    global $WPGLOBAL, $loop;
    $prismic = $WPGLOBAL['prismic'];
    $doc = $loop->current_post();
    if(!$doc) {
        return;
    }
    $socialData = $doc->getSliceZone($doc->getType.'.social');
    if($socialData) {
        foreach ($$socialData.getSlices() as $socialSlice) {
         return $socialSlice->getValue();
        }
    }
}

function cards_data_by_social_service($socialSlice) {
    switch ($socialSlice->getSliceType()) {
        case 'general_card':
            get_general_data($socialSlice);
        break;

        case 'twitter':
           get_twitter_data($socialSlice);
        break;
        case 'open_graph':
            get_open_graph_data($socialSlice);
        break;

        case 'email':
            get_email_data($socialSlice);
        break;
        
        default:
            return;
    }
}

function twitter() {
    $social Data()
}


function page_social_data_twitter()
{
    global $WPGLOBAL, $loop;
    $prismic = $WPGLOBAL['prismic'];
    $doc = $loop->current_post();
    if (!$doc) {
        return;
    }
    $social = $doc->getSliceZone($doc->getType().'.social');
    if ($social) {
        foreach ($social->getSlices() as $slice) {
            if($slice->getSliceType() == 'twitter') {
                return $slice->getValue();
            }
        }
    }
}

function page_social_twitter_card() {
    $twitterData = page_social_data_twitter();
    return $twitterData->getArray()[0]['twitter_card']->getValue();
}

function page_social_twitter_creator() {
    $twitterData = page_social_data_twitter();
    return $twitterData->getArray()[0]['twitter_creator']->getValue();
}

function page_social_twitter_site() {
    $twitterData = page_social_data_twitter();
    return $twitterData->getArray()[0]->GetFragments()['twitter_site']->getValue();
}

function page_social_twitter_images_gallery() {
    $twitterData = page_social_data_twitter();
    $arrayImage = [];
    foreach ($twitterData->getArray()[0]->GetFragments() as $elem) {
        if($elem instanceof Prismic\Fragment\Image) {
            $arrayImage[] = $elem->getMain()->getUrl();
        }
    }
    return $arrayImage;
}

function page_social_cards_description()
{
    global $WPGLOBAL, $loop;
    $prismic = $WPGLOBAL['prismic'];
    $doc = $loop->current_post();
    if (!$doc) {
        return;
    }
    return $doc->getText($doc->getType().'.cards_description');
}

function page_social_cards_title()
{
    global $WPGLOBAL, $loop;
    $prismic = $WPGLOBAL['prismic'];
    $doc = $loop->current_post();
    if (!$doc) {
        return;
    }
    return $doc->getText($doc->getType().'.cards_title');
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