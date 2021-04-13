<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use App\Models\Page;

class Slides extends Model{
	
    protected $table	= 'page_slides';
    
    public $timestamps	= false;
    
    protected $fillable = [
		'page_id',
		'public', 
		'sort',
		'image',
		'alt_ua','alt_ru','alt_en',
		'image_ua','image_ru','image_en',
        'title_ua','title_ru','title_en',
        'description_ua','description_ru','description_en'
	];
    
	public function page(){
		return $this->belongsTo(Page::class, 'page_id');
	}
}
