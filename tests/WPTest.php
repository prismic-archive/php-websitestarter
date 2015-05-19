<?php

class WPTest extends LocalWebTestCase
{

    public function testBlogHome()
    {
        $this->client->get('/blog');
        $this->assertEquals(200, $this->client->response->status());

        $html = str_get_dom($this->client->response->body());

        // No errors, one blog post
        $this->assertEquals(2, count($html('div.blog-post')));

        // The sidebar is correct
        // $h3links = $html('.sidebar-section h3 a');
        // $first = $h3links[0];
        // $this->assertEquals('active', $first->class);
        // $last = $h3links[3];
        // $this->assertEquals('external', $last->class);
    }

    public function testPermalink()
    {
        $this->client->get('/blog/2014/11/27/first-post');
        $this->assertEquals(200, $this->client->response->status());

        $html = str_get_dom($this->client->response->body());
        $this->assertEquals(1, count($html('h1.blog-title')));
    }

    public function testArchive()
    {
        $this->client->get('/archive/2014/11');
        $this->assertEquals(200, $this->client->response->status());

        $html = str_get_dom($this->client->response->body());
        $this->assertEquals(1, count($html('div.blog-post')));
    }

    public function testSearch()
    {
        $this->client->get('/search', array('q' => 'sample'));
        $this->assertEquals(200, $this->client->response->status());

        $html = str_get_dom($this->client->response->body());
        $this->assertEquals(1, count($html('div.blog-post')));
    }

    public function testSearchNoResult()
    {
        $this->client->get('/search', array('q' => 'asdfasdfkjheiudwed'));
        $this->assertEquals(200, $this->client->response->status());

        $html = str_get_dom($this->client->response->body());
        $this->assertEquals(0, count($html('div.blog-post')));
    }

    public function testAuthor()
    {
        $this->client->get('/author/VHiMRicAACcAHSaw/erwan-loisant');
        $this->assertEquals(200, $this->client->response->status());

        $html = str_get_dom($this->client->response->body());
        $this->assertEquals('Second Blog Post', trim($html('.blog-post-title', 0)->getPlainText()));
    }

    public function testCategory()
    {
        $this->client->get('/category/personal');
        $this->assertEquals(200, $this->client->response->status());

        $html = str_get_dom($this->client->response->body());
        $this->assertEquals(1, count($html('div.blog-post')));
    }

    public function testPage()
    {
        $this->client->get('/sample-page');
        $this->assertEquals(200, $this->client->response->status());
        $html = str_get_dom($this->client->response->body());
        $this->assertEquals('Website Starter Sample Page', trim($html('h2', 0)->getPlainText()));
    }

    public function testTag()
    {
        $this->client->get('/tag/lorem');
        $this->assertEquals(200, $this->client->response->status());

        $html = str_get_dom($this->client->response->body());
        $this->assertEquals(1, count($html('div.blog-post')));
    }
}
