<?php

namespace App\Repository;

use App\Entity\Activity;
use Carbon\Carbon;
use DateInterval;
use DateTime;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Collections\Criteria;
use Doctrine\ORM\Query\QueryException;
use Doctrine\Persistence\ManagerRegistry;
use Exception;

/**
 * @extends ServiceEntityRepository<Activity>
 *
 * @method Activity|null find($id, $lockMode = null, $lockVersion = null)
 * @method Activity|null findOneBy(array $criteria, array $orderBy = null)
 * @method Activity[]    findAll()
 * @method Activity[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ActivityRepository extends ServiceEntityRepository
{
	public function __construct(ManagerRegistry $registry)
	{
		parent::__construct($registry, Activity::class);
	}

	public function save(Activity $entity, bool $flush = false): void
	{
		$this->getEntityManager()->persist($entity);

		if ($flush) {
			$this->getEntityManager()->flush();
		}
	}

	public function remove(Activity $entity, bool $flush = false): void
	{
		$this->getEntityManager()->remove($entity);

		if ($flush) {
			$this->getEntityManager()->flush();
		}
	}

	/**
	 * @throws QueryException
	 */
	public function findAllTopicalForAdmin(): array
	{
		$criteria = Criteria::create()
			->where(Criteria::expr()->gt('start', Carbon::yesterday()->endOfDay()));

		return $this->createQueryBuilder('a')
			->addCriteria($criteria)
			->getQuery()
			->getResult();
	}

	/**
	 * @param DateTime $year
	 * @return array
	 * @throws QueryException
	 */
	public function findAllExpiredByYearForAdmin(DateTime $year): array
	{
		$criteria = Criteria::create()
			->where(Criteria::expr()->lt('start', new DateTime('today')))
			->andWhere(Criteria::expr()->gte('start', $year))
			->andWhere(Criteria::expr()->lt('start', (clone $year)->add(new DateInterval('P1Y'))));

		return $this->createQueryBuilder('a')
			->addCriteria($criteria)
			->getQuery()
			->getResult();
	}

	/**
	 * @param string $param
	 * @return array
	 * @throws Exception
	 */
	public function findAllByTypeOrTagForAdmin(string $param): array
	{
		return $this->createQueryBuilder('a')
			->innerJoin('a.tags', 't')
			->where('a.type = :type')
			->orWhere('t.name = :tag')
			->setParameter('type', $param)
			->setParameter('tag', $param)
			->getQuery()
			->getResult();
	}

	/**
	 * @param string $param
	 * @return array
	 * @throws Exception
	 */
	public function findAllByInstitutionGuidOrNameForAdmin(string $param): array
	{
		return $this->createQueryBuilder('a')
			->join('a.institution', 'i')
			->where('i.guid = :param')
			->orWhere('i.name = :param')
			->setParameter('param', $param)
			->orderBy('a.start', 'desc')
			->getQuery()
			->getResult();
	}

	/**
	 * @param string $param
	 * @return array
	 * @throws Exception
	 */
	public function findAllTopicalByInstitutionGuidOrNameForAdmin(string $param): array
	{
		$criteria = Criteria::create()
			->where(Criteria::expr()->gt('start', Carbon::yesterday()->endOfDay()));

		return $this->createQueryBuilder('a')
			->join('a.institution', 'i')
			->where('i.guid = :param')
			->orWhere('i.name = :param')
			->addCriteria($criteria)
			->setParameter('param', $param)
			->orderBy('a.start', 'desc')
			->getQuery()
			->getResult();
	}

	/**
	 * @param string $param
	 * @return array
	 * @throws Exception
	 */
	public function findAllExpiredByInstitutionGuidOrNameForAdmin(string $param): array
	{
		$criteria = Criteria::create()
			->where(Criteria::expr()->lt('start', Carbon::today()));

		return $this->createQueryBuilder('a')
			->join('a.institution', 'i')
			->where('i.guid = :param')
			->orWhere('i.name = :param')
			->addCriteria($criteria)
			->setParameter('param', $param)
			->orderBy('a.start', 'desc')
			->getQuery()
			->getResult();
	}

	/**
	 * @param string $guid
	 * @return array
	 * @throws Exception
	 */
	public function findAllForUser(string $guid): array
	{
		return $this->createQueryBuilder('a')
			->innerJoin('a.user', 'u')
			->where('u.guid = :guid')
			->setParameter('guid', $guid)
			->getQuery()
			->getResult();
	}

	/**
	 * @param string $guid
	 * @return array
	 * @throws QueryException
	 */
	public function findAllTopicalForUser(string $guid): array
	{
		$criteria = Criteria::create()
			->where(Criteria::expr()->gt('start', Carbon::yesterday()->endOfDay()));

		return $this->createQueryBuilder('a')
			->innerJoin('a.user', 'u')
			->addCriteria($criteria)
			->andWhere('u.guid = :guid')
			->setParameter('guid', $guid)
			->getQuery()
			->getResult();
	}

	/**
	 * @param string $guid
	 * @param DateTime $year
	 * @return array
	 * @throws QueryException
	 */
	public function findAllExpiredByYearForUser(string $guid, DateTime $year): array
	{
		$criteria = Criteria::create()
			->where(Criteria::expr()->lt('start', new DateTime('today')))
			->andWhere(Criteria::expr()->gte('start', $year))
			->andWhere(Criteria::expr()->lt('start', (clone $year)->add(new DateInterval('P1Y'))));

		return $this->createQueryBuilder('a')
			->innerJoin('a.user', 'u')
			->addCriteria($criteria)
			->andWhere('u.guid = :guid')
			->setParameter('guid', $guid)
			->getQuery()
			->getResult();
	}

	/**
	 * @param string $param
	 * @param string $guid
	 * @return array
	 * @throws Exception
	 */
	public function findAllByTypeOrTagForUser(string $param, string $guid): array
	{
		return $this->createQueryBuilder('a')
			->innerJoin('a.user', 'u')
			->innerJoin('a.tags', 't')
			->where('a.type = :type')
			->orWhere('t.name = :tag')
			->andWhere('u.guid = :guid')
			->setParameter('type', $param)
			->setParameter('tag', $param)
			->setParameter('guid', $guid)
			->getQuery()
			->getResult();
	}

	/**
	 * @param string $param
	 * @param string $guid
	 * @return array
	 * @throws Exception
	 */
	public function findAllByInstitutionGuidOrNameForUser(string $param, string $guid): array
	{
		return $this->createQueryBuilder('a')
			->join('a.institution', 'i')
			->join('a.user', 'u')
			->where('i.guid = :param')
			->orWhere('i.name = :param')
			->andWhere('u.guid = :guid')
			->setParameter('param', $param)
			->setParameter('guid', $guid)
			->orderBy('a.start', 'desc')
			->getQuery()
			->getResult();
	}

	/**
	 * @param string $param
	 * @param string $guid
	 * @return array
	 * @throws Exception
	 */
	public function findAllTopicalByInstitutionGuidOrNameForUser(string $param, string $guid): array
	{
		$criteria = Criteria::create()
			->andWhere(Criteria::expr()->gt('a.start', Carbon::yesterday()->endOfDay()));

		return $this->createQueryBuilder('a')
			->join('a.institution', 'i')
			->join('a.user', 'u')
			->where('i.guid = :param')
			->orWhere('i.name = :param')
			->andWhere('u.guid = :guid')
			->addCriteria($criteria)
			->setParameter('param', $param)
			->setParameter('guid', $guid)
			->orderBy('a.start', 'desc')
			->getQuery()
			->getResult();
	}

	/**
	 * @param string $param
	 * @param string $guid
	 * @return array
	 * @throws Exception
	 */
	public function findAllExpiredByInstitutionGuidOrNameForUser(string $param, string $guid): array
	{
		$criteria = Criteria::create()
			->andWhere(Criteria::expr()->lt('a.start', Carbon::today()));

		return $this->createQueryBuilder('a')
			->join('a.institution', 'i')
			->join('a.user', 'u')
			->where('i.guid = :param')
			->orWhere('i.name = :param')
			->andWhere('u.guid = :guid')
			->addCriteria($criteria)
			->setParameter('param', $param)
			->setParameter('guid', $guid)
			->orderBy('a.start', 'desc')
			->getQuery()
			->getResult();
	}

	/**
	 * @param string $guid
	 * @return float|int|mixed|string
	 * @throws QueryException
	 */
	public function findOfTodayForUser(string $guid): mixed
	{
		$criteria = Criteria::create()
			->where(Criteria::expr()->gt('a.start', Carbon::yesterday()->endOfDay()))
			->andWhere(Criteria::expr()->lt('a.start', Carbon::tomorrow()->startOfDay()));

		return $this->createQueryBuilder('a')
			->join('a.user', 'u')
			->where('u.guid = :guid')
			->addCriteria($criteria)
			->setParameter('guid', $guid)
			->orderBy('a.start')
			->getQuery()
			->getResult();
	}

	/**
	 * @param string $guid
	 * @return mixed
	 * @throws QueryException
	 */
	public function findOfNextSevenDaysForUser(string $guid): mixed
	{
		$criteria = Criteria::create()
			->where(Criteria::expr()->gt('a.start', Carbon::today()->endOfDay()))
			->andWhere(Criteria::expr()->lte('a.start', Carbon::today()->addDays(7)->endOfDay()));

		return $this->createQueryBuilder('a')
			->join('a.user', 'u')
			->where('u.guid = :guid')
			->addCriteria($criteria)
			->setParameter('guid', $guid)
			->orderBy('a.start')
			->getQuery()
			->getResult();
	}

	/**
	 * @param string $guid
	 * @return mixed
	 * @throws QueryException
	 */
	public function findOfNextThirtyDaysForUser(string $guid): mixed
	{
		$criteria = Criteria::create()
			->where(Criteria::expr()->gt('a.start', Carbon::today()->addDays(7)->endOfDay()))
			->andWhere(Criteria::expr()->lte('a.start', Carbon::today()->addDays(37)->endOfDay()));

		return $this->createQueryBuilder('a')
			->join('a.user', 'u')
			->where('u.guid = :guid')
			->addCriteria($criteria)
			->setParameter('guid', $guid)
			->orderBy('a.start')
			->getQuery()
			->getResult();
	}

	/**
	 * @param string $param
	 * @param string $guid
	 * @return array
	 * @throws Exception
	 */
	public function findOfTodayByInstitutionGuidOrNameForUser(string $param, string $guid): array
	{
		$criteria = Criteria::create()
			->where(Criteria::expr()->gt('a.start', Carbon::yesterday()->endOfDay()))
			->andWhere(Criteria::expr()->lt('a.start', Carbon::tomorrow()->startOfDay()));

		return $this->createQueryBuilder('a')
			->join('a.institution', 'i')
			->join('a.user', 'u')
			->where('i.guid = :param')
			->orWhere('i.name = :param')
			->andWhere('u.guid = :guid')
			->addCriteria($criteria)
			->setParameter('param', $param)
			->setParameter('guid', $guid)
			->orderBy('a.start')
			->getQuery()
			->getResult();
	}

	/**
	 * @param string $param
	 * @param string $guid
	 * @return array
	 * @throws Exception
	 */
	public function findOfNextSevenDaysByInstitutionGuidOrNameForUser(string $param, string $guid): array
	{
		$criteria = Criteria::create()
			->where(Criteria::expr()->gt('a.start', Carbon::today()->endOfDay()))
			->andWhere(Criteria::expr()->lte('a.start', Carbon::today()->addDays(7)->endOfDay()));

		return $this->createQueryBuilder('a')
			->join('a.institution', 'i')
			->join('a.user', 'u')
			->where('i.guid = :param')
			->orWhere('i.name = :param')
			->andWhere('u.guid = :guid')
			->addCriteria($criteria)
			->setParameter('param', $param)
			->setParameter('guid', $guid)
			->orderBy('a.start')
			->getQuery()
			->getResult();
	}

	/**
	 * @param string $param
	 * @param string $guid
	 * @return array
	 * @throws Exception
	 */
	public function findOfNextThirtyDaysByInstitutionGuidOrNameForUser(string $param, string $guid): array
	{
		$criteria = Criteria::create()
			->where(Criteria::expr()->gt('a.start', Carbon::today()->addDays(7)->endOfDay()))
			->andWhere(Criteria::expr()->lte('a.start', Carbon::today()->addDays(37)->endOfDay()));

		return $this->createQueryBuilder('a')
			->join('a.institution', 'i')
			->join('a.user', 'u')
			->where('i.guid = :param')
			->orWhere('i.name = :param')
			->andWhere('u.guid = :guid')
			->addCriteria($criteria)
			->setParameter('param', $param)
			->setParameter('guid', $guid)
			->orderBy('a.start')
			->getQuery()
			->getResult();
	}

//    /**
//	 * @param string $orderType ASC or DESC
//     * @return Activity[] Returns an array of ActivityTags objects sorted by start date
//     */
//    public function sortByDate(string $orderType): array
//    {
//        return $this->createQueryBuilder('a')
//            ->orderBy('a.start', $orderType)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?ActivityTags
//    {
//        return $this->createQueryBuilder('a')
//            ->andWhere('a.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
