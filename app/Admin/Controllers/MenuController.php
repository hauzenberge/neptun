<?php

namespace App\Admin\Controllers;

use App\Admin\Controllers\MyAdminController;

use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;

use App\Models\Menu;

use DB;

use App\Helpers\StringHelper;

class MenuController extends MyAdminController {
	
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'Керування меню';
	
    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid(){
        $grid = new Grid(new Menu);
		
		$grid->column('sort'		, __('admin.site-menu.sort'))->sortable();
		
        $grid->column('id'			, __('ID'));
        
        $grid->column('uri'			, __('admin.site-menu.uri'));
        
		$grid->column('label_ua'	, __('admin.site-menu.label'));
		
        return $grid;
    }
	
    protected function detail($id){
		header('Location: /admin/menu/'.$id.'/edit');
		return;
		//return redirect('/login');
	}
	
    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form(){
        $form = new Form(new Menu);
		
		$this->configure($form);
		
		$id = $this->_id;
		
		$form->tab(__('admin.site-menu.basic'), function($form) use ($id){
			$form->display('id'			, __('ID'));
			
			$form->text('sort'			, __('admin.site-menu.sort'));
			
			$form->text('uri'			, __('admin.site-menu.uri'))->rules('max:100');
			
			$form->text('label_ua'		, __('admin.site-menu.label').' (UA)')->rules('required|max:100');
			$form->text('label_ru'		, __('admin.site-menu.label').' (RU)')->rules('max:100');
			$form->text('label_en'		, __('admin.site-menu.label').' (EN)')->rules('max:100');
			
			$form->text('class_name'	, __('admin.site-menu.class'))->rules('max:15');
			
			$form->switch('noindex'		, __('admin.site-menu.noindex'));
		});
		
		$form->tab(__('admin.site-menu.submenu'), function($form) use ($id){
			$form->hasMany('submenu', '', function($form) use ($id){
				$form->decimal('sub_sort'	, __('admin.site-menu.sort'))->rules('required');
				
				$form->text('sub_uri'		, __('admin.site-menu.uri'))->rules('max:50');
				
				$form->switch('sub_public'	, __('admin.public'));
				
				$form->text('sub_label_ua'	, __('admin.site-menu.label').' (UA)')->rules('required|max:50');
				$form->text('sub_label_ru'	, __('admin.site-menu.label').' (RU)')->rules('max:50');
				$form->text('sub_label_en'	, __('admin.site-menu.label').' (EN)')->rules('max:50');
			});
		});
		
		// callback before save
		$form->saving(function (Form $form){
			$form->sort		= (int)trim($form->sort);
			$form->noindex	= (int)trim($form->noindex);
			
			$form->uri		= trim($form->uri);
			
			$form->label_ua	= trim($form->label_ua);
			$form->label_ru	= trim($form->label_ru);
			$form->label_en	= trim($form->label_en);
			
			if($form->sort < 1){
				$count = DB::table('menu')->count();
				$count++;
				
				$form->sort = $count;
			}
			
			if($form->label_ua && !$form->uri){
				$form->uri = StringHelper::url_title($form->label_ua, 'dash');
			}
		});
				
        return $form;
    }
}
