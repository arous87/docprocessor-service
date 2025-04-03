<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\SerializerInterface;
use App\Service\PaperService;

class PapersController extends AbstractController
{

    /**
     * @var PaperService
     */
    private $paperService;

    private $serializer;

    public function __construct(PaperService $paperService, SerializerInterface $serializer)
    {
        $this->paperService = $paperService;
        $this->serializer = $serializer;
    }

    #[Route('/papers/create', name: 'create_paper', methods: ['POST'])]
    public function create(Request $request): Response
    {
        try {
            $paper = $this->paperService->createPaper($request);
            $tags = $this->paperService->suggestTags($paper);
        } catch (\InvalidArgumentException $e) {
            return new JsonResponse([
                'status' => 'Error',
                'message' => $e->getMessage(),
            ], Response::HTTP_BAD_REQUEST);
        }
        
        return new JsonResponse([
            'status' => 'Paper created',
            'tags' => $tags,
        ], Response::HTTP_CREATED);
    }

    #[Route('/papers/update-paper-tags', name: 'update_paper_tags', methods: ['POST'])]
    public function update(Request $request): Response
    {
        try {
            $paper = $this->paperService->updatePaperTags($request);
        } catch (\InvalidArgumentException $e) {
            return new JsonResponse([
                'status' => 'Error',
                'message' => $e->getMessage(),
            ], Response::HTTP_BAD_REQUEST);
        }
        
        return new JsonResponse([
            'status' => 'Paper Updated',
            'paper' => $this->serializer->serialize($paper, 'json')
        ], Response::HTTP_OK);
    }
}
