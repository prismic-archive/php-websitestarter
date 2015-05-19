<?php

/**
 * Most of these functions accept a $document as parameter.
 * For the single page, the document can be omitted.
 *
 * get_* function will return the values, others will output them.
 *
 * The way the tags are written can lead to the same request being done several times,
 * but it's OK because the Prismic kit has a built-in cache (APC).
 */

// Loop management

function have_posts()
{
    global $loop;

    return $loop->has_more();
}

function count_posts()
{
    global $loop;

    return $loop->size();
}

function the_post()
{
    global $loop;
    $loop->increment();
}

function rewind_posts()
{
    global $loop;
    $loop->reset();
}

function the_ID()
{
    global $loop;
    echo $loop->current_post()->getId();
}

function the_permalink()
{
    echo get_permalink();
}

function get_permalink($id = null)
{
    global $WPGLOBAL, $loop;
    $prismic = $WPGLOBAL['prismic'];
    $post = $id ? $prismic->get_document($id) : $loop->current_post();

    return $post ? $prismic->linkResolver->resolveDocument($post) : null;
}

function the_title()
{
    global $loop;
    $doc = $loop->current_post();
    if ($doc) {
        echo htmlentities($doc->getText($doc->getType().'.title'));
    }
}

function the_title_attribute()
{
    return the_title();
}

function the_date_link($format = 'F, jS Y')
{
    global $loop;
    $date = get_date('post.date', $loop->current_post());
    if (!$date) {
        return;
    }
    if ($date instanceof \Prismic\Fragment\Date) {
        $date = $date->asDateTime();
    }
    $label = date_format($date, $format);
    $url = archive_link($date->format('Y'), $date->format('m'), $date->format('d'));
    echo '<a class="created-at" href="'.$url.'">'.$label.'</a>';
}

function get_the_date($format = 'F, jS Y')
{
    global $loop;
    $date = get_date('post.date', $loop->current_post());
    if (!$date) {
        return;
    }
    if ($date instanceof \Prismic\Fragment\Date) {
        $date = $date->asDateTime();
    }

    return date_format($date, $format);
}

function get_the_time($format = 'g:iA')
{
    global $loop;
    $date = get_date('post.date', $loop->current_post());
    if (!$date) {
        return;
    }
    if ($date instanceof \Prismic\Fragment\Date) {
        $date = $date->asDateTime();
    }

    return date_format($date, $format);
}

function the_content()
{
    global $WPGLOBAL, $loop;
    $prismic = $WPGLOBAL['prismic'];
    $doc = $loop->current_post();
    if (!$doc) {
        return;
    }
    $body = $doc->getStructuredText($doc->getType().'.body');
    if ($body) {
        $htmlSerializer = function ($element, $content) use (&$doc) {
            if ($element instanceof \Prismic\Fragment\Block\ParagraphBlock) {
                $threadIdentifer = hash('md5', $doc->getId().'#'.$content);
                $label = $element->getLabel();
                if ($label == 'image-label') {
                    return;
                }
                $class = !is_null($label) ? 'class="'.$label.'"' : '';

                return '<p '.$class.'data-disqium-thread-id="'.$threadIdentifer.'">'.$content.'<p>';
            }

            return;
        };
        echo $body->asHtml($prismic->linkResolver, $htmlSerializer);
    }
}

function the_post_thumbnail($size = 'main', $attr = array())
{
    global $WPGLOBAL, $loop;
    $doc = $loop->current_post();
    if ($size == 'full') {
        $size = 'main';
    }
    if (!$doc) {
        return;
    }
    $image = $doc->getImage($doc->getType().'.image');
    if ($image) {
        echo $image->getView($size)->asHtml();
    }
}

function post_thumbnail_url($size = 'main')
{
    global $WPGLOBAL, $loop;
    $doc = $loop->current_post();
    if ($size == 'full') {
        $size = 'main';
    }
    if (!$doc) {
        return;
    }
    $image = $doc->getImage($doc->getType().'.image');
    if ($image) {
        return $image->getView($size)->getUrl();
    }
}

function has_post_thumbnail()
{
    global $WPGLOBAL, $loop;
    $doc = $loop->current_post();

    return ($doc != null && $doc->getImage($doc->getType().'.image') != null);
}

function get_the_excerpt()
{
    global $WPGLOBAL, $loop;
    $prismic = $WPGLOBAL['prismic'];
    $doc = $loop->current_post();
    if (!$doc) {
        return;
    }
    if ($doc instanceof Author) {
        return;
    }
    // Plain text to avoid open tag at the end
    $body = $doc->getStructuredText($doc->getType().'.body');
    if (!$body) {
        return '';
    }

    return substr($body->asText(), 0, 300).'...';
}

function get_post_type()
{
    global $loop;
    $doc = $loop->current_post();
    if (!$doc) {
        return;
    }

    return $doc->getType();
}

function the_excerpt()
{
    echo get_the_excerpt();
}

function get_the_tags()
{
    global $loop;
    $doc = $loop->current_post();
    if (!$doc) {
        return array();
    }

    return $doc->getTags();
}

function the_tags($before = '', $sep = '', $after = '')
{
    echo get_the_tag_list($before, $sep, $after);
}

function get_the_tag_list($before = '', $sep = '', $after = '')
{
    $tags = get_the_tags();
    if (count($tags) == 0) {
        return;
    }
    $result = $before;
    $result .= implode($sep, array_map(function ($tag) use ($sep) {
        return '<a href="/tag/'.$tag.'">'.$tag.'</a>';
    }, $tags));
    $result .= $after;

    return '<span class="tags">'.$result.'</span>';
}

// Other tags

function single_post()
{
    global $WPGLOBAL;
    if (isset($WPGLOBAL['single_post'])) {
        return $WPGLOBAL['single_post'];
    }

    return;
}

function document_url($document)
{
    global $WPGLOBAL;
    $prismic = $WPGLOBAL['prismic'];

    return $prismic->linkResolver->resolveDocument($document);
}

function link_to_post($post)
{
    return '<a href="'.document_url($post).'">'.post_title($post).'</a>';
}

function single_post_title($prefix = '', $display = true)
{
    global $WPGLOBAL;
    $prismic = $WPGLOBAL['prismic'];
    if (!single_post()) {
        return;
    }
    $result = $prefix.single_post()->getText('post.title');
    if ($display) {
        echo htmlentities($result);
    } else {
        return $result;
    }
}

function single_post_shortlede_text()
{
    global $WPGLOBAL, $loop;
    $prismic = $WPGLOBAL['prismic'];
    $doc = $loop->current_post();
    if (!$doc) {
        return;
    }
    if ($doc instanceof Author) {
        return;
    }

    return $doc->getText('post.shortlede');
}

function single_post_shortlede()
{
    $shortlede = single_post_shortlede_text();
    if ($shortlede) {
        $text = mb_strlen($shortlede) > 200 ? mb_substr($shortlede, 0, 200).'...' : $shortlede;
        echo '<p class="shortlede">'.$text.'</p>';
    }
}

function single_post_date_text($format = 'F, jS Y')
{
    global $loop;
    $date = get_date('post.date', $loop->current_post());
    if ($date) {
        if ($date instanceof \Prismic\Fragment\Date) {
            $date = $date->asDateTime();
        }
        return date_format($date, $format);
    }
}

function single_post_date($format = 'F, jS Y')
{
    $date = single_post_date_text($format);
    if ($date) {
        echo '<p class="date">'.$date.'</p>';
    }
}

function single_post_author_text()
{
    global $WPGLOBAL, $loop;
    $prismic = $WPGLOBAL['prismic'];
    $post = $loop->current_post();
    if (!$post) {
        return;
    }
    $author = $post->getLink($post->getType().'.author');
    if (!$author) {
        return;
    }
    return $author->getText('author.full_name');
}

function single_post_author()
{
    $author = single_post_author_text();

    echo '<p class="author">'.$author.'</p>';
}

function get_html($field, $document = null)
{
    global $WPGLOBAL;
    $prismic = $WPGLOBAL['prismic'];
    $doc = $document ? $document : current_document();
    if ($doc->get($field)) {
        return $doc->get($field)->asHtml($prismic->linkResolver);
    }

    return;
}

function get_date($field, $doc)
{
    if (!$doc) {
        return;
    }
    if ($doc instanceof Author) {
        return;
    }

    return $doc->getDate($field);
}
