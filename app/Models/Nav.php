<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use App\Models\Page;

class Nav extends Model{
	
    protected $table = 'page_nav';
    
    public $timestamps = false;
    
    protected $fillable = ['page_id','sort','url','label_ua','label_ru','label_en'];
    
	public function page(){
		return $this->belongsTo(Page::class, 'page_id');
	}
}
