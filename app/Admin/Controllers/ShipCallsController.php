<?php

namespace App\Admin\Controllers;

use App\Models\ShipCalls;

use Encore\Admin\Controllers\AdminController;

use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;

use App\Admin\Actions\SyncShipCalls;

class ShipCallsController extends AdminController{
	
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'Суднозаходи';
	
    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid(){
        $grid = new Grid(new ShipCalls());
        
        $grid->column('vessel'		, __('admin.ship_calls.vessel'));
        $grid->column('dwt'			, __('admin.ship_calls.dwt'));
        $grid->column('loa'			, __('admin.ship_calls.loa'));
        $grid->column('cargo'		, __('admin.ship_calls.cargo'));
        $grid->column('destination'	, __('admin.ship_calls.destination'));
        $grid->column('eta'			, __('admin.ship_calls.eta'));
        $grid->column('etd'			, __('admin.ship_calls.etd'));
        $grid->column('ets'			, __('admin.ship_calls.ets'));
        
        $grid->actions(function($actions){
			//$tools->disableDelete();
			$actions->disableView();
			//$tools->disableList();
		});
		
		$grid->tools(function(Grid\Tools $tools){
			$tools->append(new SyncShipCalls());
		});
        
        return $grid;
    }
    
    /**
     * Make a show builder.
     *
     * @param mixed $id
     * @return Show
     */
    protected function detail($id){
        $show = new Show(ShipCalls::findOrFail($id));
        
        $show->field('vessel'		, __('admin.ship_calls.vessel'));
        $show->field('dwt'			, __('admin.ship_calls.dwt'));
        $show->field('loa'			, __('admin.ship_calls.loa'));
        $show->field('cargo'		, __('admin.ship_calls.cargo'));
        $show->field('destination'	, __('admin.ship_calls.destination'));
        $show->field('eta'			, __('admin.ship_calls.eta'));
        $show->field('etd'			, __('admin.ship_calls.etd'));
        $show->field('ets'			, __('admin.ship_calls.ets'));
        
        return $show;
    }
    
    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form(){
        $form = new Form(new ShipCalls());
        
        $form->text('vessel'		, __('admin.ship_calls.vessel'));
        $form->text('dwt'			, __('admin.ship_calls.dwt'));
        $form->text('loa'			, __('admin.ship_calls.loa'));
        $form->text('cargo'			, __('admin.ship_calls.cargo'));
        $form->text('destination'	, __('admin.ship_calls.destination'));
        $form->text('eta'			, __('admin.ship_calls.eta'));
        $form->text('etd'			, __('admin.ship_calls.etd'));
        $form->text('ets'			, __('admin.ship_calls.ets'));
        
        return $form;
    }
}
