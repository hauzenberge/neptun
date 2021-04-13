<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Facts extends Model{
	
    protected $table = 'facts';
    
    public $timestamps = false;
    
    protected $fillable = [
		'sort',
		'title_ua', 'title_ru', 'title_en',
		'icon', 'public',
		'text_ua', 'text_ru', 'text_en'
	];
    
	public function page(){
		return $this->belongsTo(Page::class, 'page_id');
	}
}
