<?php

namespace App\Controller\Authentication;

use App\Repository\LogRepository;
use KnpU\OAuth2ClientBundle\Client\ClientRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Http\Util\TargetPathTrait;

class AzureController extends AbstractController
{
	use TargetPathTrait;

	public function __construct
	(
		private readonly LogRepository   $logRepository,
		private readonly RouterInterface $router,
	)
	{
	}

	/**
	 * Link to this controller to start the "connect" process
	 */
	#[Route('/connect/azure', name: 'connect_azure')]
	public function connect(Request $request, ClientRegistry $clientRegistry): RedirectResponse
	{
		$url = $request->headers->get('referer');
		if (is_null($url)) {
			$url = '';
		}

		$this->saveTargetPath($request->getSession(), 'main', $url);

		return $clientRegistry
			->getClient('azure')
			->redirect([], []);
	}

	/**
	 * After going to Azure, you're redirected back here
	 * because this is the "redirect_route" you configured
	 * in config/packages/knpu_oauth2_client.yaml
	 *
	 */
	#[Route('/connect/azure/check', name: 'connect_azure_check')]
	public function connectCheck(): Response
	{
		return new Response("", Response::HTTP_OK);
	}
}

