<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ArticlesCategory extends Model{
	
    protected $table = 'articles_category';
    
    public $timestamps = false;
    
    protected $fillable = ['name', 'color'];
    
}
