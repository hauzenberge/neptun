<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use App\Models\Page;

use DB;

class Langs extends Model{
	
    protected $table	= 'langs';
    
    public $timestamps	= false;
    
    protected $fillable = ['code', 'active', 'sort'];
    
    static function getList(){
		$tmp = DB::table('langs')
				->select(
					'id', 
					'code'
				)
				->where('active', '1')
				->orderBy('sort', 'asc')
				->get();
		
		$data = [];
		
		if(count($tmp)){
			foreach($tmp as $item){
				$data[] = $item->code;
			}
		}
		
		return $data;
	}
}
