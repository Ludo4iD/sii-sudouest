<?php

namespace App\Controller;

use App\Entity\Bookmark;
use App\Repository\BookmarkRepository;
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

    #[Route('/bookmarks', name:"createBookmark", methods: ['POST'])]
    public function createBookmark(Request $request, UrlGeneratorInterface $urlGenerator): JsonResponse
    {
        $bookmark = $this->serializer->deserialize($request->getContent(), Bookmark::class, 'json');
        $jsonBookmark = $this->serializer->serialize($bookmark, 'json', ['groups' => 'getBookmarks']);
        $location = $urlGenerator->generate('detailBookmark', ['id' => $bookmark->getId()], UrlGeneratorInterface::ABSOLUTE_URL);

        return new JsonResponse($jsonBookmark, Response::HTTP_CREATED, ["Location" => $location], true);
    }

    #[Route('/bookmarks/{id}', name: 'deleteBookmark', methods: ['DELETE'])]
    public function deleteBookmark(Bookmark $bookmark): JsonResponse
    {
        $this->bookmarkRepository->remove($bookmark);
        return new JsonResponse(null, Response::HTTP_NO_CONTENT);
    }
}
