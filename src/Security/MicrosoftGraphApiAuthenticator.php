<?php

namespace App\Security;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

class MicrosoftGraphApiAuthenticator
{

	public function __construct
	(
		private readonly AzureAuthenticator    $azureAuthenticator,
		private readonly ParameterBagInterface $params
	)
	{
	}

	/**
	 * @throws GuzzleException
	 */
	public function getAccessToken()
	{
		$tenantId = $this->params->get('app.azure_tenant_id');
		$clientId = $this->params->get('app.azure_client_id');
		$clientSecret = $this->params->get('app.azure_client_secret');

		$guzzle = new Client();
		$url = 'https://login.microsoftonline.com/' . $tenantId . '/oauth2/v2.0/token';
		$token = json_decode($guzzle->post($url, [
			'form_params' => [
				'client_id' => $clientId,
				'client_secret' => $clientSecret,
				'scope' => 'https://graph.microsoft.com/.default',
				'grant_type' => 'client_credentials',
			],
		])->getBody()->getContents());

		return $token->access_token;
	}
}