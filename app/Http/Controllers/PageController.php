<?php

namespace App\Http\Controllers;

use App\Models\Page;
use App\Models\Articles;
use App\Models\ShipCalls;

use App\Helpers\MyPaginator;
use App\Helpers\MyBreadcrumbs;
use App\Helpers\StringHelper;
use App\Helpers\ImageHelper;

use Illuminate\Http\Request;

use Mail;
use DB;

use Illuminate\Support\Facades\View;

class PageController extends MyController {
	
    public function __construct(){
        parent::__construct();
    }
    
    public function index(){
        $page = Page::where('uri', 'index')
					->select(
						'pages.*',
						DB::raw('pages.title_'.$this->_current_lang.' as title'),
						DB::raw('pages.keywords_'.$this->_current_lang.' as keywords'),
						DB::raw('pages.description_'.$this->_current_lang.' as description'),
						DB::raw('pages.text_'.$this->_current_lang.' as text'),
						DB::raw('pages.advantages_label_'.$this->_current_lang.' as advantages_label'),
						
						DB::raw('pages.title_timeline_'.$this->_current_lang.' as title_timeline'),
						DB::raw('pages.link_timeline_'.$this->_current_lang.' as link_timeline')
					)
					->first();
        
        $contents = ['other' => []];
        
        $tmp = $page->contents()
					->select(
						'contents.*',
						DB::raw('contents.description_'.$this->_current_lang.' as description'),
						DB::raw('contents.content_'.$this->_current_lang.' as content')
					)
					->where('public','1')
					->orderBy('sort', 'asc')
					->get();
        
        if(count($tmp)){
			$tmp = $tmp->toArray();
			
			foreach($tmp as $item){
				$item = (object)$item;
				
				if(!$item->field){
					$contents['other'][] = $item;
				}else{
					$contents[$item->field] = $item;
				}
			}
		}
		
		// nav
		
		$nav = [];
		
		$tmp = $page->nav()
					->select(
						'page_nav.*',
						DB::raw('page_nav.label_'.$this->_current_lang.' as label')
					)
					->get();
		
		if(count($tmp)){
			$tmp = $tmp->toArray();
			
			foreach($tmp as $item){
				$item = (object)$item;
				
				if(!$item->label){
					continue;
				}
				
				$nav[] = $item;
			}
			
			$nav = array_chunk($nav, 2);
		}
		
		$tmp = null;
		
		// docs
		
		$docs= [];
		
		$tmp = Page::where('uri', 'documents')
					->select('id')
					->first()
					->docs()
					->select(
						'docs.*',
						DB::raw('docs.description_'.$this->_current_lang.' as description')
					)
					->where('docs.public_doc', 'on')
					->get();
		
		if(count($tmp)){
			$tmp = $tmp->toArray();
			
			foreach($tmp as $item){
				$item = (object)$item;
				
				if(!$item->description){
					continue;
				}
				
				$docs[] = $item;
			}
		}
		
		$tmp = null;
		
		//
		
		//var_dump($nav);exit;
        
        $page->public_timeline	= (int)$page->public_timeline;
        $page->liveshow			= (int)$page->liveshow;
        
        $gallery	= [];
        
        if($page->liveshow){
			$gallery = Page::where('uri', 'gallery')
							->select('id')
							->first()
							->gallery()
							->where('type', 'live')
							->select(
								'gallery.*',
								DB::raw('gallery.alt_'.$this->_current_lang.' as alt')
							)
							->where('gallery.public','1')
							->get();
		}
        
        $data		= [
			'page' => array(
				'id'   			=> $page->id,
				'title'         => $page->title ? $page->title : $page->name,
				'keywords'      => $page->keywords,
				'description'   => $page->description,
				
				'url'           => url(($this->_current_lang != $this->_primary_lang ? $this->_current_lang.'/' : '')),
				'uri'           => 'index',
				
				'og_image'   	=> '',
			),
			'wrapperClass'		=> '',
			'slides'			=> $page->slides()
										->select(
											DB::raw('page_slides.image_'.$this->_current_lang.' as image'),
											DB::raw('page_slides.alt_'.$this->_current_lang.' as alt'),
                                            DB::raw('page_slides.title_'.$this->_current_lang.' as title'),
                                            DB::raw('page_slides.description_'.$this->_current_lang.' as description')
										)
										->where('page_slides.public','1')
										->get(),
			'nav'				=> $nav,
			'contents'			=> $contents,
			'docs'				=> $docs,
			'gallery'			=> $gallery,
			'news'				=> Articles::getItems($this->_current_lang, 0, 3),
			'timeline'			=> [
				'title'	=> $page->title_timeline,
				'link'	=> $page->public_timeline ? $page->link_timeline : ''
			]
		];
		
        return view(
            'main',
            $data
        );
    }
    
    public function once(Request $request){
		$uri = $request->route('uri');
		
        $page = Page::where('uri', $uri)
					->select(
						'pages.*',
						DB::raw('pages.title_'.$this->_current_lang.' as title'),
						DB::raw('pages.keywords_'.$this->_current_lang.' as keywords'),
						DB::raw('pages.description_'.$this->_current_lang.' as description'),
						DB::raw('pages.text_'.$this->_current_lang.' as text'),
						DB::raw('pages.advantages_label_'.$this->_current_lang.' as advantages_label'),
						
						DB::raw('pages.about_last_title_'.$this->_current_lang.' as about_last_title'),
						DB::raw('pages.about_last_btn_'.$this->_current_lang.' as about_last_btn'),
                        
                        DB::raw('pages.video_'.$this->_current_lang.' as video')
					)
					->first();

        if(!$page){
            return $this->show_404();
        }
        
        $disable = (int)$request->get('disable') > 0;
        
        if($disable){
			$page->video = "";
		}
        
        $page->time = strtotime($page->created_at);
        
        $view = 'page';
        $data = [
			'page' => array(
				'id'   			=> $page->id,
				'title'         => $page->title ? $page->title : $page->name,
				'keywords'      => $page->keywords,
				'description'   => $page->description,
				'name'         	=> $page->name,
				'content'       => $page->text,
				'image'       	=> $page->image,
				'video'       	=> $page->video,
                
				'url'           => url(($this->_current_lang != $this->_primary_lang ? $this->_current_lang.'/' : '').$uri),
				'uri'           => $uri,
				
				'og_image'   	=> '',
				
				'created'		=> [
					'day'   		=> date('j', $page->time),
					'month'   		=> trans('site.months.'.date('F', $page->time)),
					'year'   		=> date('Y', $page->time)
				],
			),
			'wrapperClass'		=> '',
			'disable' => $disable
		];
		
		//
		
		if($page->id == 6){
			$view = 'gallery';
			
			$data['gallery'] = [];
			
			$page->liveshow	 = (int)$page->liveshow > 0;
			
			$query = $page->gallery()
							->select(
								'gallery.*',
								DB::raw('gallery.alt_'.$this->_current_lang.' as alt')
							)
							->where('gallery.public','1');
			
			if(!$page->liveshow){
				$page->where('gallery.type', 'video');
			}
			
			$data['gallery'] = $query->get();
		}
		
		if($page->id == 8){
			$view = 'docs';
			
			$data['docs'] = [];
			
			$tmp = $page->docs()
						->select(
							'docs.*',
							DB::raw('docs.description_'.$this->_current_lang.' as description')
						)
						->where('docs.public_doc', 'on')
						->get();
			
			if(count($tmp)){
				$tmp = $tmp->toArray();
				
				foreach($tmp as $item){
					$item = (object)$item;
					
					if(!$item->description){
						continue;
					}
					
					$data['docs'][] = $item;
				}
			}
		}
		
		if($page->id == 2){
			$view = 'about';
			
			$page->public_indicators	= (int)$page->public_indicators;
			$page->public_advantages	= (int)$page->public_advantages;
			$page->public_partners		= (int)$page->public_partners;
			$page->public_facts			= (int)$page->public_facts;
			$page->public_commands		= (int)$page->public_commands;
			$page->about_last_public	= (int)$page->about_last_public;
			
			$data['indicators']			= [];
			
			if($page->public_indicators > 0){
				$data['indicators']	= $page->indicators()
											->select(
												'indicators.*',
												DB::raw('indicators.bold_'.$this->_current_lang.' as bold'),
												DB::raw('indicators.description_'.$this->_current_lang.' as description')
											)
											->where('indicators.public','1')
											->get();
			}
			
			$data['advantages_label']	= $page->advantages_label;
			$data['advantages']			= [];
			
			if($page->public_advantages > 0){
				$data['advantages']			= $page->advantages()
													->select(
														'advantages.*',
														DB::raw('advantages.title_'.$this->_current_lang.' as title'),
														DB::raw('advantages.description_'.$this->_current_lang.' as description')
													)
													->where('advantages.public','1')
													->get();
			}
			
			$data['slides']				= $page->slides()
												->select(
													'page_slides.*',
													DB::raw('page_slides.alt_'.$this->_current_lang.' as alt')
												)
												->where('page_slides.public','1')
												->get();
			
			$data['commands']			= [];
			
			if($page->public_commands > 0){
				$data['commands']		= $page->commands()
												->select(
													'commands.*',
													DB::raw('commands.name_'.$this->_current_lang.' as name'),
													DB::raw('commands.position_'.$this->_current_lang.' as position'),
													DB::raw('commands.description_'.$this->_current_lang.' as description')
												)
												->where('commands.public','1')
												->get();
			}
			
			$data['partners']			= [];
			
			if($page->public_partners > 0){
				$data['partners']		= $page->partners()
												->select(
													'partners.*',
													DB::raw('partners.partner_name_'.$this->_current_lang.' as name'),
													DB::raw('partners.partner_description_'.$this->_current_lang.' as description')
												)
												->where('partners.partner_public','1')
												->get();
			}
			
			$data['facts']				= [];
			
			if($page->public_facts > 0){
				$data['facts']			= $page->facts()
												->select(
													'facts.*',
													DB::raw('facts.title_'.$this->_current_lang.' as title'),
													DB::raw('facts.text_'.$this->_current_lang.' as text')
												)
												->where('facts.public','1')
												->get();
			}
			
			$data['about_last']			= [];
			
			if($page->about_last_public > 0){
				$data['about_last']	= [
					'title'		=> $page->about_last_title,
					
					'label'		=> $page->about_last_btn,
					'url'		=> $page->about_last_btn_url,
					
					'image'		=> $page->about_last_image
				];
			}
		}
		
		if($page->id == 10){
			$view = 'contacts';
		}
		
		if($page->id == 9){
			$view = 'vacancies';
			
			$vacancies = $page->vacancies()
								->select(
									'vacancies.*',
									DB::raw('vacancies.name_'.$this->_current_lang.' as name'),
									DB::raw('vacancies.label_'.$this->_current_lang.' as label'),
									DB::raw('vacancies.description_'.$this->_current_lang.' as description'),
									DB::raw('vacancies.characteristics_'.$this->_current_lang.' as characteristics'),
									DB::raw('vacancies.requirements_'.$this->_current_lang.' as requirements')
								)
								->where('vacancies.public','1')
								->get();
			
			$data['vacancies']	= [];
			
			if(count($vacancies)){
				$vacancies = $vacancies->toArray();
				
				foreach($vacancies as $item){
					$item = (object)$item;
					
					if(!$item->name){
						continue;
					}
					
					$characteristics		= trim($item->characteristics);
					$characteristics		= explode("\n", $characteristics);
					$item->characteristics	= [];
					
					if($characteristics){
						foreach($characteristics as $str){
							$str = trim($str);
							
							if($str){
								$item->characteristics[] = $str;
							}
						}
					}
					
					$requirements			= trim($item->requirements);
					$requirements			= explode("\n", $requirements);
					$item->requirements		= [];
					
					if($requirements){
						foreach($requirements as $str){
							$str = trim($str);
							
							if($str){
								$item->requirements[] = $str;
							}
						}
					}
					
					$data['vacancies'][] = $item;
				}
			}
		}
		
        if($page->id == 12){
			$view = 'history';
        }
        
        if($page->id == 13){
			$view = 'ship_calls';
			$video = new Page;
			$data['page']['video'] = $video->video(13, $this->_current_lang);
			
			$data['data'] = ShipCalls::query()->get();
		}
        
		//
		
		$data['contents'] = ['other' => []];
        
        $tmp = $page->contents()
					->select(
						'contents.*',
						DB::raw('contents.description_'.$this->_current_lang.' as description'),
						DB::raw('contents.content_'.$this->_current_lang.' as content')
					)
					->where('contents.public','1')
					->orderBy('sort', 'asc')
					->get();
        
        if(count($tmp)){
			$tmp = $tmp->toArray();
			
			foreach($tmp as $item){
				$item = (object)$item;
				
				if(!$item->field){
					$data['contents']['other'][] = $item;
				}else{
					$data['contents'][$item->field] = $item;
				}
			}
		}
		
		//var_dump($data);exit;
        
        return view($view, $data);
    }
}
