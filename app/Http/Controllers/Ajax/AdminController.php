<?php

namespace App\Http\Controllers\Ajax;

use App\Http\Controllers\Controller;

use DB;

class AdminController extends Controller {
    
    public function __construct(){
        parent::__construct();
    }
    
    public function index($action = ''){
        switch($action){
            case('jslangs'):
                return $this->jslangs();
            break;
        }
    }
    
    public function jslangs(){
        $status = false;
        
        $tmp = DB::table('js_langs')
                        ->orderBy('key', 'asc')
                        ->select('key', 'value_ua', 'value_en')
                        ->get();
        
        $data_ua = array();
        $data_en = array();
        
        $json_ua = "{\n";
        $json_en = "{\n";
        
        if($tmp){
            foreach($tmp as $item){
                $data_ua[] = "\t".'"'.$item->key.'" : "'.addslashes($item->value_ua).'"';
                $data_en[] = "\t".'"'.$item->key.'" : "'.addslashes($item->value_en).'"';
            }
            
            $json_ua .= implode(",\n", $data_ua);
            $json_en .= implode(",\n", $data_en);
            
            $data_ua = null;
            $data_en = null;
        }
        
        $tmp = null;

        $json_ua = "var langs = ".$json_ua;
        $json_en = "var langs = ".$json_en;
        
        $json_ua .= "\n};";
        $json_en .= "\n};";
        
        if(file_put_contents(ROOT.'/js/langs_ua.js', $json_ua)){
            $status = true;
        }
        
        if(file_put_contents(ROOT.'/js/langs_en.js', $json_en)){
            $status = true;
        }
        
        return array(
            'status' => $status
        );
    }
}
