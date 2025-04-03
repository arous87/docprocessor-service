<?php

namespace App\Tests\Service;

use App\Entity\Paper;
use App\Repository\PaperRepository;
use App\Repository\TagRepository;
use App\Service\ObjectTransformerService;
use App\Service\ValidatorService;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;
use Mockery;

class ObjectTransformerServiceTest extends TestCase
{
    protected $validator;
    protected $paperRepository;
    protected $tagRepository;

    protected function setUp(): void
    {
        parent::setUp();

        $this->validator = Mockery::mock(ValidatorService::class);
        $this->paperRepository = Mockery::mock(PaperRepository::class);
        $this->tagRepository = Mockery::mock(TagRepository::class);
    }

    public function testTransformRequestToPaper(): void
    {
        $service = new ObjectTransformerService($this->validator, $this->paperRepository, $this->tagRepository);

        $request = new Request([], [], [], [], [], [], json_encode(['content' => 'Test content']));
        $paper = $service->transformRequestToPaper($request);

        $this->assertInstanceOf(Paper::class, $paper);
        $this->assertEquals('Test content', $paper->getContent());
    }
}