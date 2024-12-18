<?php

namespace App\Controller\Api;

use App\Service\InstitutionService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api/institutions', name: 'api_institutions_')]
class InstitutionController extends AbstractController
{
	public function __construct
	(
		private readonly InstitutionService $institutionService
	)
	{
	}

	#[Route('', name: 'find_all', methods: ['GET'])]
	public function findAll(): Response
	{
		$institutions = $this->institutionService->findAllForUser();
		if (!$institutions) return $this->json('Institutions not found', Response::HTTP_NO_CONTENT);

		return $this->json($institutions, Response::HTTP_OK, [], ['groups' => ['get', 'byInstitution']]);
	}


	#[Route('/{guid}', name: 'find_by_guid', methods: ['GET'])]
	public function findByGuid(string $guid): Response
	{
		$institution = $this->institutionService->findByGuid($guid);
		if (!$institution) return $this->json('Institution not found by guid: ' . $guid, Response::HTTP_NO_CONTENT);

		return $this->json($institution, Response::HTTP_OK, [], ['groups' => ['get', 'byInstitution']]);
	}

	/**
	 * find institutions of user by name, address info or client id
	 */
	#[Route('/info/{param}', name: 'find_by_institution_info', methods: ['GET'])]
	public function findByInfo(string $param): Response
	{
		$param .= '%';

		$institutions = $this->institutionService->findByInfoForUser($param);
		if (!$institutions) return $this->json('Institutions not found by parameter: ' . $param, Response::HTTP_NO_CONTENT);

		return $this->json($institutions, Response::HTTP_OK, [], ['groups' => ['get', 'byInstitution']]);
	}
}
