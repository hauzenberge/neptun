<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use App\Models\Page;

class Docs extends Model{
	
    protected $table = 'docs';
    
    public $timestamps = false;
    
    protected $fillable = [
		'page_id',
		'file',
		'public_doc', 
		'description_ua','description_ru','description_en'
	];
    
	public function page(){
		return $this->belongsTo(Page::class, 'page_id');
	}
}
