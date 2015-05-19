<?php

use Prismic\Api;
use Prismic\LinkResolver;
use Prismic\Predicates;

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
        foreach ($this->prismic->get_api()->bookmarks() as $name => $id) {
            if ($link->getId() == $id && $name == 'home') {
                return '/';
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

        $homeblogId = $this->prismic->get_api()->bookmark('homeblog');
        if ($link->getType() == 'homeblog' && $link->getId() == $homeblogId) {
            return '/blog';
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
    }
}

/**
 * This class contains helpers for the Prismic API. Most of these can't go
 * to the PHP kit because they are specific to the default Document Masks
 * or to the way we retrieve configuration in this project.
 */
class PrismicHelper
{
    private $app;
    public $linkResolver;

    public function __construct($app)
    {
        $this->app = $app;
        $this->linkResolver = new StarterKitLinkResolver($this);
    }

    private $api = null;

    public function pageSize()
    {
        return $this->app->config('page_size');
    }

    public function get_api()
    {
        $url = $this->app->config('prismic.url');
        $token = $this->app->config('prismic.token');
        if ($this->api == null) {
            $this->api = Api::get($url, $token);
        }

        return $this->api;
    }

    /**
     * Get the reference that should be used, in this order:
     *  - from the experiment cookies if present (A/B testing)
     *  - from the preview cookie if present (a writer clicked the "preview" button in the writing room)
     *  - defaults to the master release
     */
    public function get_ref()
    {
        $api = $this->get_api();
        $experimentCookie = $this->app->request()->cookies[Prismic\EXPERIMENTS_COOKIE];
        $previewCookie = $this->app->request()->cookies[Prismic\PREVIEW_COOKIE];
        if ($experimentCookie) {
            $experiments = $api->getExperiments();
            $experimentCookie = str_replace(' ', '%20', $experimentCookie);

            return $experiments->refFromCookie($experimentCookie);
        } elseif ($previewCookie != null) {
            return $previewCookie;
        } else {
            return $this->get_api()->master();
        }
    }

    public function form($pageSize = null)
    {
        if (!$pageSize) {
            $pageSize = $this->pageSize();
        }

        return $this->get_api()->forms()->everything
            ->pageSize($pageSize)
            ->ref($this->get_ref());
    }

    public function by_uid($type, $uid, $fetch = array())
    {
        $results =
          $this->form()
            ->fetchLinks($fetch)
            ->query(array(
                Predicates::at('my.'.$type.'.uid', $uid),
            ))
            ->submit()
            ->getResults();

        if (count($results) > 0) {
            return $results[0];
        }

        return;
    }

    public function get_prev_post($id)
    {
        $results =
            $this->form()
                 ->query(Predicates::at('document.type', 'post'))
                 ->set('after', $id)
                 ->pageSize(1)
                 ->orderings('[my.post.date desc, document.id desc]')
                 ->submit()
                 ->getResults();
        if (count($results) > 0) {
            return $results[0];
        }
    }

    public function get_next_post($id)
    {
        $results =
            $this->form()
                 ->query(Predicates::at('document.type', 'post'))
                 ->set('after', $id)
                 ->pageSize(1)
                 ->orderings('[my.post.date, document.id]')
                 ->submit()
                 ->getResults();
        if (count($results) > 0) {
            return $results[0];
        }
    }

    public function get_document($id)
    {
        $results = $this->form()
            ->query(array(Predicates::at('document.id', $id)))
            ->submit()
            ->getResults();
        if (count($results) > 0) {
            return $results[0];
        }

        return;
    }

    public function from_ids(array $documentIds)
    {
        return $this->form()
            ->query(array(Predicates::any('document.id', $documentIds)))
            ->submit();
    }

    public function refresh_path($path)
    {
        $pages = $this->form()
          ->query(Predicates::in('my.page.uid', $path))
          ->submit()
          ->getResults();

        $npath = array_map(function ($p) {
        return $p->getUid();
      }, $pages);

        if (count($path) == count($npath)) {
            return $npath;
        }

        return;
    }

    public function page_path($uid)
    {
        $homeId = $this->get_api()->bookmark('home');

        $pages = $this->form()
          ->query(Predicates::at('document.type', 'page'))
          ->submit()
          ->getResults();

        $parents = array();
        foreach ($pages as $p) {
            if ($p->getId() == $homeId) {
                continue;
            }
            $cs = $p->getGroup('page.children');
            if ($cs) {
                foreach ($cs->getArray() as $child) {
                    $link = $child->getLink('link');
                    if ($link instanceof \Prismic\Fragment\Link\DocumentLink) {
                        $parent_title = $p->getUid();
                        $parents[$link->getUid()] = $parent_title;
                    }
                }
            }
        }

        if ($uid == null) {
            return array();
        }

        $p = $uid;

        $path = array($uid);
        while (array_key_exists($p, $parents)) {
            $nextp = $parents[$p];
            array_push($path, $nextp);
            $p = $nextp;
        }

        return array_reverse($path);
    }

    public function archives($date, $page = 1)
    {
        if (!$date['month']) {
            $lowerBound = DateTime::createFromFormat('Y-m-d', ($date['year'] - 1).'-12-31');
            $upperBound = DateTime::createFromFormat('Y-m-d', ($date['year'] + 1).'-01-01');
        } elseif (!$date['day']) {
            $lowerBound = DateTime::createFromFormat('Y-m-d', $date['year'].'-'.$date['month'].'-01');
            $upperBound = clone $lowerBound;
            $lowerBound->modify('-1 day');
            $upperBound->modify('+1 month - 1 day');
        } else {
            $lowerBound = DateTime::createFromFormat('Y-m-d', $date['year'].'-'.$date['month'].'-'.$date['day']);
            $upperBound = clone $lowerBound;
            $lowerBound->modify('-1 day');
        }

        return $this->form()
            ->query(array(
                Predicates::at('document.type', 'post'),
                Predicates::dateAfter('my.post.date', $lowerBound),
                Predicates::dateBefore('my.post.date', $upperBound),
            ))
            ->orderings('[my.post.date desc]')
            ->page($page)
            ->submit();
    }

    public function get_bookmarks()
    {
        $bookmarks = $this->get_api()->bookmarks();
        $bkIds = array();
        foreach ($bookmarks as $name => $id) {
            array_push($bkIds, $id);
        }
        if (count($bkIds) == 0) {
            return array();
        }

        return $this->form()
            ->query(Predicates::any('document.id', $bkIds))
            ->orderings('[my.page.priority desc]')
            ->submit()
            ->getResults();
    }

    public function archive_link($year, $month = null, $day = null)
    {
        $url = '/archive/'.$year;
        if ($month) {
            $url .= '/'.$month;
        }
        if ($month && $day) {
            $url .= '/'.$day;
        }

        return $url;
    }

    public function get_calendar()
    {
        $calendar = array();
        $page = 1;
        do {
            $posts = $this->form(100)
                ->page($page)
                ->query(Predicates::at('document.type', 'post'))
                ->orderings('my.post.date desc')
                ->submit();
            foreach ($posts->getResults() as $post) {
                if (!$post->getDate('post.date')) {
                    continue;
                }
                $date = $post->getDate('post.date')->asDateTime();
                $key = $date->format('F Y');
                $last = end($calendar);
                if ($key != $last['label']) {
                    array_push($calendar, array(
                        'label' => $key,
                        'link' => $this->archive_link($date->format('Y'), $date->format('m')),
                    ));
                }
                $page++;
            }
        } while ($posts->getNextPage());

        return $calendar;
    }

    public function get_theme()
    {
        $themeId = $this->get_api()->bookmark('theme');

        if (!$themeId) {
            return null;
        }

        return $this->get_document($themeId);
    }

    public function get_404()
    {
        $notfoundId = $this->get_api()->bookmark('notfound');

        if (!$notfoundId) {
            return null;
        }

        return $this->get_document($notfoundId);
    }

    public function home()
    {
        $homeId = $this->get_api()->bookmark('home');
        if (!$homeId) {
            return array();
        }

        $home = $this->get_document($homeId);

        if (!$home || $home->getType() != 'page') {
            return array();
        }

        return array(
            'label' => 'Home',
            'url' => $this->linkResolver->resolveDocument($home),
            'external' => false,
            'children' => $this->getPageChildren($home),
        );
    }

    private function getPageChildren($page)
    {
        $result = array();
        if (!$page) {
            return $result;
        }
        $group = $page->getGroup('page.children');
        if (!$group) {
            return $result;
        }
        $children_ids = array();
        foreach ($group->getArray() as $item) {
            if (!isset($item['label']) || !isset($item['link'])) {
                continue;
            }
            $link = $item->getLink('link');
            if ($link instanceof \Prismic\Fragment\Link\DocumentLink) {
                array_push($children_ids, $link->getId());
            }
        }
        $children_by_id = array();
        foreach ($this->from_ids($children_ids)->getResults() as $page) {
            $children_by_id[$page->getId()] = $page;
        }
        foreach ($group->getArray() as $item) {
            if (!isset($item['label']) || !isset($item['link'])) {
                continue;
            }
            $label = $item->getText('label');
            $link = $item->getLink('link');
            $children = array();
            if ($link instanceof \Prismic\Fragment\Link\DocumentLink && !$link->isBroken()) {
                $doc = $children_by_id[$link->getId()];
                if (!$label) {
                    $label = 'No label';
                }
                $children = $this->getPageChildren($doc);
            }
            array_push($result, array(
                'label' => $label,
                'url' => $link->getUrl($this->linkResolver),
                'external' => $link instanceof \Prismic\Fragment\Link\WebLink,
                'children' => $children,
            ));
        }

        return $result;
    }
}
