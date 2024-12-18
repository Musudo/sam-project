<?php

namespace App\Controller\Authentication;

use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class LogoutController extends AbstractController
{
	/**
	 * @throws Exception
	 */
    #[Route('/logout', name: 'app_logout', methods: ['GET'])]
    public function logout(): Response
    {
		// controller can be blank: it will never be called!
		throw new Exception('Logout out');
    }
}
