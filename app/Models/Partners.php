<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use App\Models\Page;

class Partners extends Model{
	
    protected $table = 'partners';
    
    public $timestamps = false;
    
    protected $fillable = [
		'page_id', 
        'partner_public',
		'partner_sort', 
		'partner_name', 
		'partner_logo', 
		'partner_description_ua', 
		'partner_description_ru', 
		'partner_description_en'
	];
    
    public function page(){
		return $this->belongsTo(Page::class, 'page_id');
	}
}
