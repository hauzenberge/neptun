<?php

namespace App\Admin\Controllers;

use App\Models\Articles;
use App\Models\ArticlesCategory;

use App\Admin\Controllers\MyAdminController;

use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;

use Encore\Admin\Layout\Content;

use App\Helpers\StringHelper;

class ArticlesController extends MyAdminController {
	
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'Статті';
    
    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid(){
        $grid = new Grid(new Articles());
        
        $grid->column('id', __('Id'));
        
        $grid->column('created_at'	, __('admin.created_at'))->sortable();
        $grid->column('updated_at'	, __('admin.updated_at'));
        
        $grid->column('type'		, __('admin.articles.category'))->display(function($type){
			return __('admin.articles.type_'.$type);
		});
        
        //$grid->column('category'	, __('admin.category'));
        
        $grid->column('name_ua'		, __('admin.name_article'));
        //$grid->column('uri'			, __('admin.uri'));
        
        $grid->column('image'		, __('admin.image'))->image();
		
		$grid->column('public'		, __('admin.public'))->display(function($public){
			$public = (int)$public;
			
			return $public > 0 ? '<i class="fa fa-check" style="color:green;" aria-hidden="true"></i>' : '<i class="fa fa-times" style="color:red;" aria-hidden="true"></i>';
		});
		
		$model = $grid->model();
        
        $model->orderBy('articles.created_at', 'desc');
		
		/*
		$model->leftJoin('articles_category', 'articles_category.id', '=', 'articles.category_id');
        
        $model->select(
			'articles.id', 
			'articles.created_at', 
			'articles.updated_at', 
			'articles.uri', 
			'articles.name', 
			'articles.title', 
			'articles.type', 
			'articles.image', 
			'articles_category.name as category'
		);
		*/
		
		$grid->actions(function($actions){
			//$tools->disableDelete();
			$actions->disableView();
			//$tools->disableList();
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
        $show = new Show(Articles::findOrFail($id));
        
        $show->field('id', __('Id'));
        
        $show->field('created_at', __('admin.created_at'));
        $show->field('updated_at', __('admin.updated_at'));
        
        $show->field('name_ua', __('admin.name_article').' (UA)');
        $show->field('name_ru', __('admin.name_article').' (RU)');
        $show->field('name_en', __('admin.name_article').' (EN)');
        
        $show->field('uri', __('admin.uri'));
        
        $show->field('title_ua', __('admin.title').' (UA)');
        $show->field('title_ru', __('admin.title').' (RU)');
        $show->field('title_en', __('admin.title').' (EN)');
        
        $show->field('keywords_ua', __('admin.meta_keywords').' (UA)');
        $show->field('keywords_ru', __('admin.meta_keywords').' (RU)');
        $show->field('keywords_en', __('admin.meta_keywords').' (EN)');
        
        $show->field('description_ua', __('admin.meta_description').' (UA)');
        $show->field('description_ru', __('admin.meta_description').' (RU)');
        $show->field('description_en', __('admin.meta_description').' (EN)');
        
        $show->field('image', __('admin.image'));
        
        return $show;
    }
    
    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form(){
        $form = new Form(new Articles());
        
        $this->configure($form);
		
		$id = $this->_id;
        
        $form->tab(__('admin.page_info'), function($tab){
			$tab->datetime('created_at'			, __('admin.created_at'))->rules('required')->default(date('Y-m-d H:i:s'));
			$tab->datetime('updated_at'			, __('admin.updated_at'));
			
			$tab->text('name_ua'				, __('admin.name_article').' (UA)')->rules('required|string|max:200');
			$tab->text('name_ru'				, __('admin.name_article').' (RU)')->rules('string|max:200');
			$tab->text('name_en'				, __('admin.name_article').' (EN)')->rules('string|max:200');
			
			$tab->text('uri'					, __('admin.uri'))->rules('max:200');
			
			$tab->switch('public'				, __('admin.public'));
			
			//
			
			$tab->radio('type'					, __('admin.articles.category'))
					->options([
						'video'		=> __('admin.articles.type_video'),
						'about_us'	=> __('admin.articles.type_about_us'),
						'articles'	=> __('admin.articles.type_articles')
					])
					->rules('required');
			
			//
			
			//$category = ArticlesCategory::orderBy('name', 'asc')->get()->pluck('name', 'id')->toArray();
			//$tab->select('category_id'			, __('admin.category'))->options(($category ? array_merge([0 => ''], $category) : []));
			
			//
			
			$tab->image('image'					, __('admin.announcement_image'))->help('396x270px')->rules('required')->removable();
			
			$tab->url('youtube'					, __('admin.articles.youtube'))->rules('max:100');
			$tab->text('duration'				, __('admin.articles.duration'))->help('m:s')->rules('max:8');
        });
        
		$form->tab(__('admin.page_seo'), function($tab){
			$tab->text('title_ua'				, __('admin.title').' (UA)')->rules('max:200');
			$tab->text('title_ru'				, __('admin.title').' (RU)')->rules('max:200');
			$tab->text('title_en'				, __('admin.title').' (EN)')->rules('max:200');
			
			$tab->text('keywords_ua'			, __('admin.meta_keywords').' (UA)')->rules('max:200');
			$tab->text('keywords_ru'			, __('admin.meta_keywords').' (RU)')->rules('max:200');
			$tab->text('keywords_en'			, __('admin.meta_keywords').' (EN)')->rules('max:200');
			
			$tab->text('description_ua'			, __('admin.meta_description').' (UA)')->rules('max:200');
			$tab->text('description_ru'			, __('admin.meta_description').' (RU)')->rules('max:200');
			$tab->text('description_en'			, __('admin.meta_description').' (EN)')->rules('max:200');
        });
        
        if($this->_edit){
			$page		= [];
			
			if(!$this->_update){
				$page = $form->model()->where('id', $id)->select('type')->first();
			}
			
			$type		= $this->_update ? request()->input('type') : $page->type;
			
			//
			
			$form->tab(__('admin.page_content'), function($tab) use ($type){
				if($type != 'video'){
					$tab->image('main_image'			, __('admin.main_image'))->help('824x562px')->removable();
					
					$tab->summernote('text_ua'				, __('admin.page_text').' (UA)')->help(__('admin.content_codes'));
					$tab->summernote('text_ru'				, __('admin.page_text').' (RU)')->help(__('admin.content_codes'));
					$tab->summernote('text_en'				, __('admin.page_text').' (EN)')->help(__('admin.content_codes'));
				}else{
					$tab->summernote('text_ua'				, __('admin.page_text').' (UA)');
					$tab->summernote('text_ru'				, __('admin.page_text').' (RU)');
					$tab->summernote('text_en'				, __('admin.page_text').' (EN)');
				}
			});
			
			if($type != 'video'){
				if(false){
					$form->tab(__('admin.images'), function($tab){
						$tab->hasMany('images', '', function($item){
							$item->decimal('sort'			, __('admin.sort_img'));
							
							$item->image('img'				, __('admin.image'))->help('803x420px')->removable();
							
							$item->text('alt_ua'				, __('admin.alt').' (UA)')->rules('max:200');
							$item->text('alt_ru'				, __('admin.alt').' (RU)')->rules('max:200');
							$item->text('alt_en'				, __('admin.alt').' (EN)')->rules('max:200');
						});
					});
				}
				
				$form->tab(__('admin.slider'), function($tab){
					//$tab->text('slider_alt', __('admin.slider_alt'))->rules('max:250');
					
					$tab->hasMany('slides', '', function($item){
						$item->decimal('slide_sort'		, __('admin.sort_img'));
						
						$item->image('slide_image'		, __('admin.image'))->help('822x462px')->removable();
						
						$item->text('slide_alt_ua'		, __('admin.alt').' (UA)')->rules('max:200');
						$item->text('slide_alt_ru'		, __('admin.alt').' (RU)')->rules('max:200');
						$item->text('slide_alt_en'		, __('admin.alt').' (EN)')->rules('max:200');
					});
				});
				
				$form->tab(__('admin.interview.tab'), function($tab){
					$tab->text('respondent_name_ua'			, __('admin.respondent.name').' (UA)')->help(__('admin.respondent.tab'))->rules('max:100');
					$tab->text('respondent_name_ru'			, __('admin.respondent.name').' (RU)')->rules('max:100');
					$tab->text('respondent_name_en'			, __('admin.respondent.name').' (EN)')->rules('max:100');
					
					$tab->text('respondent_position_ua'		, __('admin.respondent.position').' (UA)')->rules('max:100');
					$tab->text('respondent_position_ru'		, __('admin.respondent.position').' (RU)')->rules('max:100');
					$tab->text('respondent_position_en'		, __('admin.respondent.position').' (EN)')->rules('max:100');
					
					$tab->image('respondent_photo'			, __('admin.respondent.photo'))->help('290x317px')->removable();
					
					$tab->hasMany('interview', __('admin.interview.faq'), function($item){
						$item->image('image'			, __('admin.interview.image'))->help('504x283px')->removable();
						$item->url('video'				, __('admin.interview.youtube'))->rules('max:100');
						
						$item->text('question_ua'		, __('admin.interview.question').' (UA)')->rules('max:200');
						$item->text('question_ru'		, __('admin.interview.question').' (RU)')->rules('max:200');
						$item->text('question_en'		, __('admin.interview.question').' (EN)')->rules('max:200');
						
						$item->summernote('answer_ua'		, __('admin.interview.answer').' (UA)');
						$item->summernote('answer_ru'		, __('admin.interview.answer').' (RU)');
						$item->summernote('answer_en'		, __('admin.interview.answer').' (EN)');
					})->useTab();
				});
			}
		}
		
		// callback before save
		$form->saving(function (Form $form){
			$form->name_ua		= trim($form->name_ua);
			$form->name_ru		= trim($form->name_ru);
			$form->name_en		= trim($form->name_en);
			
			$form->title_ua		= trim($form->title_ua);
			$form->title_ru		= trim($form->title_ru);
			$form->title_en		= trim($form->title_en);
			
			if($form->name_ua && !$form->uri){
				$form->uri = StringHelper::url_title($form->name_ua, 'dash');
			}
		});
		
        return $form;
    }
}
