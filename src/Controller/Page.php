<?php

namespace App\Controller;

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