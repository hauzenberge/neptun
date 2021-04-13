<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use App\Models\Page;

class Advantages extends Model{
	
    protected $table = 'advantages';
    
    public $timestamps = false;
    
    protected $fillable = [
		'page_id',
		'sort', 'icon', 'public',
		'title_ua','title_ru','title_en',
		'description_ua','description_ru','description_en'
    ];
    
	public function page(){
		return $this->belongsTo(Page::class, 'page_id');
	}
}
