<?php

namespace App\MessageHandler;

use App\Message\UpdateNews;
use App\Repository\NewsRepository;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

class UpdateNewsHandler implements MessageHandlerInterface
{
    /**
     * @var NewsRepository
     */
    private $newsRepository;

    public function __construct(NewsRepository $newsRepository)
    {
        $this->newsRepository = $newsRepository;
    }

    public function __invoke(UpdateNews $message)
    {
        $news = $message->getNews();
        // не контролирует поля, например позволяет менять дату создания
        // todo ограничить
        $news = $this->newsRepository->merge($news);
        $this->newsRepository->save($news);
    }
}