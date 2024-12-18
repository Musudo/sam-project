<?php

namespace App\Security;

use App\Entity\Log;
use App\Entity\User;
use App\Repository\LogRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use KnpU\OAuth2ClientBundle\Client\ClientRegistry;
use KnpU\OAuth2ClientBundle\Client\OAuth2ClientInterface;
use KnpU\OAuth2ClientBundle\Security\Authenticator\OAuth2Authenticator;
use League\OAuth2\Client\Token\AccessToken;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\UserBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Passport;
use Symfony\Component\Security\Http\Authenticator\Passport\SelfValidatingPassport;
use Symfony\Component\Security\Http\EntryPoint\AuthenticationEntryPointInterface;
use Symfony\Component\Security\Http\Util\TargetPathTrait;
use TheNetworg\OAuth2\Client\Provider\AzureResourceOwner;

class AzureAuthenticator extends OAuth2Authenticator implements AuthenticationEntrypointInterface
{
	use TargetPathTrait;

	public function __construct
	(
		private readonly ClientRegistry         $clientRegistry,
		private readonly LogRepository          $logRepository,
		private readonly UserRepository         $userRepository,
		private readonly Security               $security,
		private readonly RouterInterface        $router,
		private readonly EntityManagerInterface $entityManager
	)
	{
	}

	/**
	 * Called on every request to decide if this authenticator should be
	 * used for the request. Returning `false` will cause this authenticator
	 * to be skipped.
	 */
	public function supports(Request $request): ?bool
	{
		return $request->attributes->get('_route') === "connect_azure_check";
	}

	public function authenticate(Request $request): Passport
	{
		$client = $this->clientRegistry->getClient('azure');
		$accessToken = $this->fetchAccessToken($client);

		return new SelfValidatingPassport(
			new UserBadge($accessToken->getToken(), function () use ($accessToken, $client) {
				/** @var AzureResourceOwner $azureUser */

				$azureUser = $client->fetchUserFromToken($accessToken);
				$email = $azureUser->getUpn();

				// 1) do we have a matching user by email?
				$existingUser = $this->userRepository->findOneBy(['email' => $email]);
				if ($existingUser) return $existingUser;

				// 2) If not create a new user and add him to db
				$user = new User();
				$user->setFirstName($azureUser->getFirstName());
				$user->setLastName($azureUser->getLastName());
				$user->setEmail($email);
				$user->setRoles(['ROLE_USER']);

				$this->userRepository->save($user, true);

				return $user;
			})
		);
	}

	public function onAuthenticationSuccess(Request $request, TokenInterface $token, string $firewallName): ?Response
	{
		try {
			$log = new Log();
			$log->setUser($this->security->getUser());
			$log->setStatus("logged_in");

			$this->logRepository->save($log, true);
		} catch (Exception $e) {
			return new JsonResponse($e, Response::HTTP_BAD_REQUEST);
		}

		$url = $this->getTargetPath($request->getSession(), 'main');
		if (!$url) {
			$targetUrl = $this->router->generate('home');
		} else {
			$targetUrl = $url;
		}

		return new RedirectResponse($targetUrl);
	}

	public function onAuthenticationFailure(Request $request, AuthenticationException $exception): ?Response
	{
		$data = [
			// you may want to customize or obfuscate the message first
			'message' => strtr($exception->getMessageKey(), $exception->getMessageData())

			// or to translate this message
			// $this->translator->trans($exception->getMessageKey(), $exception->getMessageData())
		];

		return new JsonResponse($data, Response::HTTP_UNAUTHORIZED);
	}


	public function start(Request $request, AuthenticationException $authException = null): RedirectResponse
	{
		return new RedirectResponse(
			'/connect/azure', // might be the site, where users choose their oauth provider
			Response::HTTP_TEMPORARY_REDIRECT
		);
	}

	public function getAccessToken(): AccessToken
	{
		$client = $this->clientRegistry->getClient('azure');

		return $this->fetchAccessToken($client);
	}

	public function getUser(): OAuth2ClientInterface
	{
		return $this->clientRegistry->getClient('azure');
	}
}