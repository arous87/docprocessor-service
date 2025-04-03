<?php
namespace App\Tests\E2E\Controller;

use App\Controller\PapersController;
use App\Service\PaperService;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\SerializerInterface;
use Doctrine\ORM\EntityManager;
use App\Entity\Paper;

class PapersControllerTest extends WebTestCase
{

    /**
     * helper to access EntityManager
     * @var EntityManager
     */
    protected $em;
    /**
     * Helper to access test Client
     * @var Client
     */
    protected $client;

    public function setUp(): void
    {
        $this->client = static::createClient();
        $this->client->disableReboot();
        $this->em = $this->client->getContainer()->get('doctrine.orm.entity_manager');
        $this->em->beginTransaction();
        $this->em->getConnection()->setAutoCommit(false);
        $this->em->getRepository(Paper::class)->removeAll();
        $this->em->commit();
    }

    public function testCreatePaperSuccess(): void
    {
        $paperData = [
            'user_id' => 100,
            'file_path' => "s3://user1_bucket/telekom_invoice1.pdf",
            'new_tags' => ['tag1', 'tag2'],
            'tags' => [],
            'content' => 'Test paper content',
        ];

        $uri = '/papers/create';
        $this->client->request(Request::METHOD_POST, $uri, [], [], [], json_encode($paperData));

        $response = $this->client->getResponse();

        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals(201, $response->getStatusCode());
        $this->assertStringContainsString('Paper created', $response->getContent());        
    }

    //TDOD : Add more tests for different scenarios

    //TODO : Add tests for different scenarios for update paper tags

}