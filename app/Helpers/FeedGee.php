<?php

namespace App\Helpers;

use App\Helpers\Helper;
use App\Helpers\CurlHelper;

/**
 * FeedGee
 */
class FeedGee extends Helper {
	
	private $apikey;
	
	public function __construct($apikey){
		$this->apikey = $apikey;	
	}
	
	public function subscribe($email){
		if(!$this->apikey){
			return false;
		}
		
		$url = 'http://api.feedgee.com/1.0/subscriberImport';
		
		CurlHelper::setUrl($url);
		CurlHelper::setTimeout(10);
		CurlHelper::setConnectTimeout(5);
		CurlHelper::json(true);
		
		CurlHelper::setData([
			'apikey' 			=> $this->apikey,
			'email' 			=> $email,
			'phone' 			=> '',
			'mobilecountry'		=> 'Ukraine',
			'fname'				=> '',
			'lname'				=> '',
			'names'				=> 'language',
			'values'			=> 'ua',
			'update_existing'	=> 'FALSE',
			'output'			=> 'json'
		]);
		
		$data = CurlHelper::request();
		
		if($data && isset($data['success']) && isset($data['errors'])){
			if($data['success'] > 0 && $data['errors'] < 1){
				return $this->addToList($email);
			}elseif($data['success'] < 1 && $data['errors'] > 0){
				if(preg_match('/profile\s+already\s+exist/', $data['error'])){
					//return null;
					
					return $this->addToList($email);
				}else{
					return false;
				}
			}
		}
		
		return false;
	}
	
	public function addToList($email){
		if(!$this->apikey){
			return false;
		}
		
		$url = 'http://api.feedgee.com/1.0/listSubscribeOptInNow';
		
		CurlHelper::setUrl($url);
		CurlHelper::setTimeout(10);
		CurlHelper::setConnectTimeout(5);
		CurlHelper::json(true);
		
		CurlHelper::setData([
			'apikey' 			=> $this->apikey,
			'list_id'			=> '102088',
			'email' 			=> $email,
			'phone' 			=> '',
			'mobilecountry'		=> 'Ukraine',
			'fname'				=> '',
			'lname'				=> '',
			'names'				=> '',
			'values'			=> '',
			'optin'				=> 'FALSE',
			'update_existing'	=> 'TRUE',
			'output'			=> 'json'
		]);
		
		$data = CurlHelper::request();
		
		if($data && isset($data['success']) && $data['success'] > 0){
			return true;
		}
		
		return false;
	}
}
