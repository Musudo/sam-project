<?php

namespace App\Repository;

use App\Entity\Contact;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Exception;

/**
 * @extends ServiceEntityRepository<Contact>
 *
 * @method Contact|null find($id, $lockMode = null, $lockVersion = null)
 * @method Contact|null findOneBy(array $criteria, array $orderBy = null)
 * @method Contact[]    findAll()
 * @method Contact[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ContactRepository extends ServiceEntityRepository
{
	public function __construct(ManagerRegistry $registry)
	{
		parent::__construct($registry, Contact::class);
	}

	public function save(Contact $entity, bool $flush = false): void
	{
		$this->getEntityManager()->persist($entity);

		if ($flush) {
			$this->getEntityManager()->flush();
		}
	}

	public function remove(Contact $entity, bool $flush = false): void
	{
		$this->getEntityManager()->remove($entity);

		if ($flush) {
			$this->getEntityManager()->flush();
		}
	}

	/**
	 * @param string $param
	 * @return array
	 * @@throws Exception
	 */
	public function findAllByContactInfoForAdmin(string $param): array
	{
		return $this->createQueryBuilder('c')
			->join('c.institutions', 'i')
			->join('i.users', 'u')
			->where('c.firstName like :param')
			->orWhere('c.lastName like :param')
			->orWhere('c.email1 like :param')
			->orWhere('c.email2 like :param')
			->orWhere('c.jobTitle like :param')
			->orWhere('concat(c.firstName, \' \', c.lastName) like :param')
			->orWhere('i.name like :param')
			->orWhere('i.city like :param')
			->orWhere('i.zipCode like :param')
			->orWhere('i.clientId like :param')
			->setParameter('param', $param)
			->getQuery()
			->getResult();
	}

	/**
	 * @param string $param
	 * @return array
	 * @@throws Exception
	 */
	public function findAllByInstitutionGuidOrNameForAdmin(string $param): array
	{
		return $this->createQueryBuilder('c')
			->join('c.institutions', 'i')
			->join('i.users', 'u')
			->where('i.guid = :param')
			->orWhere('i.name = :param')
			->setParameter('param', $param)
			->orderBy('c.firstName', 'ASC')
			->addOrderBy('c.lastName', 'ASC')
			->getQuery()
			->getResult();
	}

	/**
	 * @param string $guid
	 * @return float|int|mixed|string
	 * @@throws Exception
	 */
	public function findAllForUser(string $guid): mixed
	{
		return $this->createQueryBuilder('c')
			->join('c.institutions', 'i')
			->join('i.users', 'u')
			->where('u.guid = :guid')
			->setParameter('guid', $guid)
			->getQuery()
			->getResult();
	}

	/**
	 * @param string $param
	 * @param string $guid
	 * @return array
	 * @@throws Exception
	 */
	public function findAllByContactInfoForUser(string $param, string $guid): array
	{
		return $this->createQueryBuilder('c')
			->join('c.institutions', 'i')
			->join('i.users', 'u')
			->where('c.firstName like :param')
			->orWhere('c.lastName like :param')
			->orWhere('c.email1 like :param')
			->orWhere('c.email2 like :param')
			->orWhere('c.jobTitle like :param')
			->orWhere('concat(c.firstName, \' \', c.lastName) like :param')
			->andWhere('u.guid = :guid')
			->setParameter('param', $param)
			->setParameter('guid', $guid)
			->getQuery()
			->getResult();
	}

	/**
	 * @param string $param
	 * @param string $guid
	 * @return array
	 * @@throws Exception
	 */
	public function findAllByInstitutionGuidOrNameForUser(string $param, string $guid): array
	{
		return $this->createQueryBuilder('c')
			->join('c.institutions', 'i')
			->join('i.users', 'u')
			->where('i.guid = :param')
			->orWhere('i.name = :param')
			->andWhere('u.guid = :guid')
			->setParameter('param', $param)
			->setParameter('guid', $guid)
			->orderBy('c.firstName', 'ASC')
			->addOrderBy('c.lastName', 'ASC')
			->getQuery()
			->getResult();
	}

	/**
	 * @param string $guid
	 * @return array
	 * @@throws Exception
	 */
	public function findInstitutionsOfContact(string $guid): array
	{
		return $this->createQueryBuilder('c')
			->innerJoin('c.institutions', 'i')
			->where('c.guid = :guid')
			->setParameter('guid', $guid)
			->getQuery()
			->getResult();
	}

	/**
	 * @param string $guid
	 * @return array
	 * @@throws Exception
	 */
	public function findAllByActivity(string $guid): array
	{
		return $this->createQueryBuilder('c')
			->innerJoin('c.activities', 'a')
			->where('a.guid = :guid')
			->setParameter('guid', $guid)
			->orderBy('c.firstName', 'ASC')
			->addOrderBy('c.lastName', 'ASC')
			->getQuery()
			->getResult();
	}

//	/**
//	 * @param string $nameType first name or last name
//	 * @param string $orderType ASC or DESC
//	 * @return array Returns an array of Contact objects sorted by firstName or lastName
//	 */
//	public function sortByNameType(string $orderType, string $nameType = 'firstName'): array
//	{
//		$field = 'c.' . $nameType;
//		return $this->createQueryBuilder('c')
//			->orderBy($field, $orderType)
//			->getQuery()
//			->getResult();
//	}

//    public function findOneBySomeField($value): ?Contact
//    {
//        return $this->createQueryBuilder('c')
//            ->andWhere('c.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
