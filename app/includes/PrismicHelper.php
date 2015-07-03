<?php

use Prismic\Api;
use Prismic\Predicates;

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
        // We keep that here as an optimization
        $this->allPages = null;
    }

    private $api = null;

    private $conf = null;

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

    public function get_conf()
    {
        $apiEndpoint = $this->app->config('prismic.url');
        $apiConfEndpoint = mb_substr($apiEndpoint, 0, count($apiEndpoint) - 4) . 'conf';
        $token = $this->app->config('prismic.token');
        if ($token && is_null($this->conf)) {
            try {
                $httpClient = \Prismic\Api::defaultHttpAdapter();
                $queryString = http_build_query(array("access_token" => $token));
                $response = $httpClient->get($apiConfEndpoint.'?'.$queryString);
                $this->conf = json_decode($response->getBody(), true);
            } catch (\Ivory\HttpAdapter\HttpAdapterException $e) {
            }
        }

        return $this->conf;
    }

    public function config($key)
    {
        $conf = $this->get_conf();
        return ($conf && isset($conf[$key])) ? $conf[$key] : $this->app->config($key);
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
        $blogId = $this->get_api()->bookmark('bloghome');

        $pages = $this->get_all_pages();

        $parents = array();
        foreach ($pages as $p) {
            if ($p->getId() == $homeId) {
                continue;
            }
            $cs = $p->getGroup($p->getType() . '.children');
            if ($cs) {
                foreach ($cs->getArray() as $child) {
                    $link = $child->getLink('link');
                    if ($link instanceof \Prismic\Fragment\Link\DocumentLink) {
                        $parent_title = ($p->getId() == $blogId) ? 'blog' : $p->getUid();
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
            $posts = $this->form()
                ->page($page)
                ->query(Predicates::at('document.type', 'post'))
                ->fetch('post.date')
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

    public function get_skin()
    {
        $skinId = $this->get_api()->bookmark('skin');

        if (!$skinId) {
            return null;
        }

        return $this->get_document($skinId);
    }

    public function get_404()
    {
        $notfoundId = $this->get_api()->bookmark('notfound');

        if (!$notfoundId) {
            return null;
        }

        return $this->get_document($notfoundId);
    }

    public function get_all_pages()
    {
        if ($this->allPages == null) {
            $has_more = true;
            $this->allPages = array();
            $p = 0;
            while ($has_more) {
                $response = $this->form(20)
                          ->query(Predicates::any('document.type', array('page', 'bloghome')))
                          ->page($p++)
                          ->submit();
                foreach ($response->getResults() as $page) {
                    $this->allPages[$page->getId()] = $page;
                }
                $has_more = ($response->getNextPage() != null);
            }
        }
        return $this->allPages;
    }

    public function get_page($id)
    {
        $pages = $this->get_all_pages();
        return array_key_exists($id, $pages) ? $pages[$id] : null;
    }

    public function home()
    {
        $homeId = $this->get_api()->bookmark('home');
        if (!$homeId) {
            return array();
        }
        $pages = $this->get_all_pages();
        $home = $pages[$homeId];

        if (!$home) {
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
        $pages = $this->get_all_pages();

        $result = array();
        if (!$page) {
            return $result;
        }
        $group = $page->getGroup($page->getType() . '.children');
        if (!$group) {
            return $result;
        }
        $children_by_id = array();
        foreach ($group->getArray() as $item) {
            if (!isset($item['label']) || !isset($item['link'])) {
                continue;
            }
            $link = $item->getLink('link');
            if ($link instanceof \Prismic\Fragment\Link\DocumentLink) {
                if(isset($pages[$link->getId()])) {
                    $children_by_id[$link->getId()] = $pages[$link->getId()];
                }
            }
        }
        foreach ($group->getArray() as $item) {
            $label = $item->getText('label');
            $link = $item->getLink('link');
            if ($link == null) {
                continue;
            }
            $children = array();
            if ($link instanceof \Prismic\Fragment\Link\DocumentLink && !$link->isBroken()) {
                if(isset($children_by_id[$link->getId()])) {
                    $doc = $children_by_id[$link->getId()];
                    if (!$label) {
                        $label = 'No label';
                    }
                    $children = $this->getPageChildren($doc);
                }
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
