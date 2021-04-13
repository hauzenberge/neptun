<?php

namespace App\Http\Controllers;

use App\Models\Page;
use App\Models\ArticlesCategory;
use App\Models\Articles;

use App\Helpers\MyPaginator;
use App\Helpers\MyBreadcrumbs;
use App\Helpers\StringHelper;
use App\Helpers\ImageHelper;

use Illuminate\Http\Request;

use Mail;
use DB;

use Illuminate\Support\Facades\View;

class NewsController extends MyController {
	
    public function __construct(){
        parent::__construct();
    }
    
    public function index(Request $request){
		$number = (int)$request->route('page');
		
		if($number < 1){
			$number = 1;
		}
		
        $page = Page::where('uri', 'news')
					->select(
						'pages.*',
						DB::raw('pages.title_'.$this->_current_lang.' as title'),
						DB::raw('pages.keywords_'.$this->_current_lang.' as keywords'),
						DB::raw('pages.description_'.$this->_current_lang.' as description'),
						DB::raw('pages.text_'.$this->_current_lang.' as text')
					)
					->first();
        
        $limit = (int)config('count_articles');
        
        if(!$limit){
			$limit = 10;
		}
		
		//$limit = 10;
		
		$articles	= [];
		$count		= Articles::where('articles.public','1')->count();
		$pages		= 0;
		$start		= 0;
		
		$paginator = new MyPaginator($count, $limit, $number);
		$paginator->setCountShowPages(3);
		$paginator->queryStringResult(false);
		$paginator->setPath('/news');
		$paginator->calcNumPages();
		
		$pages = $paginator->getNumPages();
		
		if($count){
			if($number > $pages){
				 return $this->show_404();
			}
			
			if($number > 1){
				$start = ($number * $limit) - 1;
			}
			
			$articles	= Articles::getItems($this->_current_lang, $start, $limit);
			//dd(Articles::all());
			//dd(Articles::getItems($this->_current_lang, $start, $limit));
		}
		
		//var_dump($paginator->render());
		//exit;

		
        $data = [
			'page' => array(
				'id'         	=> $page->id,
				'name'         	=> $page->name,
				'title'         => $page->title,
				'keywords'      => $page->keywords,
				'description'   => $page->description,
				'image'  	 	=> $page->image,
				
				'url'           => url('/'.($this->_current_lang != $this->_primary_lang ? $this->_current_lang : '').'/news'),
				'uri'           => 'news',
				
				'og_image'   	=> '',
			),
			'wrapperClass'		=> '',
			//'category'		=> ArticlesCategory::orderBy('name', 'asc')->get(),
			'articles'			=> $articles,
			'count'				=> $count,
			'limit'				=> $limit,
			'pagination'		=> $paginator->render(),
			'public_cetegory' 	=> (int)$page->public_cetegory
		];
		
        return view(
            'news/index',
            $data
        );
    }
    
    public function once(Request $request){
		$uri = $request->route('uri');
		
        $page = Articles::getOnce($this->_current_lang, $uri);

        if(!$page){
            return $this->show_404();
        }
        
        return view(
            'news/once',
            [
				'page' => array(
					'title'         => $page->title ? $page->title : $page->name,
					'keywords'      => $page->keywords,
					'description'   => $page->description,
					'content'       => '',
					
					'url'           => url('/'.($this->_current_lang != $this->_primary_lang ? $this->_current_lang : '').'/news/'.$uri),
					'uri'           => 'news/'.$uri,
					
					'og_image'   	=> $page->main_image ? $page->main_image : $page->image,
				),
				'wrapperClass'		=> '',
				
				'active'			=> url(($this->_current_lang != $this->_primary_lang ? $this->_current_lang.'/' : '').'news'),
				'data'       		=> $page,
				'other'				=> Articles::getItems($this->_current_lang, 0, 3, $page->id, $page->type)
			]
        );
    }
}
