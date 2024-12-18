<?php

namespace App\EventSubscriber;

use Psr\Log\LoggerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;
use Symfony\Component\HttpKernel\KernelEvents;

class ExceptionSubscriber implements EventSubscriberInterface
{
	public function __construct(private readonly LoggerInterface $logger)
	{
	}

	public function onKernelException(ExceptionEvent $event): void
	{
		// Retrieve the exception from the event
		$exception = $event->getThrowable();

		// Create a response based on the exception
		$response = new Response();
		$response->setContent('An exception occurred. ' . $exception->getMessage());

		// Log the exception too
		$this->logger->error('An exception occurred. ' . $exception->getMessage());

		// Set the response status code based on the exception type
		if ($exception instanceof HttpExceptionInterface) {
			$response->setStatusCode($exception->getStatusCode());
		} else {
			$response->setStatusCode(Response::HTTP_INTERNAL_SERVER_ERROR);
		}

		// Set the response on the event
		$event->setResponse($response);
	}

	public static function getSubscribedEvents(): array
	{
		return [
			KernelEvents::EXCEPTION => 'onKernelException'
		];
	}
}