<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use App\Models\Page;

class Gallery extends Model{
	
    protected $table = 'gallery';
    
    public $timestamps = false;
    
    protected $fillable = [
		'page_id','public', 
		'type',
		'alt_ua','alt_ru','alt_en',
		'youtube',
		'duration',
		'image'
	];
    
	public function page(){
		return $this->belongsTo(Page::class, 'page_id');
	}
}
