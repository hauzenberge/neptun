<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use App\Models\ArticlesCategory;
use App\Models\ArticlesImages;
use App\Models\ArticlesSlides;
use App\Models\ArticlesInterview;

use DB;

class Articles extends Model{
	
	protected $table = 'articles';
    
    //public $timestamps = false;
    
    protected $fillable = [
		'name_ua', 'name_ru', 'name_en', 
		'uri', 'public', 
		
		'title_ua', 'keywords_ua', 'description_ua', 
		'title_ru', 'keywords_ru', 'description_ru', 
		'title_en', 'keywords_en', 'description_en', 
		
		'image', 'main_image', 
		'youtube', 'duration', 
		
		'text_ua', 'slider_alt_ua', 
		'text_ru', 'slider_alt_ru', 
		'text_en', 'slider_alt_en', 
		
		'type', 'category_id',
		
		'respondent_photo',
		
		'respondent_name_ua', 'respondent_position_ua',
		'respondent_name_ru', 'respondent_position_ru',
		'respondent_name_en', 'respondent_position_en'
	];
	
    public function images(){
		return $this->hasMany(ArticlesImages::class, 'article_id');
    }
    
    public function slides(){
		return $this->hasMany(ArticlesSlides::class, 'article_id');
    }
    
    public function interview(){
		return $this->hasMany(ArticlesInterview::class, 'article_id');
    }
    
    public static function getItems($lang, $start = 0, $limit = 15, $article_id = 0, $type = ''){
		$data = [];
		
		$query = DB::table('articles')
				->select(
					'articles.id', 
					DB::raw('articles.name_'.$lang.' as name'),
					'articles.uri', 
					'articles.image', 
					'articles.created_at', 
					'articles.type',
					'articles.duration'
				)
				->where('articles.public','1')
				//->leftJoin('articles_category', 'articles_category.id', '=', 'articles.category_id')
				->orderBy('articles.created_at', 'desc');
		
		if($article_id){
			$query->whereRaw('articles.id != '.$article_id);
		}
		
		if($type){
			$query->where('articles.type', $type);
		}
		
		if($start){
			$query->skip($start);
		}
		
		if($limit){
			$query->take($limit);
		}
		
		$tmp = $query->get()->toArray();
		
		if($tmp){
			$max_name = 85;
			
			foreach($tmp as $item){
				$item = (object)$item;
				
				if(!$item->name){
					continue;
				}
				
				$item->image		= asset('storage/'.$item->image);
				//$item->main_image	= $item->main_image ? url('storage/'.$item->main_image) : '';
				
				$item->time = strtotime($item->created_at);
				
				$l = mb_strlen($item->name);
				
				if($l > $max_name){
					$item->name = mb_substr($item->name, 0, ($max_name-2)).'..';
				}
				
				$item->day		= date('j', $item->time);
				$item->month	= trans('site.months.'.date('F', $item->time));
				$item->year		= date('Y', $item->time);
				
				$data[] = $item;
			}
		}
		
		$tmp = null;
		
		return $data;
		
		var_dump($data);
		exit;
	}
	
	public static function getOnce($lang, $uri){
		$data = DB::table('articles')
					->where('articles.uri', $uri)
					->select(
						'articles.*',
						DB::raw('articles.name_'.$lang.' as name'),
						DB::raw('articles.title_'.$lang.' as title'),
						DB::raw('articles.keywords_'.$lang.' as keywords'),
						DB::raw('articles.description_'.$lang.' as description'),
						DB::raw('articles.text_'.$lang.' as text'),
						
						DB::raw('articles.slider_alt_'.$lang.' as slider_alt'),
						DB::raw('articles.respondent_name_'.$lang.' as respondent_name'),
						DB::raw('articles.respondent_position_'.$lang.' as respondent_position')
					)
					->where('articles.public','1')
					->first();
		
		if($data){
			//$data = (object)$data->toArray();
			
			$data->image		= url('storage/'.$data->image);
			$data->main_image	= $data->main_image ? url('storage/'.$data->main_image) : '';
			
			$data->respondent_photo	= $data->respondent_photo ? url('storage/'.$data->respondent_photo) : '';
			
			$data->time = strtotime($data->created_at);
							
			$data->day		= date('j', $data->time);
			$data->month	= trans('site.months.'.date('F', $data->time));
			$data->year		= date('Y', $data->time);
			
			$data->interview	= [];
			
			if($data->type != 'video'){
				$data->images	= [];
				
				$tmp = DB::table('articles_images')
							->where('articles_images.article_id', $data->id)
							->orderBy('articles_images.sort', 'asc')
							->select(
								'articles_images.*',
								DB::raw('articles_images.alt_'.$lang.' as alt')
							)
							->get()
							->toArray();
				
				if(count($tmp)){
					foreach($tmp as $item){
						$item = (object)$item;
						$item->img = url('storage/'.$item->img);
						
						$data->images[$item->sort] = $item;
					}
				}
				
				$tmp = null;
				
				$data->slides	= [];
				
				$tmp = DB::table('articles_slides')
							->where('articles_slides.article_id', $data->id)
							->orderBy('articles_slides.slide_sort', 'asc')
							->select(
								'articles_slides.*',
								DB::raw('articles_slides.slide_alt_'.$lang.' as slide_alt')
							)
							->get()
							->toArray();
				
				if(count($tmp)){
					foreach($tmp as $item){
						$item = (object)$item;
						$item->slide_image = url('storage/'.$item->slide_image);
						
						$data->slides[] = (object)$item;
					}
				}
				
				//var_dump($data->slides);
				//exit;
				
				$patterns = [
					'#<p>\[slider\]</p>#i',
					'#<p>\[video\]</p>#i',
					'#<p>\[image:(\d+)\]</p>#i'
				];
				
				$replacements = [
					'[slider]',
					'[video]',
					'[image:\\1]',
				];
				
				$data->text = preg_replace($patterns, $replacements, $data->text);
				
				$patterns = [];
				$replacements = [];
				
				if($data->slides){
					$patterns[]		= '#\[slider\]#i';
					$replacements[] = view('components.slider', ['slides' => $data->slides]);
				}
				
				if($data->youtube){
					$patterns[]		= '#\[video\]#i';
					$replacements[] = view('components.video', ['url' => $data->youtube, 'text' => '']);
				}
				
				if($data->images){
					preg_match_all('/(\[image:(\d+)\])/i', $data->text, $matches);
					
					if($matches){
						foreach($matches[2] as $k){
							$patterns[] = '#\[image:'.$k.'\]#i';
							$replacements[] = view('components.image', ['data' => $data->images[$k]]);
						}
					}
				}
				
				if($patterns){
					$data->text = preg_replace($patterns, $replacements, $data->text);
				}
				
				$patterns = null;
				$replacements = null;
				
				$data->images = null;
				$data->slides = null;
				$data->youtube = null;
				
				$data->text = preg_replace_callback('|(<blockquote>)(.+)(</blockquote>)|iU', function($matches){
					// $matches[2]
					
					return view('components.blockquote', ['str' => $matches[2]]);
				}
				,$data->text);
				
				//
				
				$tmp = DB::table('articles_interview')
							->where('articles_interview.article_id', $data->id)
							//->orderBy('articles_interview.sort', 'asc')
							->select(
								'articles_interview.*',
								DB::raw('articles_interview.question_'.$lang.' as question'),
								DB::raw('articles_interview.answer_'.$lang.' as answer')
							)
							->get()
							->toArray();
				
				if(count($tmp)){
					foreach($tmp as $item){
						$item = (object)$item;
						
						if($item->image){
							$item->image = url('storage/'.$item->image);
						}
						
						$data->interview[] = $item;
					}
				}
				
				$tmp = null;
			}else{
				$data->text = view('components.video', ['url' => $data->youtube, 'text' => $data->text]);
			}
		}
		
		return $data;
		
		echo"<pre>";
		print_r($data);
		exit;
	}
}
