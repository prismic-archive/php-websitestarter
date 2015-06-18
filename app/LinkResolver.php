<?php

use Prismic\LinkResolver;

/**
 * The link resolver is the code building URLs for pages corresponding to
 * a Prismic document.
 *
 * If you want to change the URLs of your site, you need to update this class
 * as well as the routes in app.php.
 */
class StarterKitLinkResolver extends LinkResolver
{
    private $prismic;

    public function __construct($prismic)
    {
        $this->prismic = $prismic;
    }

    public function resolve($link)
    {
        if($link instanceof Prismic\Fragment\Link\DocumentLink) {
            foreach ($this->prismic->get_api()->bookmarks() as $name => $id) {
                if ($link->getId() == $id && ($name == 'home' || $name == 'skin')) {
                    return '/';
                }
                if ($link->getId() == $id && $name == 'bloghome') {
                    return '/blog';
                }
            }
            if ($link->isBroken()) {
                return;
            }
            if ($link->getType() == 'contact') {
                return '/contact';
            }
            if ($link->getType() == 'author') {
                return '/author/'.$link->getId().'/'.$link->getSlug();
            }
            if ($link->getType() == 'category') {
                return '/category/'.$link->getUid();
            }
            if ($link->getType() == 'post') {
                $date = $link->getDate('post.date');
                $year = $date ? $date->asDateTime()->format('Y') : '0';
                $month = $date ? $date->asDateTime()->format('m') : '0';
                $day = $date ? $date->asDateTime()->format('d') : '0';

                return '/blog/'.$year.'/'.$month.'/'.$day.'/'.urlencode($link->getUid());
            }

            if ($link->getType() == 'page') {
                $homeId = $this->prismic->get_api()->bookmark('home');
                if ($link->getId() == $homeId) {
                    return '/';
                } else {
                    $pieces = $this->prismic->page_path($link->getUid());
                    $pieces_encoded = array_map(function ($p) {
                        return urlencode($p);
                    }, $pieces);

                    return '/'.implode('/', $pieces_encoded);
                }
            }

            // This is a generic route for user-created document masks.
            // To have nicer looking URLs, it is recommended to add a specific rule for any mask you create.
            return '/document/' . $link->getId() . '/' . $link->getSlug();
        } else {
            return $link->getUrl();
        }
    }
}

