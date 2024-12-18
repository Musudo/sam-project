<?php

namespace App\Service\Interface;

use App\Entity\Contact;
use App\Service\PersistData;
use Symfony\Component\HttpFoundation\Request;

interface IContactService
{
	public function findAllForAdmin();

	public function findAllByContactInfoForAdmin(string $param);

	public function findAllByInstitutionGuidOrNameForAdmin(string $param);

	public function findAllForUser();

	public function findAllByContactInfoForUser(string $param);

	public function findAllByInstitutionGuidOrNameForUser(string $param);

	public function findInstitutionsOfContact(string $guid);

	public function findAllByActivity(string $guid);

	public function findByGuid(string $guid);

	public function findById(int $id);

	public function findByEmail(string $email);

	public function save(Contact $contact);

	public function remove(Contact $contact);
}