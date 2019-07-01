<?php

namespace App\MessageHandler;

use App\Message\NewNews;
use App\Repository\NewsRepository;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

class NewNewsHandler implements MessageHandlerInterface
{
    /**
     * @var NewsRepository
     */
    private $newsRepository;

    public function __construct(NewsRepository $newsRepository)
    {
        $this->newsRepository = $newsRepository;
    }

    public function __invoke(NewNews $message)
    {
        $this->newsRepository->save($message->getNews());
    }
}