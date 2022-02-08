<?php

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