<?php

namespace App\Helpers;

use App\Helpers\Helper;
use DB;
use App\Helpers\CurlHelper;

/**
 * SMSClub
 */
class SMSClub extends Helper{
	
	private $username;
	private $token;
	private $from;
	
	public function config_user($username, $token, $from){
		$this->username = $username;
		$this->token = $token;
		$this->from = $from;
	}
	
	public function sendSMS($to, $data, $template = 'SMSClub'){
		$text = DB::table('cms_email_templates')->where('slug', $template)->first();
		
		if(is_null($text)) return false;
		
		$text = $text->content;
		
		foreach($data as $key => $value){
			$text = str_replace('{' . $key . '}', $value, $text);
		}
		
		$text = urlencode(iconv('utf-8','windows-1251', $text));
		
		$url = 'https://gate.smsclub.mobi/token/';
		
		CurlHelper::setUrl($url);
		CurlHelper::setTimeout(10);
		CurlHelper::setConnectTimeout(5);
		//CurlHelper::json(true);
		//CurlHelper::post(true);
		
		$send = [
			'username' 			=> $this->username,
			'token' 			=> $this->token,
			'from' 				=> $this->from,
			'to'				=> $to,
			'text'				=> $text
		];
		
		//print_r($send);
		
		CurlHelper::setData($send);
		
		$data = CurlHelper::request();
		
		//print_r($data);
		//exit;
		
		return $data;
	}
	
	public function sendViber($to, $data, $media, $template = 'SMSViber'){
		$text = DB::table('cms_email_templates')->where('slug', $template)->first();
		
		if(is_null($text)) return false;
		
		$text = $text->content;
		
		foreach($data as $key => $value){
			$text = str_replace('{' . $key . '}', $value, $text);
		}
		
		$url = 'https://im.smsclub.mobi/vibers/send';
		
		CurlHelper::setUrl($url);
		CurlHelper::setTimeout(10);
		CurlHelper::setConnectTimeout(5);
		CurlHelper::json(true);
		CurlHelper::post(true);
		
		$sendData = [
			'sender' 			=> $this->from,
			'phones'			=> [$to],
			'message'			=> $text,
			'lifetime' 			=> 120,
			'senderSms' 		=> $this->from,
			'messageSms'		=> $text,
			
			//'picture_url'		=> '',
			'button_txt'		=> 'Детальньніше',
			'button_url'		=> url('/catalog?art='.$media['art'])
		];
		
		if($media['image'] && file_exists(ROOT.'/'.$media['image'])){
			$sendData['picture_url'] = url('/'.$media['image']);
		}
		
		CurlHelper::setData($sendData, true);
		
		CurlHelper::setHeaders([
			'Content-Type: application/json',
			'X-Requested-With: XMLHttpRequest',
			'Accept: application/json, text/javascript, */*; q=0.01'
		]);
		
		CurlHelper::auth($this->username.':'.$this->token);
		
		$data = CurlHelper::request();
		
		if(isset($data['$data'])){
			return true;
		}
		
		return false;
	}
}
