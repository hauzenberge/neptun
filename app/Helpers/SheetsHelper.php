<?php

namespace App\Helpers;

use App\Helpers\Helper;

class SheetsHelper extends Helper{
    
    private $key;
    private $client;
    private $service;
    
    public function __construct(){
        //$this->key      = $key;
        $this->client   = null;
        $this->service  = null;
    }
    
    public function client(){
        try {
            $this->client = new \Google_Client();
            $this->client->useApplicationDefaultCredentials();
            
            $this->client->addScope('https://www.googleapis.com/auth/spreadsheets');
            
            $this->service = new \Google_Service_Sheets($this->client);
        }catch(Google_Service_Exception $exception){
            $this->client   = null;
            $this->service  = null;
        }
        
        return $this;
    }
    
    public function read($id = ""){
        if(!$id || !$this->service){
            return [];
        }
        
        try {
            $response = $this->service->spreadsheets->get($id);
            
            if(!$response){
                return [];
            }
        }catch(Google_Service_Exception $exception){
            return [];
        }
        
        $values = [];
        
        try {
            foreach($response->getSheets() as $sheet){
                $sheetProperties = $sheet->getProperties();
                
                $result = $this->service->spreadsheets_values->get($id, $sheetProperties->title);
                
                if($result && isset($result->values) && is_array($result->values)){
                    $values = $result->values;
                    unset($values[0]);
                    $values = array_values($values);
                }
                
                break;
            }
        }catch(Google_Service_Exception $exception){
            return [];
        }
        
        if($values){
            foreach($values as $i => $item){
                $item[0] = isset($item[0]) ? trim($item[0]) : "";
                $item[1] = isset($item[1]) ? trim($item[1]) : "";
                $item[2] = isset($item[2]) ? trim($item[2]) : "";
                $item[3] = isset($item[3]) ? trim($item[3]) : "";
                $item[4] = isset($item[4]) ? trim($item[4]) : "";
                $item[5] = isset($item[5]) ? trim($item[5]) : "";
                $item[6] = isset($item[6]) ? trim($item[6]) : "";
                $item[7] = isset($item[7]) ? trim($item[7]) : "";
                
                if(!$item[0]){
                    unset($values[$i]);
                    continue;
                }
                
                $values[$i] = $item;
            }
            
            $values = array_values($values);
        }
        
        return $values;
    }
}
