<?php
namespace App\Tests\Service;

use App\Entity\Paper;
use App\Service\PaperService;
use App\Service\ObjectTransformerService;
use Doctrine\ORM\EntityManagerInterface;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;

class PaperServiceTest extends TestCase
{
    public function testCreatePaper(): void
    {
        $entityManagerMock = $this->createMock(EntityManagerInterface::class);
        $objectTransformerMock = $this->createMock(ObjectTransformerService::class);

        $objectTransformerMock->method('transformRequestToPaper')->willReturn(new Paper());

        $service = new PaperService($entityManagerMock, $objectTransformerMock);

        $request = new Request([], [], [], [], [], [], json_encode(['content' => 'Test content']));
        $paper = $service->createPaper($request);

        $this->assertInstanceOf(Paper::class, $paper);
    }

    public function testSuggestTags(): void
    {
        $entityManagerMock = $this->createMock(EntityManagerInterface::class);
        $objectTransformerMock = $this->createMock(ObjectTransformerService::class);

        $service = new PaperService($entityManagerMock, $objectTransformerMock);

        $paper = new Paper();
        $paper->setAnchor('test-anchor');

        $entityManagerMock->method('getRepository')->willReturnSelf();
        $entityManagerMock->method('findIdsByAnchor')->willReturn([1, 2, 3]);

        $tags = $service->suggestTags($paper);

        $this->assertIsArray($tags);
    }
}