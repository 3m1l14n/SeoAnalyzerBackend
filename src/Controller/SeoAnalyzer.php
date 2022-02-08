<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Helper;
use App\Models\WebPage;

class SeoAnalyzer
{

	/**
	 * @Route("/analyze")
	 */
	public function RequestUrl(Request $request)
	{
		$url = $request->query->get('pageaddress');
		$webpage = new WebPage($url);
		$webpage->crawler->run($url);
		Helper::print_r2($webpage->crawler->linkObjects);

		//Helper::print_r2($webpage);
		// Helper::print_r2($webpage->pageObjects);
		//Helper::print_r2($webpage->crawler->run());
		//Helper::print_r2($webpage->pageObjects[0]->linkObjects);
		//$webpage->getPage('contact')->getLinkCount(); //Returns number of links of specific page. 


		//$json = json_encode($links, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
		return new Response("");
	}

}