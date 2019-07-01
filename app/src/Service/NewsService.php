<?php

namespace App\Service;

use App\Criteria\NewsCriteria;
use App\Entity\News;
use App\Repository\NewsRepository;
use Symfony\Component\PropertyInfo\Extractor\ReflectionExtractor;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\DateTimeNormalizer;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * Class NewsService
 * @package App\Service
 */
class NewsService
{
    /**
     * @var NewsRepository
     */
    private $newsRepository;

    /**
     * @var Serializer
     */
    private $serializer;

    /**
     * @var ValidatorInterface
     */
    private $validator;

    /**
     * NewsService constructor.
     * @param NewsRepository $newsRepository
     * @param ValidatorInterface $validator
     */
    public function __construct(NewsRepository $newsRepository, ValidatorInterface $validator)
    {
        $this->newsRepository = $newsRepository;
        $encoders = [new JsonEncoder()];
        $normalizers = [new DateTimeNormalizer(), new ObjectNormalizer(null, null, null, new ReflectionExtractor()),];

        $this->serializer = new Serializer($normalizers, $encoders);
        $this->validator = $validator;
    }

    /**
     * @param string $json
     * @return News[]
     */
    public function getList(string $json)
    {
        $criteria = $this->serializer->deserialize($json, NewsCriteria::class, 'json');

        return $this->newsRepository->getList($criteria);
    }

    /**
     * @param News|array $news
     * @return string
     */
    public function serializeNewsToJson($news)
    {
        return $this->serializer->serialize($news, 'json');
    }

    /**
     * @param string $json
     * @return News
     */
    public function deserializeJsonToNews(string $json)
    {
        return $this->serializer->deserialize($json, News::class, 'json');
    }

    /**
     * @param News $news
     */
    public function save(News $news)
    {
        $this->newsRepository->save($news);
    }

    /**
     * @param string $id
     * @return News|null
     */
    public function get(string $id): ?News
    {
        return $this->newsRepository->find($id);
    }
}