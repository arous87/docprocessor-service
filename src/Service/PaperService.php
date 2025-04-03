<?php

namespace App\Service;

use App\Entity\Paper;
use App\Entity\Tag;
use App\Repository\PaperRepository;
use App\Repository\TagRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityManager;
use Symfony\Component\HttpFoundation\Request;

class PaperService
{
    /**
     * @var ObjectTransformerService
     */
    private ObjectTransformerService $objectTransformerService;

    /**
     * @var EntityManager
     */
    private $entityManager;

    public function __construct(
        EntityManagerInterface $entityManager,
        ObjectTransformerService $objectTransformerService)
    {
        $this->objectTransformerService = $objectTransformerService;
        $this->entityManager = $entityManager;
    }

    /**
     * Creates a new Paper entity from the request data.
     *
     * @param Request $request
     * @return Paper
     * @throws \InvalidArgumentException
     */
    public function createPaper(Request $request): Paper
    {
        $paper = $this->objectTransformerService->transformRequestToPaper($request);

        $anchor = $this->extractAnchor($paper->getContent());
        $paper->setAnchor($anchor);

        $this->entityManager->persist($paper);
        $this->entityManager->flush();

        return $paper;
    }

    /**
     * Creates a new Paper entity from the request data.
     *
     * @param Request $request
     * @return Paper
     * @throws \InvalidArgumentException
     */
    public function updatePaperTags(Request $request): Paper
    {
        $paper = $this->objectTransformerService->transformRequestToPaper($request);

        $this->entityManager->persist($paper);
        $this->entityManager->flush();

        return $paper;
    }

    /**
     * Suggests tags for a given paper based on its anchor.
     *
     * @param Paper $paper
     * @return Tag[] Returns an array of suggested tags
     */
    public function suggestTags(Paper $paper): array
    {
        $similarPapersIds = $this->entityManager->getRepository(Paper::class)
                                            ->findIdsByAnchor($paper->getAnchor());

        if (count($similarPapersIds) > 0) {
            $similarPapersIds = array_diff($similarPapersIds, [$paper->getId()]);

            return $this->entityManager->getRepository(Paper::class)
                                    ->findDistinctTagsByPaperIds($similarPapersIds);
        }
        
        return [];
    }

    /**
     * Extracts the first three words from the content to use as an anchor.
     *
     * @param string $content
     * @return string
     */
    private function extractAnchor(string $content): string
    {
        // Use preg_split to split the string into words with a limit of 4 (3 words + 1 extra to stop early)
        $words = preg_split('/\s+/', trim($content), 4);

        return implode(' ', array_slice($words, 0, 3));
    }
}


