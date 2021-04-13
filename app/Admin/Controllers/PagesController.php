<?php

namespace App\Admin\Controllers;

use App\Models\Page;

use App\Admin\Controllers\MyAdminController;

use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;

use Encore\Admin\Layout\Content;

use App\Helpers\StringHelper;

class PagesController extends MyAdminController {
	
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'Сторінки';
	
    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid(){
        $grid = new Grid(new Page());
        
        $grid->column('id'				, __('ID'));
        
        $grid->column('created_at'		, __('admin.pages.created_at'));
        $grid->column('updated_at'		, __('admin.pages.updated_at'));
        
        $grid->column('parent'			, __('admin.pages.parent'));
        
        $grid->column('uri'				, __('admin.pages.uri'));
        $grid->column('name'			, __('admin.pages.name'));
        
        //$grid->column('title_ua'		, __('admin.pages.meta_title'));
        
        $model = $grid->model();
        
        $model->leftJoin('pages as parent', 'parent.id', '=', 'pages.parent_id');
        
        $model->select(
			'pages.id', 
			'pages.created_at', 
			'pages.updated_at', 
			'pages.uri', 
			'pages.name', 
			//'pages.title', 
			'parent.name as parent'
		);
        
		$grid->actions(function($actions){
			//$tools->disableDelete();
			$actions->disableView();
			//$tools->disableList();
		});
		
        return $grid;
    }
	
    protected function detail($id){
		header('Location: /admin/pages/'.$id.'/edit');
		return;
		//return redirect('/login');
	}
	
    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form(){
        $form = new Form(new Page());
        
        $this->configure($form);
		
		$id = $this->_id;
        
        $form->tab(__('admin.pages.page_info')		, function($form) use ($id){
			if($id){
				$form->datetime('updated_at', __('admin.pages.updated_at'))->default(date('Y-m-d H:i:s'));
			}
			
			$pages = Page::whereRaw('uri != "index"')->orderBy('name', 'asc')->get()->pluck('name', 'id');
			
			$form->select('parent_id'			, __('admin.pages.parent'))->options($pages);
			
			$form->text('name'					, __('admin.pages.name'))->rules('max:100');
			$form->text('uri'					, __('admin.pages.uri'))->rules('max:100');
			
			//$form->display('created_at', 'Created At');
			//$form->display('updated_at', 'Updated At');
		});
		
		$form->tab(__('admin.pages.page_seo')		, function($form){
			$form->text('title_ua'			, __('admin.pages.meta_title').' (UA)')->rules('max:150');
			$form->text('title_ru'			, __('admin.pages.meta_title').' (RU)')->rules('max:150');
			$form->text('title_en'			, __('admin.pages.meta_title').' (EN)')->rules('max:150');
			
			$form->text('keywords_ua'		, __('admin.pages.meta_keywords').' (UA)')->rules('max:150');
			$form->text('keywords_ru'		, __('admin.pages.meta_keywords').' (RU)')->rules('max:150');
			$form->text('keywords_en'		, __('admin.pages.meta_keywords').' (EN)')->rules('max:150');
			
			$form->text('description_ua'	, __('admin.pages.meta_description').' (UA)')->rules('max:150');
			$form->text('description_ru'	, __('admin.pages.meta_description').' (RU)')->rules('max:150');
			$form->text('description_en'	, __('admin.pages.meta_description').' (EN)')->rules('max:150');
		});

		$form->tab(__('Media')		, function($form){			

			$form->radio('video_view','Яке відео відображати?')
            ->options([
              'youtube' =>  'З YouTube',
              'local' =>   'Завантажене',
            ]);
            /*
            $form->radioCard( 'video_ua','')
            ->options([
              1 =>  'Показати вставлене відео з YouTube',
              2 =>  'Показати завантажене відео',
            ])
            ->default(1)
            ->when(1, function (Form $form) {
            	$form->text('video_ua_youtube', 'iframe відео з youtube для української версії сайту');
	            $form->text('video_ru_youtube', 'iframe відео з youtube для російської версії сайту');
	            $form->text('video_en_youtube', 'iframe відео з youtube для англійської версії сайту');
            })
            ->when(2, function (Form $form) {  
            	$form->file('video_ua', 'Файл відео для української версії сайту')->move('/videos');
				$form->file('video_ru', 'Файл відео для російської версії сайту')->move('/videos');
				$form->file('video_en', 'Файл відео для англійської версії сайту')->move('/videos');
            });
            */

            $form->file('video_ua', 'Файл відео для української версії сайту')->move('/videos')->removable();

            $form->text('video_ua_youtube', 'iframe відео з youtube для української версії сайту');

			$form->file('video_ru', 'Файл відео для російської версії сайту')->move('/videos')->removable();
			$form->text('video_ru_youtube', 'iframe відео з youtube для російської версії сайту');

			$form->file('video_en', 'Файл відео для англійської версії сайту')->move('/videos')->removable();
			$form->text('video_en_youtube', 'iframe відео з youtube для англійської версії сайту');

		});

		
		if(in_array($id, [2, 7, 8, 9])){
			$form->tab(__('admin.page_content')		, function($form) use ($id){
				if($id == 8){
					//$form->summernote('text'				, __('admin.page_text'));
					
					$form->textarea('text_ua'				, __('admin.page_text').' (UA)');
					$form->textarea('text_ru'				, __('admin.page_text').' (RU)');
					$form->textarea('text_en'				, __('admin.page_text').' (EN)');
				}elseif($id == 2 || $id == 11){
					$form->summernote('text_ua'				, __('admin.page_text').' (UA)');
					$form->summernote('text_ru'				, __('admin.page_text').' (RU)');
					$form->summernote('text_en'				, __('admin.page_text').' (EN)');
					
					$form->image('image'					, __('admin.slides.image'))->removable();
                    
                    $form->text('video_ua'			        , __('admin.pages.video_link').' (UA)')->rules('max:150');
                    $form->text('video_ru'			        , __('admin.pages.video_link').' (RU)')->rules('max:150');
                    $form->text('video_en'			        , __('admin.pages.video_link').' (EN)')->rules('max:150');
				}else{
					if($id != 7){
						$form->summernote('text_ua'				, __('admin.page_text').' (UA)');
						$form->summernote('text_ru'				, __('admin.page_text').' (RU)');
						$form->summernote('text_en'				, __('admin.page_text').' (EN)');
					}
				}
				
				if($id == 7 || $id == 9){
					$form->image('image'					, __('admin.page_header_image'))->removable();
					
					$form->switch('public_cetegory'			, __('admin.pages.public_cetegory'));
				}
			});
		}
		
		if($id != 7 && $id != 10){
			if($id != 9 && $id != 7){
				$form->tab(__('admin.pages.page_blocks')	, function($form) use ($id){
					if($id == 1 || $id == 6){
						$form->switch('liveshow'	, __('admin.pages.liveshow'));
					}
					
					$form->hasMany('contents', '', function($form) use ($id){
						$form->decimal('sort'				, __('admin.slides.sort'))->default(0);
						
						$form->switch('public'				, __('admin.public'));
						
						if($id < 11){
							$form->text('field'					, __('admin.field_key'))->rules('max:40');
						}
						
						$form->image('image'				, __('admin.slides.image'))->removable();
						
						if($id < 11){
							$form->text('description_ua'		, __('admin.field_description').' (UA)')->rules('max:150');
							$form->text('description_ru'		, __('admin.field_description').' (RU)')->rules('max:150');
							$form->text('description_en'		, __('admin.field_description').' (EN)')->rules('max:150');
						}
						
                        $form->summernote('content_ua'		, __('admin.field_content').' (UA)');
                        $form->summernote('content_ru'		, __('admin.field_content').' (RU)');
                        $form->summernote('content_en'		, __('admin.field_content').' (EN)');
					})->useTab();
				});
			}else{
				$form->tab(__('admin.vacancies.tab'), function($form){
					$form->hasMany('vacancies', '', function($form){
						//$form->decimal('sort'				, __('admin.vacancies.sort'));
						
						$form->datetime('created_at'		, __('admin.vacancies.created_at'))->default(date('Y-m-d H:i:s'));
						
						$form->switch('public'				, __('admin.public'));
						
						$form->text('name_ua'				, __('admin.vacancies.name').' (UA)')->rules('max:200');
						$form->text('name_ru'				, __('admin.vacancies.name').' (RU)')->rules('max:200');
						$form->text('name_en'				, __('admin.vacancies.name').' (EN)')->rules('max:200');
						
						$form->text('label_ua'				, __('admin.vacancies.label').' (UA)')->rules('max:250');
						$form->summernote('description_ua'	, __('admin.vacancies.description').' (UA)');
						
						$form->text('label_ru'				, __('admin.vacancies.label').' (RU)')->rules('max:250');
						$form->summernote('description_ru'	, __('admin.vacancies.description').' (RU)');
						
						$form->text('label_en'				, __('admin.vacancies.label').' (EN)')->rules('max:250');
						$form->summernote('description_en'	, __('admin.vacancies.description').' (EN)');
						
						//$form->textarea('characteristics_ua', __('admin.vacancies.characteristics').' (UA)');
						//$form->textarea('characteristics_ru', __('admin.vacancies.characteristics').' (RU)');
						//$form->textarea('characteristics_en', __('admin.vacancies.characteristics').' (EN)');
						
						//$form->textarea('requirements_ua'	, __('admin.vacancies.requirements').' (UA)');
						//$form->textarea('requirements_ru'	, __('admin.vacancies.requirements').' (RU)');
						//$form->textarea('requirements_en'	, __('admin.vacancies.requirements').' (EN)');
					})->useTab();
				});
			}
		}
		
		//var_dump($id);exit;
		
		if($id == 1 || $id == 2){
			$form->tab(__('admin.slides.tab'), function($form) use ($id){
				$form->hasMany('slides', '', function($form) use ($id){
					$form->decimal('sort'			, __('admin.slides.sort'))->default(0);
					
					$form->switch('public'			, __('admin.public'));
					
					$form->text('alt_ua'			, __('admin.slides.alt').' (UA)')->rules('max:150');
					$form->text('alt_ru'			, __('admin.slides.alt').' (RU)')->rules('max:150');
					$form->text('alt_en'			, __('admin.slides.alt').' (EN)')->rules('max:150');
					
                    if($id == 1){
                        $form->text('title_ua'			, __('admin.slides.title').' (UA)')->rules('max:150');
                        $form->text('title_ru'			, __('admin.slides.title').' (RU)')->rules('max:150');
                        $form->text('title_en'			, __('admin.slides.title').' (EN)')->rules('max:150');
                        
                        $form->textarea('description_ua', __('admin.slides.description').' (UA)')->rules('max:500');
                        $form->textarea('description_ru', __('admin.slides.description').' (RU)')->rules('max:500');
                        $form->textarea('description_en', __('admin.slides.description').' (EN)')->rules('max:500');
                    }
                    
					$help = '';
					
					if($id == 1){
						$help = '1440×430px';
					}elseif($id == 2){
						$help = '1250×480px';
					}
					
					if($id == 1){
						$form->image('image_ua'			, __('admin.slides.image').' (UA)')->help($help)->removable();
						$form->image('image_ru'			, __('admin.slides.image').' (RU)')->help($help)->removable();
						$form->image('image_en'			, __('admin.slides.image').' (EN)')->help($help)->removable();
					}else{
						$form->image('image'			, __('admin.slides.image'))->help($help)->removable();
					}
				})->useTab();
			});
		}
		
		if($id == 1){
			$form->tab(__('admin.nav.tab'), function($form){
				$form->hasMany('nav', '', function($form){
					$form->decimal('sort'				, __('admin.nav.sort'));
					
					$form->text('label_ua'				, __('admin.nav.label').' (UA)')->rules('max:30');
					$form->text('label_ru'				, __('admin.nav.label').' (RU)')->rules('max:30');
					$form->text('label_en'				, __('admin.nav.label').' (EN)')->rules('max:30');
					
					$form->text('url'					, __('admin.nav.url'))->rules('max:30');
				});
			});
		}
		
		if($id == 6){
			$form->tab(__('admin.gallery.tab'), function($form){
				$form->hasMany('gallery', '', function($form){
					$form->switch('public'		, __('admin.public'));
					
					$form->radio('type'			, __('admin.gallery.type'))
						->options([
							'video'		=> __('admin.gallery.video'),
							'live'		=> __('admin.gallery.live'),
							'image'		=> __('admin.gallery.image')
						])
						->rules('required');
					
					$form->text('alt_ua'		, __('admin.gallery.alt').' (UA)')->rules('max:100');
					$form->text('alt_ru'		, __('admin.gallery.alt').' (RU)')->rules('max:100');
					$form->text('alt_en'		, __('admin.gallery.alt').' (EN)')->rules('max:100');
					
					$form->url('youtube'		, __('admin.gallery.youtube'))->rules('max:100');
					
					$form->text('duration'		, __('admin.gallery.duration'))->help('m:s')->rules('max:6');
					
					$form->image('image'		, __('admin.gallery.image'))->help('398x398px')->removable();
				});
			});
		}
		
		if($id == 8){
			$form->tab(__('admin.docs.tab'), function($form){
				$form->hasMany('docs', '', function($form){
					$form->radio('public_doc'		, __('admin.public'))
							->options([
								'off'	=> 'off',
								'on'	=> 'on'
							])
							->default('off')
							->rules('required');
					
					$form->textarea('description_ua', __('admin.docs.description').' (UA)')->rules('max:250');
					$form->textarea('description_ru', __('admin.docs.description').' (RU)')->rules('max:250');
					$form->textarea('description_en', __('admin.docs.description').' (EN)')->rules('max:250');
					
					$form->file('file'				, __('admin.docs.file'));
				});
			});
		}
		
		if($id == 2){
			$form->tab(__('admin.indicators.tab')	, function($form){
				$form->switch('public_indicators'			, __('admin.public'));
				
				$form->hasMany('indicators', '', function($form){
					$form->decimal('sort'			, __('admin.indicators.sort'))->default(0);
					
					$form->switch('public'			, __('admin.public'));
					
					$form->text('digit'				, __('admin.indicators.digit'))->rules('max:100');
					
					$form->text('bold_ua'			, __('admin.indicators.bold').' (UA)')->rules('max:100');
					$form->text('bold_ru'			, __('admin.indicators.bold').' (RU)')->rules('max:100');
					$form->text('bold_en'			, __('admin.indicators.bold').' (EN)')->rules('max:100');
					
					$form->text('description_ua'	, __('admin.indicators.description').' (UA)');
					$form->text('description_ru'	, __('admin.indicators.description').' (RU)');
					$form->text('description_en'	, __('admin.indicators.description').' (EN)');
				})->useTab();
			});
			
			$form->tab(__('admin.advantages.tab')	, function($form){
				$form->text('advantages_label_ua'	, __('admin.advantages.label').' (UA)')->rules('max:100');
				$form->text('advantages_label_ru'	, __('admin.advantages.label').' (RU)')->rules('max:100');
				$form->text('advantages_label_en'	, __('admin.advantages.label').' (EN)')->rules('max:100');
				
				$form->switch('public_advantages'	, __('admin.public'));
				
				$form->hasMany('advantages'			, '', function($form){
					$form->decimal('sort'				, __('admin.advantages.sort'))->default(0);
					
					$form->switch('public'				, __('admin.public'));
					
					$form->file('icon'					, __('admin.facts.icon'))->removable();
					
					$form->text('title_ua'				, __('admin.advantages.title').' (UA)')->rules('max:100');
					$form->text('title_ru'				, __('admin.advantages.title').' (RU)')->rules('max:100');
					$form->text('title_en'				, __('admin.advantages.title').' (EN)')->rules('max:100');
					
					$form->summernoteMini('description_ua'	, __('admin.advantages.description').' (UA)');
					$form->summernoteMini('description_ru'	, __('admin.advantages.description').' (RU)');
					$form->summernoteMini('description_en'	, __('admin.advantages.description').' (EN)');
				})->useTab();
			});
			
			$form->tab(__('admin.commands.tab')		, function($form){
				$form->switch('about_last_public'		, __('admin.about_last.public'));
				
				$form->text('about_last_title_ua'		, __('admin.about_last.title').' (UA)')->rules('max:200');
				$form->text('about_last_title_ru'		, __('admin.about_last.title').' (RU)')->rules('max:200');
				$form->text('about_last_title_en'		, __('admin.about_last.title').' (EN)')->rules('max:200');
				
				$form->text('about_last_btn_ua'			, __('admin.about_last.label').' (UA)')->rules('max:100');
				$form->text('about_last_btn_ru'			, __('admin.about_last.label').' (RU)')->rules('max:100');
				$form->text('about_last_btn_en'			, __('admin.about_last.label').' (EN)')->rules('max:100');
				
				$form->text('about_last_btn_url'		, __('admin.about_last.url'))->rules('max:100');
				
				$form->image('about_last_image'			, __('admin.about_last.image'))->help('380×407px')->removable();
				
				//
				
				$form->switch('public_commands'		, __('admin.commands.public'));
				
				$form->hasMany('commands', '', function($form){
					$form->decimal('sort'			, __('admin.commands.sort'));
					
					$form->switch('public'			, __('admin.public'));
					
					$form->image('photo'			, __('admin.commands.photo'))->help('290×317px')->removable();
					
					$form->text('name_ua'			, __('admin.commands.name').' (UA)')->rules('required|max:60');
					$form->text('name_ru'			, __('admin.commands.name').' (RU)')->rules('max:60');
					$form->text('name_en'			, __('admin.commands.name').' (EN)')->rules('max:60');
					
					$form->text('position_ua'		, __('admin.commands.position').' (UA)')->rules('max:60');
					$form->text('position_ru'		, __('admin.commands.position').' (RU)')->rules('max:60');
					$form->text('position_en'		, __('admin.commands.position').' (EN)')->rules('max:60');
					
					$form->textarea('description_ua'	, __('admin.commands.description').' (UA)');
					$form->textarea('description_ru'	, __('admin.commands.description').' (RU)');
					$form->textarea('description_en'	, __('admin.commands.description').' (EN)');
				})->useTab();
			});
			
			$form->tab(__('admin.partners.tab')		, function($form){
				$form->switch('public_partners'		, __('admin.public'));
				
				$form->hasMany('partners', ''		, function($form){
					$form->decimal('partner_sort'				, __('admin.partners.sort'));
					
					//$form->switch('partner_public'				, __('admin.public'));
					
                    $form->radio('partner_public'			        , __('admin.public'))
                            ->options([
                                0		=> __('admin.no'),
                                1		=> __('admin.yes')
                            ])
                            ->rules('required');
                    
					//$form->text('partner_name'					, __('admin.partners.name'))->rules('max:50');
					$form->image('partner_logo'					, __('admin.partners.logo'))->removable();
					
					$form->textarea('partner_description_ua'	, __('admin.partners.description').' (UA)');
					$form->textarea('partner_description_ru'	, __('admin.partners.description').' (RU)');
					$form->textarea('partner_description_en'	, __('admin.partners.description').' (EN)');
				});
				//->useTab();
			});
			
			$form->tab(__('admin.facts.tab')		, function($form){
				$form->switch('public_facts'		, __('admin.public'));
				
				$form->hasMany('facts'				, '', function($form){
					$form->decimal('sort'				, __('admin.facts.sort'))->default(0);
					
					$form->switch('public'				, __('admin.public'));
					
					$form->file('icon'					, __('admin.facts.icon'))->removable();
					
					$form->text('title_ua'				, __('admin.facts.title').' (UA)')->rules('max:250');
					$form->text('title_ru'				, __('admin.facts.title').' (RU)')->rules('max:250');
					$form->text('title_en'				, __('admin.facts.title').' (EN)')->rules('max:250');
					
					$form->summernote('text_ua'			, __('admin.facts.text').' (UA)');
					$form->summernote('text_ru'			, __('admin.facts.text').' (RU)');
					$form->summernote('text_en'			, __('admin.facts.text').' (EN)');
				})->useTab();
			});
		}
		
		if($id == 1){
			$form->tab(__('admin.timeline.tab')		, function($form){
				$form->switch('public_timeline'			, __('admin.timeline.public'));
				
				$form->text('title_timeline_ua'			, __('admin.timeline.title').' (UA)')->rules('max:200');
				$form->text('title_timeline_ru'			, __('admin.timeline.title').' (RU)')->rules('max:200');
				$form->text('title_timeline_en'			, __('admin.timeline.title').' (EN)')->rules('max:200');
				
				$form->text('link_timeline_ua'			, __('admin.timeline.link').' (UA)')->rules('max:200');
				$form->text('link_timeline_ru'			, __('admin.timeline.link').' (RU)')->rules('max:200');
				$form->text('link_timeline_en'			, __('admin.timeline.link').' (EN)')->rules('max:200');
			});
		}
		
		if($id == 10){
			$form->tab(__('admin.contacts.tab')		, function($form) use ($id){
				$form->hasMany('contacts', '', function($form) use ($id){
					$form->decimal('sort'			, __('admin.contacts.sort'))->default(0);
					
					$form->radio('type'				, __('admin.contacts.type'))
							->options([
								'addresse'	=> __('admin.contacts.addresse'),
								'post'		=> __('admin.contacts.post'),
								'phone'		=> __('admin.contacts.phone'),
								'email'		=> __('admin.contacts.email')
							])
							->rules('required');
					
					$form->textarea('value'			, __('admin.contacts.value'))->rules('max:350');
					
					$form->text('label_ua'			, __('admin.contacts.label').' (UA)')->rules('max:200');
					$form->text('label_ru'			, __('admin.contacts.label').' (RU)')->rules('max:200');
					$form->text('label_en'			, __('admin.contacts.label').' (EN)')->rules('max:200');
				})->useTab();
			});
		}
		
		// callback before save
		$form->saving(function (Form $form){
			//$form->title		= trim($form->title);
			//$form->keywords		= trim($form->keywords);
			//$form->description	= trim($form->description);
			
			$form->name			= trim($form->name);
			
			if($form->name && !$form->uri){
				$form->uri = StringHelper::url_title($form->name, 'dash');
			}
		});
		
        return $form;
    }
}
