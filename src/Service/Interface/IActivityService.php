<?php

namespace App\Service\Interface;

use App\Entity\Activity;
use DateTime;
use Symfony\Component\HttpFoundation\Request;

interface IActivityService
{
	public function findAllForAdmin();

	public function findAllTopicalForAdmin();

	public function findAllExpiredByYearForAdmin(DateTime $year);

	public function findAllByTypeOrTagForAdmin(string $param);

	public function findAllByInstitutionGuidOrNameForAdmin(string $param);

	public function findAllTopicalByInstitutionGuidOrNameForAdmin(string $param);

	public function findAllExpiredByInstitutionGuidOrNameForAdmin(string $param);

	public function findAllForUser();

	public function findAllTopicalForUser();

	public function findAllExpiredByYearForUser(DateTime $year);

	public function findAllByTypeOrTagForUser(string $param);

	public function findAllByInstitutionGuidOrNameForUser(string $param);

	public function findAllTopicalByInstitutionGuidOrNameForUser(string $param);

	public function findAllExpiredByInstitutionGuidOrNameForUser(string $param);

	public function findOfTodayForUser();

	public function findOfNextSevenDaysForUser();

	public function findOfNextThirtyDaysForUser();

	public function findOfTodayByInstitutionGuidOrNameForUser(string $param);

	public function findOfNextSevenDaysByInstitutionGuidOrNameForUser(string $param);

	public function findOfNextThirtyDaysByInstitutionGuidOrNameForUser(string $param);

	public function findByGuid(string $guid);

	public function findById(int $id);

	public function save(Activity $activity);

	public function remove(Activity $activity);
}