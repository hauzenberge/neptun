<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EmailTemplates extends Model{
	
    public $timestamps = false;
	
    protected $table = 'email_templates';
	
	protected $fillable = [
        'name',
        'slug',
        'subject',
        'content',
        'description',
        'from_name',
        'from_email'
    ];
    
}
