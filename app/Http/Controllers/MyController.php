<?php

namespace App\Http\Controllers;

//use Illuminate\Http\Request;
use Request;

use App\Http\Requests;

use DB;

use App\Models\Menu;
use App\Models\Langs;
use App\Models\Contacts;

use Illuminate\Support\Facades\View;

use App\Helpers\ImageHelper;
use App\Helpers\StringHelper;

class MyController extends Controller {
    
    public $_current_lang	= 'ua';
    public $_primary_lang	= 'ua';
    public $_langs			= ['ua'];
    
    function __construct(){
        parent::__construct();
        
        $lang = Request::route('lang');
        
        $langs = Langs::getList();
        
        if($langs){
			$this->_langs = $langs;
		}
        
        if($lang){
			if(in_array($lang, $this->_langs)){
				$this->_current_lang = $lang;
			}else{
				//$this->_current_lang = $this->_primary_lang;
				
				return $this->show_404();
			}
		}
		
		app()->setLocale(($this->_current_lang == 'ua' ? 'uk' : $this->_current_lang));
		
        View::share('langs'			, $this->_langs);
        View::share('lang'			, $this->_current_lang);
        View::share('primary_lang'	, $this->_primary_lang);
        View::share('html_lang'		, ($this->_current_lang == 'ua' ? 'uk' : $this->_current_lang).'-'.strtoupper($this->_current_lang));
        
        View::share('page', array(
			'id'         	=> 1,
            'title'         => '',
            'keywords'      => '',
            'description'   => '',
            'uri'           => 'index',
            'og_image'   	=> '',
        ));
        
        //
        
        $settings = array(
			'appname'			=> '',
			'google_api_key'	=> '',
			'favicon'			=> '',
			'copyright'			=> '',
			
			'head_code'			=> '',
			'body_code'			=> '',
			'footer_code'		=> '',
			
			'email'				=> '',
			'phone'				=> '',
			
			'social.fb'			=> '',
			'social.inst'		=> '',
			'social.yt'			=> '',
			
			'livebtn_label'		=> '',
			'livebtn_show'		=> false,
        );
        
        $tmp = DB::table('admin_config')
                    ->select('name', 'value')
                    ->whereIn('name', array_keys($settings))
                    ->get();
        
        if(count($tmp)){
            foreach($tmp as $item){
				$item->value = trim($item->value);
				
				if($item->name == 'addresses_'.$this->_current_lang){
					$settings['addresses'] = $item->value;
				}
				
				if($item->name == 'livebtn_show'){
					$item->value = (int)$item->value > 0;
				}
				
				$settings[$item->name] = $item->value;
            }
        }
        
        View::share('settings'		, $settings);
        
        View::share('contacts'		, Contacts::getData($this->_current_lang));
        
        //var_dump($social);exit;
        
        //
        
        //var_dump(Menu::getList('sort'));exit;
        View::share('menu'			, Menu::getList($this->_current_lang, 'sort'));
        
        View::share('imageHelp'		, new ImageHelper);
        View::share('string'		, new StringHelper);
        
        View::share('wrapperClass'	, '');
        View::share('active'		, '');
    }
    
    public function show_404(){
		return abort(404);
		
        return view(
            'error', 
            [
                'page' => array(
                    'title'         => trans('site.title_404'),
                    'keywords'      => '',
                    'description'   => '',
                    'uri'           => 'error',
                    'og_image'   	=> '',
                )
            ]
        );
    }
    
    public function show_500(){
		return abort(500);
		
        return view(
            '500', 
            [
                'page' => array(
                    'title'         => trans('site.title_500'),
                    'keywords'      => '',
                    'description'   => '',
                    'uri'           => 'error',
                    'og_image'   	=> '',
                )
            ]
        );
    }
}
