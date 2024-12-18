<?php

namespace App\Repository;

use App\Entity\Institution;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Exception;

/**
 * @extends ServiceEntityRepository<Institution>
 *
 * @method Institution|null find($id, $lockMode = null, $lockVersion = null)
 * @method Institution|null findOneBy(array $criteria, array $orderBy = null)
 * @method Institution[]    findAll()
 * @method Institution[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class InstitutionRepository extends ServiceEntityRepository
{
	public function __construct(ManagerRegistry $registry)
	{
		parent::__construct($registry, Institution::class);
	}

	public function save(Institution $entity, bool $flush = false): void
	{
		$this->getEntityManager()->persist($entity);

		if ($flush) {
			$this->getEntityManager()->flush();
		}
	}

	public function remove(Institution $entity, bool $flush = false): void
	{
		$this->getEntityManager()->remove($entity);

		if ($flush) {
			$this->getEntityManager()->flush();
		}
	}

	/**
	 * @param string $param
	 * @return float|int|mixed|string
	 * @throws Exception
	 */
	public function findByInfoForAdmin(string $param): mixed
	{
		return $this->createQueryBuilder('i')
			->where('i.name like :param')
			->orWhere('i.clientId like :param')
			->orWhere('i.country like :param')
			->orWhere('i.city like :param')
			->orWhere('i.zipCode like :param')
			->setParameter('param', $param)
			->getQuery()
			->getResult();
	}

	/**
	 * @param string $guid
	 * @return float|int|mixed|string
	 * @throws Exception
	 */
	public function findAllForUser(string $guid): mixed
	{
		return $this->createQueryBuilder('i')
			->join('i.users', 'u')
			->where('u.guid = :guid')
			->setParameter('guid', $guid)
			->getQuery()
			->getResult();
	}

	/**
	 * @param string $param
	 * @param string $guid
	 * @return float|int|mixed|string
	 * @throws Exception
	 */
	public function findByInfoForUser(string $param, string $guid): mixed
	{
		return $this->createQueryBuilder('i')
			->join('i.users', 'u')
			->where('i.name like :param')
			->orWhere('i.clientId like :param')
			->orWhere('i.country like :param')
			->orWhere('i.city like :param')
			->orWhere('i.zipCode like :param')
			->andWhere('u.guid = :guid')
			->setParameter('param', $param)
			->setParameter('guid', $guid)
			->getQuery()
			->getResult();
	}

//    /**
//     * @return Institution[] Returns an array of Institution objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('i')
//            ->andWhere('i.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('i.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Institution
//    {
//        return $this->createQueryBuilder('i')
//            ->andWhere('i.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
