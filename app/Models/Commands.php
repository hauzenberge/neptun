<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Commands extends Model{
	
    protected $table = 'commands';
    
    public $timestamps = false;
    
    protected $fillable = [
		'sort',
		'name_ua','name_ru','name_en',
		'photo',
		'position_ua','position_ru','position_en',
		'description_ua','description_ru','description_en',
		'public'
	];
    
	public function page(){
		return $this->belongsTo(Page::class, 'page_id');
	}
}
