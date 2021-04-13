<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;

use App\Models\Contents;
use App\Models\Slides;
use App\Models\Commands;
use App\Models\Docs;
use App\Models\Gallery;
use App\Models\Indicators;
use App\Models\Advantages;
use App\Models\Partners;
use App\Models\Vacancies;
use App\Models\Nav;
use App\Models\Facts;
use App\Models\Contacts;

class Page extends Model{
	
    protected $table = 'pages';
    
    public $timestamps = false;
    
    protected $fillable = [
		'parent_id', 
		'name', 'uri', 
		'title_ua', 'keywords_ua', 'description_ua', 
		'title_ru', 'keywords_ru', 'description_ru', 
		'title_en', 'keywords_en', 'description_en', 
		'text_ua', 'text_ru', 'text_en', 
		'image', 
		'advantages_label_ua', 'advantages_label_ru', 'advantages_label_en',
		'public_indicators',
		'public_advantages',
		'public_partners',
		'public_facts',
		'public_commands',
		'header',
		'public_timeline', 
		'title_timeline_ua', 'title_timeline_ru', 'title_timeline_en',
		'link_timeline_ua', 'link_timeline_ru', 'link_timeline_en',
		'public_cetegory',
		'about_last_public','about_last_image',
		'about_last_title_ua','about_last_title_ru','about_last_title_en',
		'about_last_btn_ua','about_last_btn_ru','about_last_btn_en',
		'about_last_btn_url',
		'liveshow',
        'video_ua',
        'video_ru',
        'video_en',

        'video_ua_youtube',
        'video_ru_youtube',
        'video_en_youtube',
        'video_view'
	];
    
	public function contents(){
		return $this->hasMany(Contents::class, 'page_id')->orderBy('sort', 'asc');
	}
	
	public function slides(){
		return $this->hasMany(Slides::class, 'page_id')->orderBy('sort', 'asc');
	}
	
	public function commands(){
		return $this->hasMany(Commands::class, 'page_id')->orderBy('sort', 'asc');
	}
	
	public function docs(){
		return $this->hasMany(Docs::class, 'page_id');
	}
	
	public function gallery(){
		return $this->hasMany(Gallery::class, 'page_id');
	}
	
	public function indicators(){
		return $this->hasMany(Indicators::class, 'page_id')->orderBy('sort', 'asc');
	}
	
	public function advantages(){
		return $this->hasMany(Advantages::class, 'page_id')->orderBy('sort', 'asc');
	}
	
	public function partners(){
		return $this->hasMany(Partners::class, 'page_id')->orderBy('partner_sort', 'asc');
	}
	
	public function vacancies(){
		return $this->hasMany(Vacancies::class, 'page_id')->orderBy('created_at', 'desc');
	}
	
	public function nav(){
		return $this->hasMany(Nav::class, 'page_id')->orderBy('sort', 'asc');
	}
	
	public function facts(){
		return $this->hasMany(Facts::class, 'page_id')->orderBy('sort', 'asc');
	}
	
	public function contacts(){
		return $this->hasMany(Contacts::class, 'page_id')->orderBy('sort', 'asc');
	}
	
	//
    
    function getPageData($lang){
        return array(
            'title'         => $tmp->{'title_'.$lang} ? $tmp->{'title_'.$lang} : $tmp->name,
            'uri'           => $this->uri,
            'keywords'      => $tmp->{'keywords_'.$lang},
            'description'   => $tmp->{'description_'.$lang},
            'content'       => $tmp->{'text_'.$lang}
        );
    }
    
    static function getPage($lang, $uri, $empty = false){
        $tmp = DB::table('pages')
                    ->where('uri', $uri)
                    ->first();
        
        if($tmp){
            return array(
                'title'         => $tmp->{'title_'.$lang} ? $tmp->{'title_'.$lang} : $tmp->name,
                'keywords'      => $tmp->{'keywords_'.$lang},
                'description'   => $tmp->{'description_'.$lang},
                'content'       => $tmp->{'text_'.$lang}
            );
        }
        
        if(!$tmp && $empty){
            return array(
                'title'         => '',
                'keywords'      => '',
                'description'   => '',
                'content'       => ''
            );
        }
    }

    public function videoHtml($src){
    	return '<video autoplay muted loop width="100%" poster="https://i.pinimg.com/750x/d0/95/80/d09580da63652c87075e826390a348ad.jpg" style="width: 100%;
														 height: 400px;
														 object-fit: cover;">
			            		<source src="'.$src.'">
			            			Тег video не поддерживается вашим браузером. 
			            	</video>';
    }

    public function YoutubeSrc($url){
    	 $shortUrlRegex = '/youtu.be\/([a-zA-Z0-9_-]+)\??/i';
	     $longUrlRegex = '/youtube.com\/((?:embed)|(?:watch))((?:\?v\=)|(?:\/))([a-zA-Z0-9_-]+)/i';

	    if (preg_match($longUrlRegex, $url, $matches)) {
	        $youtube_id = $matches[count($matches) - 1];
	    }

	    if (preg_match($shortUrlRegex, $url, $matches)) {
	        $youtube_id = $matches[count($matches) - 1];
	    }
	    return 'https://www.youtube.com/embed/' . $youtube_id ;
    }

    public function YoutubeIframe($video){
    	return '<iframe width="100%" height="400px" src="'.$this->YoutubeSrc($video).'" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>';
    }

    public function switchVideoUa($id, $view){
    	switch ($view) {
    		//dd(Page::where('id', '=', $id)->get()[0]->video_en);
			case 'local':
				if (Page::where('id', '=', $id)->get()[0]->video_ua != null) {
					$video = $this->videoHtml(asset(Page::where('id', '=', $id)->get()[0]->video_ua));
				}else $video = null;
			    
				break;

			case 'youtube':;
				if (Page::where('id', '=', $id)->get()[0]->video_ua_youtube != null) {
					$video = $this->YoutubeIframe(Page::where('id', '=', $id)->get()[0]->video_ua_youtube);
				} else $video = null;
				break;
						
			default:
				$video = null;
				break;
		}
    	return $video;
    }

    public function switchVideoRu($id, $view){
    	switch (Page::where('id', '=', $id)->get()[0]->video_view) {
			case 'local':
			    if (Page::where('id', '=', $id)->get()[0]->video_ru != null) {
					$video = $this->videoHtml(asset(Page::where('id', '=', $id)->get()[0]->video_ru));
				}else $video = null;
				break;

			case 'youtube':
				if (Page::where('id', '=', $id)->get()[0]->video_ru_youtube != null) {
					$video = $this->YoutubeIframe(Page::where('id', '=', $id)->get()[0]->video_ru_youtube);
				} else $video = null;
				break;
						
			default:
				$video = null;
				break;
		}
    	return $video;
    }

    public function switchVideoEN($id, $view){
    	switch (Page::where('id', '=', $id)->get()[0]->video_view) {
			case 'local':	
			    if (Page::where('id', '=', $id)->get()[0]->video_en != null) {
					$video = $this->videoHtml(asset(Page::where('id', '=', $id)->get()[0]->video_en));
				}else $video = null;
				break;

			case 'youtube':
				$video = Page::where('id', '=', $id)->get()[0]->video_en_youtube;

				if (Page::where('id', '=', $id)->get()[0]->video_en_youtube != null) {
					$video = $this->YoutubeIframe(Page::where('id', '=', $id)->get()[0]->video_en_youtube);
				} else $video = null;
				break;
						
			default:
				$video = null;
				break;
		}
    	return $video;
    }

    public function video($id, $lang){
    	$view = Page::where('id', '=', $id)->get()[0]->video_view;

    	switch ($lang) {
				case 'ua':	
					$video = $this->switchVideoUa($id, $view);
					break;
				case 'ru': 
					$video = $this->switchVideoRU($id, $view);
					break;
				case 'en':
					$video = $this->switchVideoEN($id, $view);
					break;
				default:
					$video = null;
					break;
			}
		return $video;
    }
}
