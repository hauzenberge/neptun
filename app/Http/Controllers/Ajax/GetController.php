<?php

namespace App\Http\Controllers\Ajax;

use App\Http\Controllers\Controller;

use App\Models\ArticlesCategory;
use App\Models\Articles;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

use DB;

class GetController extends Controller {
    
    public function __construct(){
        parent::__construct();
    }
    
    public function articles(Request $request){
		$limit = (int)config('count_articles');
        
        if(!$limit){
			$limit = 15;
		}
		
		$type	= $request->get('type', '');
		$tag	= $request->get('tag', '');
		$start	= (int)$request->get('start', 0);
		$cat	= (int)$request->get('cat', 0);
		
		if($type != 'article' && $type != 'analytic'){
			return [
				'count' => 0,
				'items' => []
			];
		}
		
		$data = Articles::getItems($type, $start, $limit, $cat, 0, $tag);
		
		if($data){
			foreach($data as $i => $item){
				$data[$i]->uri = url($item->type.'s/'.$item->uri);
				$data[$i]->month = trans('site.months.'.$item->month);
			}
		}
		
		//print_r($data);exit;
		
		return [
			'count' => Articles::countItems($type, $cat, $tag),
			'items' => $data
		];
    }
}
