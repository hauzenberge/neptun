<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use App\Models\Articles;

class ArticlesInterview extends Model{
	
    protected $table = 'articles_interview';
    
    public $timestamps = false;
    
    protected $fillable = [
		'article_id', 
		'question_ua', 'question_ru', 'question_en', 
		'image', 'video', 
		'answer_ua','answer_ru','answer_en'
	];
    
    public function page(){
		return $this->belongsTo(Articles::class, 'article_id');
	}
    
}
