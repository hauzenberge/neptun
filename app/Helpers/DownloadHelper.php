<?php

namespace App\Helpers;

use App\Helpers\Helper;
use App\Helpers\CurlHelper;

class DownloadHelper extends Helper {
    
    function download($url, $out, $path){
        $command = "wget --tries=3 -O '".$path."/".$out."' '".$url."' 2>&1";
        
        $output = shell_exec($command);
        
        if($output != null){
            preg_match('|\:\s+(\d*)\s+\(.*?\)|si', $output, $matches);
            
            if(!empty($matches[1])){
                if(file_exists($path."/".$out)){
                    $size1 = (int)$matches[1];
                    $size2 = (int)filesize($path."/".$out);
                    
                    if($size1 !== $size2){
                        unlink($path."/".$out);
                        
                        return false;
                    }
                    
                    return true;
                }
            }
        }
        
        return false;
    }
    
    function save_img($url, $folder, $downloader = 'curl'){
        $name = 'img_'.md5(microtime() . rand(999, 99999)).'.jpg';
        
        $i = 0;
        
        if($downloader == 'wget'){
            if($this->download($url, $name, $folder)){
                return $name;
            }
        }elseif($downloader == 'curl'){
            CurlHelper::setUrl($url);
            CurlHelper::setTimeout(10);
            CurlHelper::setConnectTimeout(4);
            
            if(CurlHelper::save($folder.'/'.$name)){
                return $name;
            }
        }else{
            return false;
        }
        
        if($i < 1){
            $i++;
            
            usleep(500);
            return $this->save_img($url, $folder);
        }
        
        return false;
    }
}
