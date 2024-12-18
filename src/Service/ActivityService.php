<?php

namespace App\Service;

use App\Entity\Activity;
use App\Exception\ActivityNotFoundException;
use App\Exception\ResourceNotCreatedException;
use App\Exception\ResourceNotDeletedException;
use App\Repository\ActivityRepository;
use App\Service\Interface\IActivityService;
use DateTime;
use Doctrine\ORM\Query\QueryException;
use Exception;
use Symfony\Component\Security\Core\Security;

class ActivityService implements IActivityService
{
	private string $guid;

	/**
	 * @param ActivityRepository $activityRepository
	 * @param Security $security
	 */
	public function __construct(private readonly ActivityRepository $activityRepository,
								private readonly Security           $security)
	{
		$this->guid = $this->security->getUser()->getUserIdentifier();
	}

	/**
	 * @return array
	 */
	public function findAllForAdmin(): array
	{
		try {
			return $this->activityRepository->findAll();
		} catch (Exception $e) {
			throw new ActivityNotFoundException("Failed to find activities");
		}
	}

	/**
	 * @return array
	 */
	public function findAllTopicalForAdmin(): array
	{
		try {
			return $this->activityRepository->findAllTopicalForAdmin();
		} catch (QueryException $e) {
			throw new ActivityNotFoundException("Failed to find topical activities");
		}
	}

	/**
	 * @param DateTime $year
	 * @return array
	 */
	public function findAllExpiredByYearForAdmin(DateTime $year): array
	{
		try {
			return $this->activityRepository->findAllExpiredByYearForAdmin($year);
		} catch (QueryException $e) {
			throw new ActivityNotFoundException("Failed to find expired activities");
		}
	}

	/**
	 * @param string $param
	 * @return array
	 */
	public function findAllByTypeOrTagForAdmin(string $param): array
	{
		try {
			return $this->activityRepository->findAllByTypeOrTagForAdmin($param);
		} catch (Exception $e) {
			throw new ActivityNotFoundException("Failed to find activities");
		}
	}

	/**
	 * @param string $param
	 * @return array
	 */
	public function findAllByInstitutionGuidOrNameForAdmin(string $param): array
	{
		try {
			return $this->activityRepository->findAllByInstitutionGuidOrNameForAdmin($param);
		} catch (Exception $e) {
			throw new ActivityNotFoundException("Failed to find all activities by institution");
		}
	}

	public function findAllTopicalByInstitutionGuidOrNameForAdmin(string $param): array
	{
		try {
			return $this->activityRepository->findAllTopicalByInstitutionGuidOrNameForAdmin($param);
		} catch (Exception $e) {
			throw new ActivityNotFoundException("Failed to find all topical activities by institution");
		}
	}

	public function findAllExpiredByInstitutionGuidOrNameForAdmin(string $param): array
	{
		try {
			return $this->activityRepository->findAllExpiredByInstitutionGuidOrNameForAdmin($param);
		} catch (Exception $e) {
			throw new ActivityNotFoundException("Failed to find all expired activities by institution");
		}
	}

	/**
	 * @return array
	 */
	public function findAllForUser(): array
	{
		try {
			return $this->activityRepository->findAllForUser($this->guid);
		} catch (Exception $e) {
			throw new ActivityNotFoundException("Failed to find activities for user");
		}
	}

	/**
	 * @return array
	 */
	public function findAllTopicalForUser(): array
	{
		try {
			return $this->activityRepository->findAllTopicalForUser($this->guid);
		} catch (Exception $e) {
			throw new ActivityNotFoundException("Failed to find topical activities for user");
		}
	}

	/**
	 * @param DateTime $year
	 * @return array
	 */
	public function findAllExpiredByYearForUser(DateTime $year): array
	{
		try {
			return $this->activityRepository->findAllExpiredByYearForUser($this->guid, $year);
		} catch (Exception $e) {
			throw new ActivityNotFoundException("Failed to find expired activities for user");
		}
	}

	/**
	 * @param string $param
	 * @return array
	 */
	public function findAllByTypeOrTagForUser(string $param): array
	{
		try {
			return $this->activityRepository->findAllByTypeOrTagForUser($param, $this->guid);
		} catch (Exception $e) {
			throw new ActivityNotFoundException("Failed to find activities for user");
		}
	}

	/**
	 * @param string $param
	 * @return array
	 */
	public function findAllByInstitutionGuidOrNameForUser(string $param): array
	{
		try {
			return $this->activityRepository->findAllByInstitutionGuidOrNameForUser($param, $this->guid);
		} catch (Exception $e) {
			throw new ActivityNotFoundException("Failed to find all activities for user");
		}
	}

	/**
	 * @param string $param
	 * @return array
	 */
	public function findAllTopicalByInstitutionGuidOrNameForUser(string $param): array
	{
		try {
			return $this->activityRepository->findAllTopicalByInstitutionGuidOrNameForUser($param, $this->guid);
		} catch (Exception $e) {
			throw new ActivityNotFoundException("Failed to find all topical activities for user");
		}
	}

	/**
	 * @param string $param
	 * @return array
	 */
	public function findAllExpiredByInstitutionGuidOrNameForUser(string $param): array
	{
		try {
			return $this->activityRepository->findAllExpiredByInstitutionGuidOrNameForUser($param, $this->guid);
		} catch (Exception $e) {
			throw new ActivityNotFoundException("Failed to find all expired activities for user");
		}
	}

	/**
	 * @return float|int|mixed|string
	 */
	public function findOfTodayForUser(): mixed
	{
		try {
			return $this->activityRepository->findOfTodayForUser($this->guid);
		} catch (Exception $e) {
			throw new ActivityNotFoundException('Failed to find activities for today');
		}
	}

	/**
	 * @return mixed
	 */
	public function findOfNextSevenDaysForUser(): mixed
	{
		try {
			return $this->activityRepository->findOfNextSevenDaysForUser($this->guid);
		} catch (Exception $e) {
			throw new ActivityNotFoundException('Failed to find activities for next seven days');
		}
	}

	/**
	 * @return mixed
	 */
	public function findOfNextThirtyDaysForUser(): mixed
	{
		try {
			return $this->activityRepository->findOfNextThirtyDaysForUser($this->guid);
		} catch (Exception $e) {
			throw new ActivityNotFoundException('Failed to find activities for next thirty days');
		}
	}

	/**
	 * @param string $param
	 * @return array
	 */
	public function findOfTodayByInstitutionGuidOrNameForUser(string $param): array
	{
		try {
			return $this->activityRepository->findOfTodayByInstitutionGuidOrNameForUser($param, $this->guid);
		} catch (Exception $e) {
			throw new ActivityNotFoundException('Failed to find activities of institution for today');
		}
	}

	/**
	 * @param string $param
	 * @return array
	 */
	public function findOfNextSevenDaysByInstitutionGuidOrNameForUser(string $param): array
	{
		try {
			return $this->activityRepository->findOfNextSevenDaysByInstitutionGuidOrNameForUser($param, $this->guid);
		} catch (Exception $e) {
			throw new ActivityNotFoundException('Failed to find activities of institution for next seven days');
		}
	}

	/**
	 * @param string $param
	 * @return array
	 */
	public function findOfNextThirtyDaysByInstitutionGuidOrNameForUser(string $param): array
	{
		try {
			return $this->activityRepository->findOfNextThirtyDaysByInstitutionGuidOrNameForUser($param, $this->guid);
		} catch (Exception $e) {
			throw new ActivityNotFoundException('Failed to find activities of institution for next thirty days');
		}
	}

	/**
	 * @param string $guid
	 * @return Activity|null
	 */
	public function findByGuid(string $guid): ?Activity
	{
		try {
			return $this->activityRepository->findOneBy(['guid' => $guid]);
		} catch (Exception $e) {
			throw new ActivityNotFoundException("Failed to find activity by guid");
		}
	}

	/**
	 * @param int $id
	 * @return Activity|null
	 */
	public function findById(int $id): ?Activity
	{
		try {
			return $this->activityRepository->find($id);
		} catch (Exception $e) {
			throw new ActivityNotFoundException("Failed to find activity by id");
		}
	}

	/**
	 * @param Activity $activity
	 * @return void
	 */
	public function save(Activity $activity): void
	{
		try {
			$this->activityRepository->save($activity, true);
		} catch (Exception $e) {
			throw new ResourceNotCreatedException("Failed to create new activity");
		}
	}

	/**
	 * @param Activity $activity
	 * @return void
	 */
	public function remove(Activity $activity): void
	{
		try {
			$this->activityRepository->remove($activity, true);
		} catch (Exception $e) {
			throw new ResourceNotDeletedException("Failed to delete activity");
		}
	}
}