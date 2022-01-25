<?php

namespace App\Controller;

use DOMDocument;
use DOMXPath;
use Symfony\Bundle\FrameworkBundle\Test\TestContainer;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TestController
{

	/**
	 * @Route("/analyze")
	 */
	public function homepage()
	{

	$html = '<!DOCTYPE html>
	<html lang="en">
	<meta charset="UTF-8">
	<title>Page Title</title>
	<meta name="viewport" content="width=device-width,initial-scale=1">
	<link rel="stylesheet" href="">
	<style>
	</style>
	<script src=""></script>
	<body>

	<img src="img_la.jpg" alt="LA" style="width:100%">

	<div class="">
	 <h1>This is a Heading</h1>
	 <p>This is a paragraph.</p>
	 <p>This is another paragraph.</p>
	  <a href="http://mojastrona1.com">Mój anchor text 1</a>
	  <a href="http://mojastrona2.com">Mój anchor text 2</a>
	  <a href="http://mojastrona3.com">Mój anchor text 3</a>
	  <a href="http://mojastrona4.com">Mój anchor text 4</a>
	</div>
	</body>
	</html>';
	

		$links = new LinksAgregator();
		$linksAssoc = $links->getLinks($html);
		
		$linksJoined = Array();
		foreach ($linksAssoc as $key => $value){
			$linksJoined[] = "$key - $value";
		}

		$show = implode("<BR>", $linksJoined);


		return new Response("$show");
	}

}

class LinksAgregator{
    private $links = array();

    public function getLinks(String $html): array{
        $doc = new DOMDocument();
        $doc->loadHTML("$html");
        $DOMNodeList = $doc->getElementsByTagName("a");
		
        foreach ($DOMNodeList as $link) {
            $eachLink = $link->getAttribute("href");
            $eachAnchor = $link->textContent;
            $this->links[$eachLink] = $eachAnchor;
        }

        return $this->links;
    }

	//Alternative method
	public function getLinks2(String $html){
		
		$doc = new DOMDocument();
		$doc->loadHTML("$html");
		$xpath = new DOMXPath($doc);
		$hrefs = $xpath->query("//a/@href");
		foreach ($hrefs as $node){ //$node: DOMAttr
			$result2[] = $node->nodeValue;
		}
	}
}

abstract class Helper {
	static public function print_r2($var){
		echo "<pre>";
		print_r($var);
		echo "</pre>";
	}

	static public function vardump_2($var){
		echo "<pre>";
		var_dump($var);
		echo "</pre>";
	}
}