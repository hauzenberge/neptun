<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use App\Models\Articles;

class ArticlesImages extends Model{
	
    protected $table = 'articles_images';
    
    public $timestamps = false;
    
    protected $fillable = ['article_id', 'img', 'alt_ua', 'alt_ru', 'alt_en', 'sort'];
    
    public function page(){
		return $this->belongsTo(Articles::class, 'article_id');
	}
    
}
