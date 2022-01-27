<?php

namespace App\Controller;

use DOMDocument;
use DOMXPath;
use Symfony\Bundle\FrameworkBundle\Test\TestContainer;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TestController
{

	/**
	 * @Route("/analyze")
	 */
	public function homepage(Request $request)
	{
		$url = $request->query->get('url');

		$links = new LinksAgregator();
		$linksAssoc = $links->getLinks($url);
		$linksJoined = Array();
		foreach ($linksAssoc as $key => $value){
			$linksJoined[] = "$key - <b>$value</b>";
		}

		$show = implode("<BR>", $linksJoined);

		return new Response("$show");
	}

}

class LinksAgregator{
    private $links = array();
	private $url = "";

	private function getRemoteFileContent(): string{
		
		$curl = curl_init();
		$header[0] = "Accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/avif,image/webp,image/apng,*/*;q=0.8,application/signed-exchange;v=b3;q=0.9";
		$header[] = "Cache-Control: max-age=0";
		$header[] = "Connection: keep-alive";
		$header[] = "Keep-Alive: 300";
		$header[] = "Accept-Charset: ISO-8859-1,utf-8;q=0.7,*;q=0.7";
		$header[] = "Accept-Language: en-us,en;q=0.5";
		$header[] = "Pragma: "; // browsers keep this blank.

		curl_setopt($curl, CURLOPT_URL, $this->url);
		curl_setopt($curl, CURLOPT_USERAGENT, 'Mozilla/5.0 (Macintosh; Intel Mac OS X x.y; rv:42.0) Gecko/20100101 Firefox/42.0');
		curl_setopt($curl, CURLOPT_HTTPHEADER, $header);
		curl_setopt($curl, CURLOPT_ENCODING, 'gzip,deflate');
		curl_setopt($curl, CURLOPT_TIMEOUT, 10);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

		$html = curl_exec($curl); 
		curl_close($curl);

		return $html;
	}

    public function getLinks(String $url): array{
		$this->url = $url;

        $dom = new DOMDocument();
		//$dom->strictErrorChecking = false; //Ta linia nie dziala. Musialem uzyc linie nizej wyciszania bledow znakiem @
        @$dom->loadHTML($this->getRemoteFileContent()); //Without @ ErrorException: DOMDocument::loadHTML(): Tag header invalid in Entity
        $DOMNodeList = $dom->getElementsByTagName("a");
		
        foreach ($DOMNodeList as $link) {
			$theLink = $this->url.$link->getAttribute("href");

			if (strpos("http", $theLink)){
				$eachLink = $this->url.$theLink;
			}
			else {
                $eachLink = $theLink;
            }
			
            $eachAnchor = $link->textContent;
            $this->links[$eachLink] = $eachAnchor;
        }

        return $this->links;
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