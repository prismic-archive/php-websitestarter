<?php

/*
 * This is the main file of the application, including routing and controllers.
 *
 * $app is a Slim application instance, see the framework documentation for more details:
 * http://docs.slimframework.com/
 *
 * The order of the routes matter, as it will define the priority of routes. For that reason we
 * need to keep the more "generic" routes, such as the pages route, at the end of the file.
 *
 * If you decide to change the URLs, make sure to change StarterKitLinkResolver in includes/PrismicHelper.php
 * as well to make sures links in your site are correctly generated.
 */

use Prismic\Api;
use Prismic\LinkResolver;
use Prismic\Predicates;

use Suin\RSSWriter\Channel;
use Suin\RSSWriter\Feed;
use Suin\RSSWriter\Item;

use Mailgun\Mailgun;

date_default_timezone_set('UTC');

require_once 'includes/Loop.php';
require_once 'includes/http.php';

// Template tags
require_once 'includes/tags/general.php';
require_once 'includes/tags/navigation.php';
require_once 'includes/tags/posts.php';
require_once 'includes/tags/pages.php';
require_once 'includes/tags/author.php';
require_once 'includes/tags/archive.php';
require_once 'includes/tags/categories.php';
require_once 'includes/tags/stubs.php';

require_once 'includes/contact.php';
require_once 'includes/errorpage.php';

// Index page, built from the "home" bookmark from your repository
$app->get('/', function () use ($app, $prismic) {

    $homeId = $prismic->get_api()->bookmark('home');

    if (!$homeId) {
        not_found($app);
        return;
    }

    $home = $prismic->get_page($homeId);

    if ($home && $home->getType() == 'page') {
        $skin = $prismic->get_skin();
        render($app, 'page', array('single_post' => $home, 'skin' => $skin));
    } else if ($home && $home->getType() == 'bloghome') {
        $skin = $prismic->get_skin();
        $posts = $prismic->form()
               ->query(Predicates::at('document.type', 'post'))
               ->fetchLinks(
                   'post.date',
                   'category.name',
                   'author.full_name',
                   'author.first_name',
                   'author.surname',
                   'author.company'
               )
               ->page(current_page($app))
               ->orderings('[my.post.date desc]')
               ->submit();

        render($app, 'bloghome', array('bloghome' => $home, 'posts' => $posts, 'skin' => $skin));
    } else {
        not_found($app);
    }
});

// Author
$app->get('/author/:id/:slug', function ($id, $slug) use ($app,$prismic) {
    $author = $prismic->get_document($id);

    if (!$author) {
        not_found($app);

        return;
    }

    $posts = $prismic->form()
        ->query(
            Predicates::at('document.type', 'post'),
            Predicates::at('my.post.author', $id))
        ->fetchLinks(
            'post.date',
            'category.name',
            'author.full_name',
            'author.first_name',
            'author.surname',
            'author.company'
        )
        ->orderings('my.post.date desc')
        ->page(current_page($app))
        ->submit();

    $skin = $prismic->get_skin();

    render($app, 'author', array('posts' => $posts, 'author' => $author, 'skin' => $skin));
});

// Search results
$app->get('/search', function () use ($app,$prismic) {
    $q = $app->request()->params('q');

    $posts = $prismic->form()
        ->query(
            Predicates::at('document.type', 'post'),
            Predicates::fulltext('document', $q))
        ->fetchLinks(
            'post.date',
            'category.name',
            'author.full_name',
            'author.first_name',
            'author.surname',
            'author.company'
        )
        ->orderings('my.post.date desc')
        ->page(current_page($app))
        ->submit();

    $skin = $prismic->get_skin();

    render($app, 'search', array('posts' => $posts, 'skin' => $skin));
});

// Category
$app->get('/category/:uid', function ($uid) use ($app,$prismic) {
    $cat = $prismic->by_uid('category', $uid);

    if (!$cat) {
        not_found($app);

        return;
    }

    $posts = $prismic->form()
        ->query(
            Predicates::at('document.type', 'post'),
            Predicates::any('my.post.categories.link', array($cat->getId())))
        ->fetchLinks(
            'post.date',
            'category.name',
            'author.full_name',
            'author.first_name',
            'author.surname',
            'author.company'
        )
        ->orderings('my.post.date desc')
        ->page(current_page($app))
        ->submit();

    $skin = $prismic->get_skin();

    render($app, 'category', array('category' => $cat, 'posts' => $posts, 'skin' => $skin));
});

// Tag
$app->get('/tag/:tag', function ($tag) use ($app,$prismic) {
    $posts = $prismic->form()
        ->query(
            Predicates::at('document.type', 'post'),
            Predicates::any('document.tags', array($tag)))
        ->fetchLinks(
            'post.date',
            'category.name',
            'author.full_name',
            'author.first_name',
            'author.surname',
            'author.company'
        )
        ->orderings('my.post.date desc')
        ->page(current_page($app))
        ->submit();

    $skin = $prismic->get_skin();

    render($app, 'tag', array('posts' => $posts, 'tag' => $tag, 'skin' => $skin));
});

// Blog
$app->get('/blog', function () use ($app, $prismic) {
    $homeId = $prismic->get_api()->bookmark('home');
    $blogHomeId = $prismic->get_api()->bookmark('bloghome');

    if ($blogHomeId == null) {
        not_found($app);
    } else if ($blogHomeId == $homeId) {
        redirect('/');
        return;
    }

    $blogHome = $prismic->get_page($blogHomeId);
    if ($blogHome == null){
        not_found($app);
        return;
    }

    $posts = $prismic->form()
           ->query(Predicates::at('document.type', 'post'))
           ->fetchLinks(
               'post.date',
               'category.name',
               'author.full_name',
               'author.first_name',
               'author.surname',
               'author.company'
           )
           ->page(current_page($app))
           ->orderings('[my.post.date desc]')
           ->submit();
    $skin = $prismic->get_skin();

    render($app, 'bloghome', array('bloghome' => $blogHome, 'posts' => $posts, 'skin' => $skin));
});

// Archive
$app->get('/archive/:year(/:month(/:day))', function ($year, $month = null, $day = null) use ($app, $prismic) {
    global $WPGLOBAL;

    $posts = $prismic->archives(array(
        'year' => $year,
        'month' => $month,
        'day' => $day,
    ), current_page($app));

    $date = array('year' => $year, 'month' => $month, 'day' => $day);

    $skin = $prismic->get_skin();

    render($app, 'archive', array('posts' => $posts, 'date' => $date, 'skin' => $skin));
});

// Previews
$app->get('/preview', function () use ($app,$prismic) {
    $token = $app->request()->params('token');
    $url = $prismic->get_api()->previewSession($token, $prismic->linkResolver, '/');
    $app->setCookie(Prismic\PREVIEW_COOKIE, $token, time() + 1800, '/', null, false, false);
    $app->response->redirect($url ? $url : '/', 301);
});

// RSS Feed,
// using the Suin RSS Writer library
$app->get('/feed', function () use ($app, $prismic) {
    $blogUrl = $app->request()->getUrl();
    $posts = $prismic->get_posts(current_page($app))->getResults();
    $feed = new Feed();
    $channel = new Channel();
    $channel
        ->title($prismic->config('site.title'))
        ->description($prismic->config('site.description'))
        ->url($blogUrl)
        ->appendTo($feed);

    foreach ($posts as $post) {
        $item = new Item();
        $item->title($post->getText('post.title'))
            ->description($post->getHtml('post.body', $prismic->linkResolver))
            ->url($blogUrl.$prismic->linkResolver->resolveDocument($post))
            ->pubDate($post->getDate('post.date')->asEpoch())
            ->appendTo($channel);
    }

    echo $feed;
});

// --- DISQUS

$app->post('/disqus/threads/create', function () use ($app, $prismic) {

    $title = $app->request->post('title');
    $identifier = $app->request->post('identifier');

    $apiKey = $prismic->config('disqus.apikey');
    $apiSecret = $prismic->config('disqus.apisecret');
    $accessToken = $prismic->config('disqus.accesstoken');
    $forum = $prismic->config('disqus.forum');

    if ($apiKey && $apiSecret && $accessToken && $forum) {

        $httpClient = \Prismic\Api::defaultHttpAdapter();

        $data = array(
            'api_key' => $apiKey,
            'api_secret' => $apiSecret,
            'access_token' => $accessToken,
            'forum' => $forum,
            'title' => $title,
            'identifier' => $identifier
        );

        $app->response->headers->set('Content-Type', 'application/json');

        try {
            $response = $httpClient->post('https://disqus.com/api/3.0/threads/create.json', array(), $data);
            $json = json_decode($response->getBody());
            $app->response->setStatus($response->getStatusCode());
            $body = array(
                'code' => $json->code,
                'id' => $json->response->id,
            );
            $app->response->setBody(json_encode($body));
        } catch (\Ivory\HttpAdapter\HttpAdapterException $e) {
            $json = json_decode($e->getResponse()->getBody());
            $app->response->setStatus($e->getResponse()->getStatusCode());
            $app->response->setBody(json_encode(array('code' => $json->code)));
        }

    } else {
        $app->response->setStatus(400);
    }
});

$app->get('/disqus/threads/list', function () use ($app, $prismic) {

    $threadIds = $app->request->get('thread');
    $cursor = $app->request->get('cursor');
    $limit = $app->request->get('limit');

    $apiKey = $prismic->config('disqus.apikey');
    $apiSecret = $prismic->config('disqus.apisecret');
    $accessToken = $prismic->config('disqus.accesstoken');
    $forum = $prismic->config('disqus.forum');

    if ($apiKey && $apiSecret && $accessToken && $forum) {

        $httpClient = \Prismic\Api::defaultHttpAdapter();

        $param = array(
                'api_key' => $apiKey,
                'api_secret' => $apiSecret,
                'access_token' => $accessToken,
                'forum' => $forum,
                'thread' => $threadIds,
                'cursor' => $cursor,
                'limit' => $limit
        );

        $queryString = http_build_query($param);

        $url = 'https://disqus.com/api/3.0/threads/list.json?' . $queryString;

        $url = preg_replace('/%5B[0-9]+%5D/simU', '%5B%5D', $url);

        try {
            $response = $httpClient->get($url);
            $app->response->headers->set('Content-Type', 'application/json');
            $json = json_decode($response->getBody());
            $app->response->setStatus($response->getStatusCode());
            $app->response->setBody(json_encode($json));

        } catch (\Ivory\HttpAdapter\HttpAdapterException $e) {
            $app->response->setStatus($e->getResponse()->getStatusCode());
        }

    } else {
        $app->response->setStatus(400);
    }
});


$app->get('/disqus/threads/details', function () use ($app, $prismic) {

    $threadIdent = $app->request->get('thread:ident');

    $apiKey = $prismic->config('disqus.apikey');
    $apiSecret = $prismic->config('disqus.apisecret');
    $accessToken = $prismic->config('disqus.accesstoken');
    $forum = $prismic->config('disqus.forum');

    if ($apiKey && $apiSecret && $accessToken && $forum) {

        $httpClient = \Prismic\Api::defaultHttpAdapter();

        $param = array(
                'api_key' => $apiKey,
                'api_secret' => $apiSecret,
                'access_token' => $accessToken,
                'thread:ident' => $threadIdent,
                'forum' => $forum
        );

        $queryString = http_build_query($param);

        $url = 'https://disqus.com/api/3.0/threads/details.json?' . $queryString;
        $url = preg_replace('/%5B[0-9]+%5D/simU', '%5B%5D', $url);

        $app->response->headers->set('Content-Type', 'application/json');

        try {
            $response = $httpClient->get($url);
            $json = json_decode($response->getBody());
            $app->response->setStatus($response->getStatusCode());
            $app->response->setBody(json_encode($json));

        } catch (\Ivory\HttpAdapter\HttpAdapterException $e) {
            $json = json_decode($e->getResponse()->getBody());
            $app->response->setStatus($e->getResponse()->getStatusCode());
            $app->response->setBody(json_encode(array('code' => $json->code)));
        }

    } else {
        $app->response->setStatus(400);
    }
});

$app->post('/disqus/posts/create', function () use ($app, $prismic) {

    $authorName = $app->request->get('author_name');
    $authorEmail = $app->request->get('author_email');
    $message = $app->request->get('message');
    $threadId = $app->request->get('thread');

    $apiKey = $prismic->config('disqus.apikey');
    $apiSecret = $prismic->config('disqus.apisecret');
    $accessToken = $prismic->config('disqus.accesstoken');

    if ($apiKey && $apiSecret && $accessToken) {

        $httpClient = \Prismic\Api::defaultHttpAdapter();

        $param = array(
            'api_key' => "E8Uh5l5fHZ6gD8U3KycjAIAk46f68Zw7C6eW8WSjZvCLXebZ7p0r1yrYDrLilk2F",
            'author_name' => $authorName,
            'author_email' => $authorEmail,
            'message' => $message,
            'thread' => $threadId
        );

        $queryString = http_build_query($param);

        $url = 'https://disqus.com/api/3.0/posts/create.json?' . $queryString;
        $app->response->headers->set('Content-Type', 'application/json');

        try {
            $response = $httpClient->post($url);
            $json = json_decode($response->getBody());
            $app->response->setStatus($response->getStatusCode());
            $body = array(
                'code' => $json->code,
                'id' => $json->response->id,
            );
            $app->response->setBody(json_encode($body));

        } catch (\Ivory\HttpAdapter\HttpAdapterException $e) {
            $json = json_decode($e->getResponse()->getBody());
            $app->response->setStatus($e->getResponse()->getStatusCode());
            $app->response->setBody(json_encode(array('code' => $json->code)));
        }

    } else {
        $app->response->setStatus(400);
    }
});

$app->get('/disqus/posts/list', function () use ($app, $prismic) {

    $cursor = $app->request->get('cursor');
    $limit = $app->request->get('limit');
    $threadIds = $app->request->get('thread');
    $order = $app->request->get('order');

    $apiKey = $prismic->config('disqus.apikey');
    $apiSecret = $prismic->config('disqus.apisecret');
    $accessToken = $prismic->config('disqus.accesstoken');
    $forum = $prismic->config('disqus.forum');

    if ($apiKey && $apiSecret && $accessToken && $forum) {

        $httpClient = \Prismic\Api::defaultHttpAdapter();

        $param = array(
            'api_key' => $apiKey,
            'api_secret' => $apiSecret,
            'access_token' => $accessToken,
            'cursor' => $cursor,
            'limit' => $limit,
            'thread' => $threadIds,
            'order' => $order,
            'forum' => $forum
        );

        $queryString = http_build_query($param);

        $url = 'https://disqus.com/api/3.0/posts/list.json?' . $queryString;
        $url = preg_replace('/%5B[0-9]+%5D/simU', '%5B%5D', $url);

        $app->response->headers->set('Content-Type', 'application/json');

        try {
            $response = $httpClient->get($url);
            $json = json_decode($response->getBody());
            $app->response->setStatus($response->getStatusCode());
            $app->response->setBody(json_encode($json));

        } catch (\Ivory\HttpAdapter\HttpAdapterException $e) {
            $app->response->setStatus($e->getResponse()->getStatusCode());
        }
    }
});


// Post
$app->get('/blog/:year/:month/:day/:uid', function ($year, $month, $day, $uid) use ($app,$prismic) {

    $fetch = array(
        'post.date',
        'category.name',
        'author.full_name',
        'author.first_name',
        'author.surname',
        'author.company',
    );

    $doc = $prismic->by_uid('post', $uid, $fetch);

    if (!$doc) {
        not_found($app);

        return;
    }

    $permalink = $prismic->linkResolver->resolveDocument($doc);

    if ($app->request()->getPath() != $permalink) {
        // The user came from a URL with an older uid or date
        $app->response->redirect($permalink);

        return;
    }

    $skin = $prismic->get_skin();

    render($app, 'single', array('single_post' => $doc, 'skin' => $skin));
});

$app->post('/contact', function() use ($app, $prismic) {
  $resp = $app->response;
  $resp->headers->set('Content-Type', 'application/json');

  $domain = $prismic->config('mailgun.domain');

  $message = array(
    'from' => $app->request->post('sender'),
    'to' => $app->request->post('mailto'),
    'subject' => $app->request->post('subject'),
    'text' => $app->request->post('message'));

  $mailgun = new Mailgun($prismic->config('mailgun.apikey'));

  try {
      $res = $mailgun->sendMessage($domain, $message);
      $data = ($res->http_response_code == 200)
        ? array("success" => $res->http_response_body->message)
        : array("error" => $res->http_response_body->message);

      $resp->setBody(json_encode($data));
  } catch (Exception $e) {
      $resp->setBody(json_encode(array("error" => $e->getMessage())));
  }
});

// This is a generic route for user-created document masks.
// To have nicer looking URLs, it is recommended to add a specific route for each mask you create.
$app->get('/document/:id/:slug', function ($id, $slug) use($app, $prismic) {
    $doc = $prismic->get_document($id);

    if (!$doc) {
        not_found($app);
        return;
    }

    $permalink = $prismic->linkResolver->resolveDocument($doc);

    if ($app->request()->getPath() != $permalink) {
        // The user came from a URL with an older slug
        $app->response->redirect($permalink);
        return;
    }

    $skin = $prismic->get_skin();

    // Do we have a template for this type?
    $file_path = views_dir() . '/' . $doc->getType() . '.php';
    $template = file_exists($file_path) ? $doc->getType() : 'document';

    render($app, $template, array('single_post' => $doc, 'skin' => $skin));
});

// Page
// Since pages can have parent pages, the URL can contains several portions
$app->get('/:path+', function ($path) use($app, $prismic) {
    $page_uid = check_page_path($path, $prismic, $app);

    $skin = $prismic->get_skin();

    if ($page_uid) {
        $page = $prismic->by_uid('page', $page_uid);
        if (!$page) {
            not_found($app, $skin);

            return;
        }

        render($app, 'page', array('single_post' => $page, 'skin' => $skin));
    }
});
