<?php

namespace App\Controller;

use App\Entity\Image;
use App\Entity\Video;
use App\Repository\BookmarkRepository;
use Embed\Embed;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Serializer\SerializerInterface;

class BookmarkController extends AbstractController
{
    private SerializerInterface $serializer;
    private BookmarkRepository $bookmarkRepository;

    public function __construct(SerializerInterface $serializer, BookmarkRepository $bookmarkRepository)
    {
        $this->serializer = $serializer;
        $this->bookmarkRepository = $bookmarkRepository;
    }

    #[Route('/bookmarks', name: 'bookmarks', methods: ['GET'])]
    public function getBookmarkList(): JsonResponse
    {
        $bookmarkList = $this->bookmarkRepository->findAll();
        $jsonBookmarkList = $this->serializer->serialize($bookmarkList, 'json', ['groups' => 'getBookmarks']);
        return new JsonResponse($jsonBookmarkList, Response::HTTP_OK, [], true);
    }

    #[Route('/bookmarks', name: "createBookmark", methods: ['POST'])]
    public function createBookmark(Request $request, UrlGeneratorInterface $urlGenerator): JsonResponse
    {
        $url = $request->request->get('url');
        $embed = new Embed();
        $info = $embed->get($url);

        $type = $info->getOEmbed()->get('type');

        if ($type === 'video') {
            $bookmark = new Video();
        } else {
            $bookmark = new Image();
        }

        $bookmark->setUrl($info->url);
        $bookmark->setType($info->getOEmbed()->get('type'));
        $bookmark->setProvider($info->providerName);
        $bookmark->setTitle($info->title);
        $bookmark->setAuthor($info->authorName);

        if ($info->publishedTime) {
            $bookmark->setPublishedOn($info->publishedTime);
        } elseif ($info->getOEmbed()->get('upload_date')) {
            $bookmark->setPublishedOn(new \DateTime($info->getOEmbed()->get('upload_date')));
        }

        if ($info->code->width) {
            $bookmark->setWidth($info->code->width);
        }

        if ($info->code->height) {
            $bookmark->setHeight($info->code->height);
        }

        if ($info->getOEmbed()->get('duration')) {
            $bookmark->setDuration($info->getOEmbed()->get('duration'));
        } elseif ($info->getMetas()->get('duration')) {
            $bookmark->setDuration($info->getMetas()->get('duration')[0]);
        }

        $this->bookmarkRepository->save($bookmark, true);

        return new JsonResponse(null, Response::HTTP_NO_CONTENT);
    }

    #[Route('/bookmarks/{id}', name: 'deleteBookmark', methods: ['DELETE'])]
    public function deleteBookmark(int $id): JsonResponse
    {
        $bookmark = $this->bookmarkRepository->find($id);

        if ($bookmark) {
            $this->bookmarkRepository->remove($bookmark, true);
            return new JsonResponse(null, Response::HTTP_NO_CONTENT);
        } else {
            return new JsonResponse(null,Response::HTTP_NOT_FOUND);
        }
    }
}
