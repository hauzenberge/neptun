<?php

namespace App\Admin\Controllers;

use App\Models\Langs;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;

class LangsController extends AdminController{
	
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'Мови';
    
    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid(){
        $grid = new Grid(new Langs());
        
        $grid->column('code'	, __('admin.langs.code'));
        $grid->column('active'	, __('admin.langs.active'))->display(function($active){
			$active = (int)$active;
			
			return $active > 0 ? '<i class="fa fa-check" style="color:green;" aria-hidden="true"></i>' : '<i class="fa fa-times" style="color:red;" aria-hidden="true"></i>';
		});
        
        $grid->disableCreateButton();
        
        $grid->actions(function($actions){
			$actions->disableDelete();
			$actions->disableView();
			//$tools->disableList();
		});
        
        return $grid;
    }
    
    protected function detail($id){
		header('Location: /admin/langs/'.$id.'/edit');
		return;
		//return redirect('/login');
	}
	
    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form(){
        $form = new Form(new Langs());
        
        $form->footer(function($footer){
			// disable reset btn
			$footer->disableReset();
			
			// disable `View` checkbox
			$footer->disableViewCheck();
			
			$footer->disableCreatingCheck();
		});
        
        $form->text('code'		, __('admin.langs.code'))->rules('max:2')->readonly();
        $form->decimal('sort'	, __('admin.langs.sort'))->default(0);
        $form->switch('active'	, __('admin.langs.active'));
        
        return $form;
    }
}
