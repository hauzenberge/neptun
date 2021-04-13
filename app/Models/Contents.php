<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;

class Contents extends Model {
	
    protected $table = 'contents';
    
    public $timestamps = false;
    
    protected $fillable = [
		'sort', 
		'field', 'public', 
		'description_ua', 'description_ru', 'description_en', 
		'image', 
		'content_ua','content_ru','content_en'
	];
    
	public function page(){
		return $this->belongsTo(Page::class, 'page_id');
	}
}
