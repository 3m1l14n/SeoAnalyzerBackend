<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TestController
{
	/**
	 * @Route("/")
	 */
	public function homepage()
	{
		return new Response('Homepage');
	}

	/**
	 * @Route("/example/test")
	 */
	public function show()
	{

		return new Response('Method show');
	}
}