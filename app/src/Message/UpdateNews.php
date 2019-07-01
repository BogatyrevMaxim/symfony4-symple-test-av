<?php

namespace App\Message;

use App\Entity\News;

class UpdateNews
{
    /** @var News */
    private $news;

    public function __construct(News $news)
    {
        $this->news = $news;
    }

    /**
     * @return News
     */
    public function getNews(): News
    {
        return $this->news;
    }
}