<?php

namespace App\Models;

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