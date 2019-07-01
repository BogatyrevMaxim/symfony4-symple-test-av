<?php

namespace App\Controller;

use App\Message\NewNews;
use App\Message\RemoveNews;
use App\Message\UpdateNews;
use App\Service\NewsService;
use Exception;
use Nelmio\ApiDocBundle\Annotation\Model;
use Ramsey\Uuid\Uuid;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\ConstraintViolationInterface;
use Symfony\Component\Validator\ConstraintViolationListInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Swagger\Annotations as SWG;

/**
 * Class NewsController
 * @package App\Controller
 * @Route("/news")
 */
class NewsController extends AbstractController
{
    /**
     * @Route("/", methods={"GET"})
     * @param Request $request
     * @param NewsService $newsService
     * @return JsonResponse
     * @throws Exception
     *
     */
    public function listAction(Request $request, NewsService $newsService)
    {
        $json = $request->getContent();
        $this->checkJson($json);

        $news = $newsService->getList($json);
        $resultJson = $newsService->serializeNewsToJson($news);

        return JsonResponse::fromJsonString($resultJson);
    }

    /**
     * @param string $json
     * @throws Exception
     */
    private function checkJson(string $json)
    {
        if (empty($json)) {
            throw new Exception('Empty json', Response::HTTP_BAD_REQUEST);
        }
    }

    /**
     * @Route("/", methods={"PUT"})
     * @param Request $request
     * @param NewsService $newsService
     * @param ValidatorInterface $validator
     * @return JsonResponse
     * @throws Exception
     *
     * @SWG\Response(
     *     response=200,
     *     @Model(type=News::class)
     * )
     */
    public function createAction(Request $request, NewsService $newsService, ValidatorInterface $validator)
    {
        $json = $request->getContent();
        $this->checkJson($json);
        $news = $newsService->deserializeJsonToNews($json);
        $errors = $validator->validate($news);

        if (count($errors)) {
            $messages = $this->prepareErrorMessage($errors);

            return new JsonResponse(['errors' => $messages], Response::HTTP_BAD_REQUEST);
        }

        $news->setId(Uuid::uuid4());
        $this->dispatchMessage(new NewNews($news));

        $resultJson = $newsService->serializeNewsToJson($news);

        return JsonResponse::fromJsonString($resultJson);
    }

    /**
     * @param ConstraintViolationListInterface $errors
     * @return array
     */
    private function prepareErrorMessage(ConstraintViolationListInterface $errors)
    {
        $messages = [];
        /** @var ConstraintViolationInterface $error */
        foreach ($errors as $error) {
            $messages[] = sprintf('%s: %s', $error->getPropertyPath(), $error->getMessage());
        }

        return $messages;
    }

    /**
     * @Route("/{id}", methods={"POST"})
     * @param string $id
     * @param Request $request
     * @param ValidatorInterface $validator
     * @param NewsService $newsService
     * @return JsonResponse
     * @throws Exception
     *
     * @SWG\Response(
     *     response=200,
     *     @Model(type=News::class)
     * )
     */
    public function updateAction(string $id, Request $request, ValidatorInterface $validator, NewsService $newsService)
    {
        $json = $request->getContent();
        $this->checkJson($json);
        $newsNew = $newsService->deserializeJsonToNews($json);
        $errors = $validator->validate($newsNew);

        if (count($errors)) {
            $messages = $this->prepareErrorMessage($errors);

            return new JsonResponse(['errors' => $messages], Response::HTTP_BAD_REQUEST);
        }

        $newsOld = $newsService->get($id);
        if (!$newsOld) {
            return new JsonResponse(['errors' => 'News not found'], Response::HTTP_NOT_FOUND);
        }

        $newsNew->setId($id)
            ->setCreatedAt($newsOld->getCreatedAt());

        $this->dispatchMessage(new UpdateNews($newsNew));

        $resultJson = $newsService->serializeNewsToJson($newsNew);

        return JsonResponse::fromJsonString($resultJson);
    }

    /**
     * @Route("/{id}", methods={"DELETE"})
     * @param string $id
     * @param NewsService $newsService
     * @return JsonResponse
     *
     * @SWG\Response(
     *     response=200,
     *     @Model(type=News::class)
     * )
     */
    public function removeAction(string $id, NewsService $newsService)
    {
        $news = $newsService->get($id);
        if (!$news) {
            return new JsonResponse(['errors' => 'News not found'], Response::HTTP_NOT_FOUND);
        }

        $this->dispatchMessage(new RemoveNews($news));

        $resultJson = $newsService->serializeNewsToJson($news);

        return JsonResponse::fromJsonString($resultJson);
    }

    /**
     * @Route("/{id}", methods={"GET"})
     * @param string $id
     * @param NewsService $newsService
     * @return JsonResponse
     *
     * @SWG\Response(
     *     response=200,
     *     @Model(type=News::class)
     * )
     */
    public function getAction(string $id, NewsService $newsService)
    {
        $news = $newsService->get($id);
        if (!$news) {
            return new JsonResponse(['errors' => 'News not found'], Response::HTTP_NOT_FOUND);
        }

        $resultJson = $newsService->serializeNewsToJson($news);

        return JsonResponse::fromJsonString($resultJson);
    }
}