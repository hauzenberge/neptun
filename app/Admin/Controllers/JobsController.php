<?php

namespace App\Admin\Controllers;

use App\Models\JobApps;

use Encore\Admin\Controllers\AdminController;

use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;

class JobsController extends AdminController{
	
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'Заявки на роботу';
    
    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid(){
        $grid = new Grid(new JobApps());
        
        $grid->column('id'			, __('Id'));
        $grid->column('created_at'	, __('admin.JobApps.created'));
        $grid->column('name'		, __('admin.JobApps.name'));
        $grid->column('phone'		, __('admin.JobApps.phone'));
        $grid->column('email'		, __('admin.JobApps.email'));
        $grid->column('job'			, __('admin.JobApps.job'));
        
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
        $show = new Show(JobApps::findOrFail($id));
        
        $show->field('id'			, __('Id'));
        $show->field('created_at'	, __('admin.JobApps.created'));
        $show->field('name'			, __('admin.JobApps.name'));
        $show->field('phone'		, __('admin.JobApps.phone'));
        $show->field('email'		, __('admin.JobApps.email'));
        $show->field('job'			, __('admin.JobApps.job'));
        
        return $show;
    }
    
    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form(){
        $form = new Form(new JobApps());
        
        $form->text('name'			, __('admin.JobApps.name'))->rules('required|min:2|max:50');
        $form->mobile('phone'		, __('admin.JobApps.phone'))->options(['mask' => '999999999999'])->rules('required');
        $form->email('email'		, __('admin.JobApps.email'))->rules('required|max:50');
        $form->text('job'			, __('admin.JobApps.job'))->rules('required|max:100');
        
        $form->file('file'			, __('admin.JobApps.file'));
        
        return $form;
    }
}
