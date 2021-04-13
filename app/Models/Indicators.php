<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use App\Models\Page;

class Indicators extends Model{
	
    protected $table = 'indicators';
    
    public $timestamps = false;
    
    protected $fillable = [
		'page_id','sort','digit', 'public',
		'bold_ua','bold_ru','bold_en',
		'description_ua','description_ru','description_en'
	];
    
	public function page(){
		return $this->belongsTo(Page::class, 'page_id');
	}
}
