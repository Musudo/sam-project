<?php

namespace App\Tests\Repository;

use App\Entity\Tag;
use App\Repository\TagRepository;
use Carbon\Carbon;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Tools\SchemaTool;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Persistence\ObjectManager;
use PHPUnit\Framework\TestCase;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class TagRepositoryTest extends KernelTestCase
{
	private EntityManager $entityManager;

	protected function setUp(): void
	{
		$kernel = self::bootKernel();

		$this->entityManager = $kernel->getContainer()
			->get('doctrine')
			->getManager();
	}

	public function testSearchByName(): void
	{
		$tag = $this->entityManager
			->getRepository(Tag::class)
			->findOneBy(['name' => 'Tag 99']);

		$this->assertSame('Tag 99', $tag->getName());
	}

	protected function tearDown(): void
	{
		parent::tearDown();

		// doing this is recommended to avoid memory leaks
		$this->entityManager->close();
		$this->entityManager = null;
	}

//	public function testSaveAndFind(): void
//	{
//		$tag = new Tag();
//		$tag->setName('Test tag 99');
//
//		$this->assertNotEmpty($tag);
//
//		// Now, mock the repository so it returns the mock of the employee
//		$employeeRepository = $this->createMock(TagRepository::class);
//		$employeeRepository->expects($this->any())
//			->method('find')
//			->willReturn($tag);


//		$foundTag = $this->tagRepository->find($tag->getId());

//		$this->assertSame($tag->getId(), $foundTag->getId());
//		$this->assertSame($tag->getName(), $foundTag->getName());
//	}

//	public function testRemove(): void
//	{
//		$tag = new Tag();
//		$tag->setName('Some new tag');
//
//		$this->tagRepository->save($tag, true);
//
//		$this->tagRepository->remove($tag, true);
//
//		$foundTag = $this->tagRepository->find($tag->getId());
//
//		$this->assertNull($foundTag);
//	}

//	protected function tearDown(): void
//	{
//		$schemaTool = new SchemaTool($this->entityManager);
//		$schemaTool->dropSchema([$this->entityManager->getClassMetadata(Tag::class)]);
//
//		$this->entityManager->close();
//		$this->entityManager = null;
//
//		parent::tearDown();
//	}
}
