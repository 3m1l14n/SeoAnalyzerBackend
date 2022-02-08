<?php

namespace App\Services;

use DOMDocument;
use App\Models\Link;

class CrawlerService
{
	private $seen = [];
	private $dom = "";
	private $url = "";
	private $depth = 0;
	public $linkObjects = array();

	public function __construct($url, $depth = 5)
	{
		$this->url = $url;
		$this->depth =  $depth;
		$dom = new DOMDocument();
		@$dom->loadHTML($this->getContent($this->url));  //Return DOMDocument object
		$this->dom = $dom;
	}
    
    public function run($url, $depth = 1, $seen = NULL){
		
		if (isset($this->seen[$url]) || $depth === 0)
		{
			return;
		}

		$this->seen[$url] = true;
		$anchor = $this->dom->getElementsByTagName("a");
        foreach ($anchor as $link) {
            $theLink = $link->getAttribute("href");
			$theLink === "/" ? $theLink = "" : $theLink = $theLink; //Kasowanie znaku "/" bo warunek if (!$key == $this->url) nie dzialal prawidlowo 
			$linkText = $link->nodeValue ? $anchorText = $link->nodeValue : "NULL";
            if (!strpos($theLink, "ttp")) {
                $href[$this->url.$theLink] = $linkText;
            } else {
                $href[$theLink] = $linkText;;
            }
        }

		$uniqueHrefs = array_unique($href);
		foreach ($uniqueHrefs as $key => $value){
            $linkObjects[$key] = new Link($key, "$value");
            if ($key !== $this->url) {
                $this->run($key, $depth = -1, $seen);
            }
        }
		//Return array of linkObjects
		$this->linkObjects = $linkObjects;
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