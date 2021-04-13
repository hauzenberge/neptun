<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;

use App\Models\Submenu;

class Menu extends Model{
	
    protected $table = 'menu';
    
    public $timestamps = false;
    
    protected $fillable = [
		'sort', 'uri', 
		'label_ua', 'label_ru', 'label_en', 
		'class_name', 'noindex'
	];
    
    public function submenu(){
		return $this->hasMany(Submenu::class, 'parent_id');
	}
    
    static function getList($lang){
		$tmp = DB::table('menu')
				->select(
					'id', 'uri', 
					DB::raw('label_'.$lang.' as label'), 
					'class_name'
				)
				->orderBy('sort', 'asc')
				->get();
		
		$data = [];
		
		if(count($tmp)){
			$tmp = $tmp->toArray();
			
			foreach($tmp as $item){
				$item = (object)$item;
				
				$item->uri		= url(($lang != 'ua' ? $lang.'/' : '').$item->uri);
				$item->submenu	= [];
				
				$data[$item->id] = $item;
			}
			
			//
			
			$tmp = DB::table('submenu')
						->select(
							'id', 'parent_id', 
							'sub_uri as uri', 
							'sub_label_'.$lang.' as label'
						)
						->where('sub_public', '1')
						->orderBy('sub_sort', 'asc')
						->get();
			
			if(count($tmp)){
				$tmp = $tmp->toArray();
				
				foreach($tmp as $item){
					$item = (object)$item;
					
					$item->uri	= $data[$item->parent_id]->uri.'/'.$item->uri;
					
					$data[$item->parent_id]->submenu[$item->id] = $item;
				}
			}
		}
		
		//echo"<pre>";print_r($data);exit;
		
		$tmp = null;
		
		return $data;
	}
}
