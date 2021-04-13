<?php

namespace App\Helpers;

class MyBreadcrumbs {
    
    protected static $_links = array();
    
    public function __construct(){}
    
    public static function push($uri, $title){
        $uri = trim($uri, '/');
        self::$_links[$uri] = $title;
    }
    
    public static function clear(){
        self::$_links = array();
    }
    
    public static function render(){
		$html = '<nav aria-label="Ви тут:" role="navigation">';
        $html .= '<ul class="breadcrumbs">';
        
        if(self::$_links){
            $i = 1;
            
            $count = count(self::$_links);
            
            if($count > 1){
                foreach(self::$_links as $uri => $title){
                    if($i > 1){
                        if($i != $count){
                            $html .= '<li><a href="/'.$uri.'">'.$title.'</a></li>';
                        }else{
                            $html .= '<li><span>'.$title.'</span></li>';
                        }
                    }else{
                        $html .= '<li><a href="/'.$uri.'">'.$title.'</a></li>';
                    }
                    
                    $i++;
                }
            }else{
                $html .= '<li><span>'.$title.'</span></li>';
            }
        }
        
        $html .= '</ul>';
        $html .= '</nav>';
        
        self::$_links = array();
        
        return $html;
    }
}
