<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ShipCalls extends Model{
	
    protected $table	= 'ship_calls';
    
    public $timestamps	= false;
    
    protected $fillable = [
		'created_at',
		'updated_at', 
		'vessel', 
		'dwt',
		'loa', 
		'cargo',
		'destination', 
		'eta', 
		'etd',
		'ets'
	];
}
