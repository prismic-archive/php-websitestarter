<?php

/**
 * This class is a stateful object to iterate on the posts present on a given page.
 * See how it is used in the default views for usage example.
 */
class Loop
{
    public $loop_index = -1;
    private $posts;

    public $page = 1;
    public $totalPages = 1;

    public function __construct()
    {
    }

    public function setResponse($response)
    {
        $this->setPosts($response->getResults());
        $this->page = $response->getPage();
        $this->totalPages = $response->getTotalPages();
    }

    public function setPosts($posts)
    {
        $this->posts = $posts;
    }

    public function reset()
    {
        $this->loop_index = -1;
    }

    public function increment()
    {
        $this->loop_index += 1;
    }

    public function has_more()
    {
        // -1 because we check before incrementing
        return $this->loop_index < (count($this->posts) - 1);
    }

    public function size()
    {
        return count($this->posts);
    }

    public function current_post()
    {
        if ($this->loop_index < 0 || $this->loop_index >= count($this->posts)) {
            return;
        }

        return $this->posts[$this->loop_index];
    }
}
