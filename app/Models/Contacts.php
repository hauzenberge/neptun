<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use App\Models\Page;

use DB;

class Contacts extends Model{
	
    protected $table	= 'contacts';
    
    public $timestamps	= false;
    
    protected $fillable	= [
		'page_id', 
		'type', 
		'value', 
		'label_ua', 
		'label_ru', 
		'label_en', 
		'sort'
	];
    
    public function page(){
		return $this->belongsTo(Page::class, 'page_id');
	}
	
	static function getData($lang){
		$tmp = DB::table('contacts')
                    ->where('page_id', 10)
                    ->orderBy('sort', 'asc')
                    ->select(
						'type',
						'value',
						DB::raw('label_'.$lang.' as label')
                    )
                    ->get();
		
		$data = [
			'addresse'	=> [],
			'post'		=> [],
			'phone'		=> [],
			'email'		=> []
		];
		
		if(count($tmp)){
			foreach($tmp as $item){
				$item->value = trim($item->value);
				$item->label = trim($item->label);
				
				$data[$item->type][] = $item;
			}
		}
		
		return $data;
	}
}
