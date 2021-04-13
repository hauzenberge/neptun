<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use App\Models\Articles;

class ArticlesSlides extends Model{
	
    protected $table = 'articles_slides';
    
    public $timestamps = false;
    
    protected $fillable = [
		'article_id', 'slide_sort', 'slide_image',
		'slide_alt_ua', 'slide_alt_ru', 'slide_alt_en'
	];
    
    public function page(){
		return $this->belongsTo(Articles::class, 'article_id');
	}
    
}
