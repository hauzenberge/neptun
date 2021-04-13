<?php

namespace App\Admin\Controllers;

use App\Models\Partners;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;

class PartnersController extends AdminController{
	
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'Наші партнери';
	
    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid(){
        $grid = new Grid(new Partners());
        
        $grid->column('id', __('Id'));
        $grid->column('name', __('admin.title'));
        $grid->column('logo', __('admin.logo'))->image();
        
        return $grid;
    }
    
    /**
     * Make a show builder.
     *
     * @param mixed $id
     * @return Show
     */
    protected function detail($id){
        $show = new Show(Partners::findOrFail($id));
        
        $show->field('id', __('Id'));
        $show->field('name', __('admin.title'));
        $show->field('logo', __('admin.logo'));
        
        return $show;
    }
    
    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form(){
        $form = new Form(new Partners());
        
        $form->text('name', __('admin.title'));
        $form->image('logo', __('admin.logo'));
        
        return $form;
    }
}
