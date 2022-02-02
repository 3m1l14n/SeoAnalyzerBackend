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
	public function RequestUrl(Request $request)
	{
		$webpage = new WebPage($request->query->get('pageaddress'));
		$webpage->crawler->run();

		//Helper::print_r2($webpage);
		// Helper::print_r2($webpage->pageObjects);
		Helper::print_r2($webpage->crawler->run());
		//Helper::print_r2($webpage->pageObjects[0]->linkObjects);
		//$webpage->getPage('contact')->getLinkCount(); //Returns number of links of specific page. 


		//$json = json_encode($links, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
		return new Response("");
	}

}
   
class CrawlerService
{
	private $dom = "";
	private $url = "";
	private $depth;

	public function __construct($url, $depth = 5)
	{
		$this->url = $url;
		$this->depth =  $depth;
		$dom = new DOMDocument();
		@$dom->loadHTML($this->getContent($this->url));  //Return DOMDocument object
		$this->dom = $dom;
	}
    
    public function run($url = "WebPageInit", $depth = 5)
	{
		$url = "WebPageInit" ? $url = $this->url : $url = $url;

		$seen = array();

		if (isset($seen[$url]) || $depth === 0)
		{
			return;
		}

		$anchor = $this->dom->getElementsByTagName("a");
		foreach ($anchor as $link)
		{
			$theLink = $link->getAttribute("href");

			//If the link is relative
			if (!strpos($theLink, "ttp"))
			{
				$href = $this->url.$theLink;
			}
			else 
			{
                $href = $theLink;
            }
			//IF the link contains body. @NULL = "NULL"
			$anchorText = $link->nodeValue ? $anchorText = $link->nodeValue : "NULL";

            $linkObjects[$href] = new Link($href, $anchorText);

			$this->run($href, $depth = -1);
        }
		//Return array of linkObjects
		return $linkObjects;
    }

	public function getTitle()
	{
				//get title
				$title =  $this->dom->getElementsByTagName("title")->item(0)->nodeValue;
				return $title;		
	}

	private function getContent($url): string{
		
		$curl = curl_init();

		//CURL headers
		$header[0] = "Accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/avif,image/webp,image/apng,*/*;q=0.8,application/signed-exchange;v=b3;q=0.9";
		$header[] = "Cache-Control: max-age=0";
		$header[] = "Connection: keep-alive";
		$header[] = "Keep-Alive: 300";
		$header[] = "Accept-Charset: ISO-8859-1,utf-8;q=0.7,*;q=0.7";
		$header[] = "Accept-Language: en-us,en;q=0.5";
		$header[] = "Pragma: ";

		//CURL options
		curl_setopt($curl, CURLOPT_URL, $url);
		curl_setopt($curl, CURLOPT_USERAGENT, 'Mozilla/5.0 (Macintosh; Intel Mac OS X x.y; rv:42.0) Gecko/20100101 Firefox/42.0');
		curl_setopt($curl, CURLOPT_HTTPHEADER, $header);
		curl_setopt($curl, CURLOPT_ENCODING, 'gzip,deflate');
		curl_setopt($curl, CURLOPT_TIMEOUT, 10);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

		$html = curl_exec($curl); 
		curl_close($curl);

		return $html;
	}

}

class WebPage
{
	public static $url = "";
	private $name = "";
	public $pageObjects = array(); //Tablica wszystkich podstron. Docelowo private
	public $crawler;

	public function __construct(string $pageAddress)
	{
		self::$url = $pageAddress;
		$this->crawler = new CrawlerService(self::$url, 5);
		//Helper::print_r2($webPage->getLinks()[4]->getUrl());
	}

	public function addPage($page)
	{
		$this->pageObjects = $page;
	}

	public function getUrl()
	{
		return $this->url;
	}

	public function getPage(string $page)
	{
		return $this->pageObjects[$page];
	}
}

class Page extends WebPage
{
	private $title = "";
	private $description = "";
	private $linkObjects = array(); //Tablica obiektow wszystkich linkow na podstronie

	public function __construct()
	{
		$this->linkObjects[] = new Link();
	}

	public function getLinkCount()
	{
		count($this->linkObjects);
	}
}

class Link extends Page
{
	private $anchorText = "";
	private $linkUrl = "";
	private $rel = "";

	public function __construct($url, $anchorText)
	{
		$this->linkUrl = $url;
		$this->anchorText = $anchorText;
	}

	public function getUrl()
	{
		return $this->linkUrl;
	}

	public function getAnchor()
	{

	}

}

class JsonConverter
{

}




abstract class Helper {
	static public function print_r2($var){
		echo "<pre><HR><BR>";
		print_r($var);
		echo "</pre>";
	}

	static public function vardump_2($var){
		echo "<pre>";
		var_dump($var);
		echo "</pre>";
	}
}