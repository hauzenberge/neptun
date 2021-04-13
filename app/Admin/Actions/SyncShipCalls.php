<?php

namespace App\Admin\Actions;

use Encore\Admin\Actions\Action;
use Illuminate\Http\Request;

class SyncShipCalls extends Action {
	
	protected $selector = '.sync-post';
	
    public function handle(Request $request){
		return $this->response()->success('Виконано')->redirect('/admin/ship_calls');
	}
	
    public function html(){
        return "<a href=\"/admin/ship_calls/sync\" class=\"btn btn-sm btn-default\">Синхронізувати</a>";
    }
}
