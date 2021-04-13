<?php

namespace App\Helpers;

use App\Helpers\Helper;
use App\Helpers\CurlHelper;

Class MEHelper extends Helper {
	
	static function getCities(){
		$send = [
			"modelName"			=> "Address",
			"calledMethod"		=> "getCities",
			"methodProperties"	=> (object)[],
			"apiKey"			=> env("NP_KEY", null)
		];
		
		$url = "https://api.novaposhta.ua/v2.0/json/";
		
		CurlHelper::setUrl($url);
		CurlHelper::setTimeout(10);
		CurlHelper::post(true);
		CurlHelper::setData($send, true);
		CurlHelper::json(true);
		
		CurlHelper::setHeaders([
			"content-type: application/json"
		]);
		
		//CurlHelper::gzip(true);
		
		//return CurlHelper::request(false);
		
		$data = CurlHelper::request(false);
		
		if($data && isset($data['success']) && $data['success']){
			return $data['data'];
		}
		
		return [];
	}

	static function getWarehouses($city){
		$send = [
			"modelName"			=> "Address",
			"calledMethod"		=> "getWarehouses",
			"methodProperties"	=> [
				"CityRef" => $city
			],
			"apiKey"			=> env("NP_KEY", null)
		];
		
		$url = "https://api.novaposhta.ua/v2.0/json/";
		
		CurlHelper::setUrl($url);
		CurlHelper::setTimeout(10);
		CurlHelper::post(true);
		CurlHelper::setData($send, true);
		CurlHelper::json(true);
		
		CurlHelper::setHeaders([
			"content-type: application/json"
		]);
		
		//CurlHelper::gzip(true);
		
		//return CurlHelper::request(false);
		
		$data = CurlHelper::request(false);
		
		if($data && isset($data['success']) && $data['success']){
			return $data['data'];
		}
		
		return [];
	}
}
