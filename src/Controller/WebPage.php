<?php

namespace App\Controller;

class WebPage
{
	private $url = "";
	private $name = "";
	public $pageObjects = array(); //Tablica wszystkich podstron. Docelowo private
	public $crawler;

	public function __construct(string $pageAddress)
	{
		$this->url = $pageAddress;
		$this->crawler = new CrawlerService($this->url, 1);
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