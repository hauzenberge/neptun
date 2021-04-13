<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Submenu extends Model{
	
    protected $table = 'submenu';
    
    public $timestamps = false;
    
    protected $fillable = ['sub_sort', 'sub_uri', 'sub_public', 'sub_label_ua', 'sub_label_ru', 'sub_label_en', 'parent_id'];
    
    public function parent(){
		return $this->belongsTo(Menu::class, 'parent_id');
	}
}
