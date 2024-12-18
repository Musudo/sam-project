<?php

namespace App\EventListener;

use Exception;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ResponseEvent;
use Symfony\Component\Security\Core\Security;

class RequestListener
{
	public function __construct(private readonly Security $security)
	{
	}

	public function onKernelResponse(ResponseEvent $event): ?JsonResponse
	{
		if (!$event->isMainRequest()) {
			// don't do anything if it's not the main request
			return null;
		}

		try {
			$response = $event->getResponse();
			$user = $this->security->getUser();
			$guid = $user?->getUserIdentifier();

			// set a single header
			$response->headers->set("User-Header", $guid);
		} catch (Exception $e) {
			return new JsonResponse($e, Response::HTTP_UNAUTHORIZED);
		}

		return null;
	}
}