<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use App\Models\Page;

class Vacancies extends Model{
	
    protected $table = 'vacancies';
    
    public $timestamps = false;
    
    protected $fillable = [
		'created_at', 'updated_at', 'page_id', 'sort', 
		'public', 
		'name_ua', 'name_ru', 'name_en', 
		'description_ua', 'description_ru', 'description_en', 
		'characteristics_ua', 'characteristics_ru', 'characteristics_en', 
		'requirements_ua','requirements_ru','requirements_en',
		'label_ua','label_ru','label_en'
	];
    
    public function page(){
		return $this->belongsTo(Page::class, 'page_id');
	}
}
