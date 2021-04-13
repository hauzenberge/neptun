<?php

namespace App\Helpers;

use App\Helpers\Helper;
use DB;
use App\Helpers\CurlHelper;

class Onesignal extends Helper{
	
	protected $_app_id;
	protected $_api_key;
	protected $_url;
	protected $_icon_url;
	protected $_own_player_id;
	
	public function __construct(){
		$this->_app_id			= env('onesignal_app_id'		, '');
		$this->_api_key			= env('onesignal_api_key'		, '');
		$this->_url				= env('onesignal_url'			, '');
		$this->_icon_url		= env('onesignal_icon_url'		, '');
		$this->_own_player_id	= env('onesignal_own_player_id'	, '');
	}
	
	function send($data, $url = '', $device = null, $users = null, $params = []){
		$fields = array(
			'app_id'			=> $this->_app_id,
			'contents'			=> array(),
			//'isAnyWeb'		=> true,
			//'chrome_web_icon'	=> $this->_icon_url,
			//'firefox_icon'		=> $this->_icon_url,
			'included_segments' => array('All'),
		);
		
		if($this->_icon_url){
			$fields['chrome_web_icon'] 	= $this->_icon_url;
			$fields['firefox_icon'] 	= $this->_icon_url;
		}
		
		if($params){
			$fields['data'] = $params;
		}
		
		if($device){
			if(!is_array($device)){
				$fields['include_player_ids'] = array($device);
			}else{
				$fields['include_player_ids'] = $device;
			}
			
			unset($fields['included_segments']);
		}
		
		if($users){
			if(!is_array($users)){
				$fields['include_external_user_ids'] = array($users);
			}else{
				$fields['include_external_user_ids'] = $users;
			}
			
			unset($fields['included_segments']);
		}
		
		foreach($data as $lang => $item){
			if(array_key_exists('title', $item) && $item['title'] != null){
				$fields['headings'][$lang] = $item['title'];
			}
			
			if(array_key_exists('text', $item) && $item['text'] != null){
				$fields['contents'][$lang] = $item['text'];
			}
		}
		
		if($fields['contents'] == null){
			return false;
		}
		
		if($url != null){
			$fields['url'] = $url;
		}
		
		CurlHelper::setUrl($this->_url);
		CurlHelper::setTimeout(10);
		CurlHelper::setConnectTimeout(5);
		CurlHelper::json(true);
		CurlHelper::post(true);
		CurlHelper::setData($fields, true);
		
		$headers = array(
			'Content-Type: application/json',
			'Authorization: Basic '.$this->_api_key
		);
		
		CurlHelper::setHeaders($headers);
		
		$request = CurlHelper::request();
		
		//print_r($data);
		//exit;
		
		if($request){
            if(!isset($request['errors'])){
                return true;
            }
        }
        
        return false;
	}
}
