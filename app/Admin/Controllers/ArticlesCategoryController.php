<?php

namespace App\Admin\Controllers;

use App\Models\ArticlesCategory;

use Encore\Admin\Controllers\AdminController;

use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;

use DB;

use App\Helpers\StringHelper;

class ArticlesCategoryController extends AdminController{
	
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'Рубрики';
	
    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid(){
        $grid = new Grid(new ArticlesCategory());
		
        $grid->column('name_ua'		, __('admin.articles.name'))->sortable();
		
		$grid->column('color'		, __('admin.articles.color'))->display(function($color){
			return '<span style="display:inline-block;width:15px;height:15px;background-color:'.$color.';"></span>';
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
        $show = new Show(ArticlesCategory::findOrFail($id));
		
        $show->field('name_ua'			, __('admin.articles.name').' (UA)');
		$show->field('name_ru'			, __('admin.articles.name').' (RU)');
		$show->field('name_en'			, __('admin.articles.name').' (EN)');
		
        return $show;
    }
    
    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form(){
        $form = new Form(new ArticlesCategory());
		
        $form->text('name_ua'			, __('admin.articles.name').' (UA)')->rules('required|max:100');
        $form->text('name_ru'			, __('admin.articles.name').' (RU)')->rules('max:100');
        $form->text('name_en'			, __('admin.articles.name').' (EN)')->rules('max:100');
        
		$form->color('color'		, __('admin.articles.color'))->rules('required|max:7');
		
		// callback before save
		$form->saving(function (Form $form){
			$form->name_ua = trim($form->name_ua);
		});
		
        return $form;
    }
}
