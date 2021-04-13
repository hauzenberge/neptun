<?php

namespace App\Admin\Controllers;

use App\Models\ShipCalls;

use App\Http\Controllers\Controller;

use App\Admin\Actions\SyncShipCalls;

use App\Helpers\SheetsHelper;

use DB;

class ShipCallsSyncController extends Controller {
	
    public function index(){
		$sheets = (new SheetsHelper())->client();
		
		$result = $sheets->read(env("GOOGLE_SPREADSHEET_ID"));
        
        if($result){
			ShipCalls::query()->delete();
			
			foreach($result as $item){
				ShipCalls::create([
					'vessel'		=> $item[0],
					'dwt'			=> $item[1],
					'loa'			=> $item[2],
					'cargo'			=> $item[3],
					'destination'	=> $item[4],
					'eta'			=> $item[5],
					'etd'			=> $item[6],
					'ets'			=> $item[7]
				]);
			}
		}
		
		return redirect('/admin/ship_calls');
	}
}
