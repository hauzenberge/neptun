<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;

class JobApps extends Model{
    
    protected $table = 'job_applications';
    
    public $timestamps = false;
    
    protected $fillable = [
        'name',
        'phone',
        'email',
        'job',
        'file',
        'type'
    ];
}
