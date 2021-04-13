<?php

namespace App\Admin\Controllers;

use App\Models\Feedback;

use Encore\Admin\Controllers\AdminController;

use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;

class FeedbackController extends AdminController{
	
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'Запити зворотного зв\'язку';
    
    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid(){
        $grid = new Grid(new Feedback());
        
        $grid->column('id'			, __('Id'));
        $grid->column('created_at'	, __('admin.feedback.created'));
        $grid->column('name'		, __('admin.feedback.name'));
        //$grid->column('phone'		, __('admin.feedback.phone'));
        $grid->column('email'		, __('admin.feedback.email'));
        //$grid->column('text'		, __('admin.feedback.text'));
        
        $grid->model()->orderBy('created_at', 'desc');
        
        return $grid;
    }
    
    /**
     * Make a show builder.
     *
     * @param mixed $id
     * @return Show
     */
    protected function detail($id){
        $show = new Show(Feedback::findOrFail($id));
        
        $show->field('id'			, __('Id'));
        $show->field('created_at'	, __('admin.feedback.created'));
        $show->field('name'			, __('admin.feedback.name'));
        //$show->field('phone'		, __('admin.feedback.phone'));
        $show->field('email'		, __('admin.feedback.email'));
        $show->field('text'			, __('admin.feedback.text'));
        
        return $show;
    }
    
    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form(){
        $form = new Form(new Feedback());
        
        $form->text('name'			, __('admin.feedback.name'))->rules('required|min:2|max:50');
        //$form->mobile('phone'		, __('admin.feedback.phone'))->options(['mask' => '999999999999'])->rules('required');
        $form->email('email'		, __('admin.feedback.email'))->rules('required');
        
        $form->textarea('message'	, __('admin.feedback.text'))->rules('required|min:5|max:500')->rows(10);
        
        $form->file('file'			, __('admin.feedback.file'));
        
        return $form;
    }
}
