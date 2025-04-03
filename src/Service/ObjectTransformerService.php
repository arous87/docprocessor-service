<?php

namespace App\Service;

use App\Entity\Paper;
use App\Entity\Tag;
use Symfony\Component\HttpFoundation\Request;
use App\Repository\TagRepository;
use App\Repository\PaperRepository;
use PhpParser\Node\Expr\Cast\String_;

class ObjectTransformerService
{
    /**
     * @var ValidatorService
     */
    private ValidatorService $validatorService;

    /**
     * @var TagRepository
     */
    private TagRepository $tagRepository; 

    /**
     * @var PaperRepository
     */
    private PaperRepository $paperRepository; 
    
    public function __construct(
        ValidatorService $validatorService, 
        TagRepository $tagRepository, 
        PaperRepository $paperRepository)
    {
        $this->validatorService = $validatorService;
        $this->tagRepository = $tagRepository;
        $this->paperRepository = $paperRepository;
    }

    /**
     * Transforms the request data into a Paper entity.
     *
     * @param Request $request
     * @return Paper
     * @throws \InvalidArgumentException
     */
    public function transformRequestToPaper(Request $request): Paper
    {
        $data = json_decode($request->getContent(), true);

        if (isset($data['id'])) {
            $paper = $this->paperRepository->find($data['id']);
        } else {
            $this->validatorService->validatePaperData($data);

            $paper = new Paper();
            $paper->setUserId($data['user_id']);
            $paper->setContent($data['content']);
            $paper->setAnchor($data['anchor'] ?? null);
            $paper->setFilePath($data['file_path'] ?? null);
        }

        if (isset($data['tags'])) {
            foreach ($data['tags'] as $tagId) {

                if (!is_int($tagId)) {
                    throw new \InvalidArgumentException('Invalid tag ID type');
                }

                $tag = $this->tagRepository->find($tagId);

                if (!$tag) {
                    throw new \InvalidArgumentException('Tag not found');
                }

                $paper->addTag($tag);
            }
        }

        if (isset($data['new_tags'])) {
            foreach ($data['new_tags'] as $tagData) {
                $tag = $this->transformStringToTag($tagData);
                $paper->addTag($tag);
            }
        }

        return $paper;
    }

    /**
     * Transforms the request data into a Tag entity.
     *
     * @param Request $request
     * @return Tag
     * @throws \InvalidArgumentException
     */
    public function transformRequestToTag(Request $request): Tag
    {
        $data = json_decode($request->getContent(), true);
        
        $this->validatorService->validateTagName($data);

        $tag = new Tag();
        $tag->setName($data);

        return $tag;
    }

    /**
     * Transforms an array of tag data into a Tag entity.
     *
     * @param string $tagName
     * @return Tag
     * @throws \InvalidArgumentException
     */
    public function transformStringToTag(string $tagName): Tag
    {
        $this->validatorService->validateTagName($tagName);

        $tag = new Tag();
        $tag->setName($tagName);

        return $tag;
    }
}