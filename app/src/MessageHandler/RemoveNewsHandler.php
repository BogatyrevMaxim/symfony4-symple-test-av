<?php

namespace App\MessageHandler;

use App\Message\RemoveNews;
use App\Repository\NewsRepository;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

class RemoveNewsHandler implements MessageHandlerInterface
{
    /**
     * @var NewsRepository
     */
    private $newsRepository;

    public function __construct(NewsRepository $newsRepository)
    {
        $this->newsRepository = $newsRepository;
    }

    public function __invoke(RemoveNews $message)
    {
        $news = $message->getNews();
        $this->newsRepository->merge($news);
        $this->newsRepository->remove($news);
    }
}