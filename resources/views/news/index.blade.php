@extends('layout')

@section('content')
	<main>
		<section class="vacancies_main news_main_block">
		@if($page['image'])
			<img src="{{asset('storage/'.$page['image'])}}" alt="{{$page['name']}}" class="back_img">
		@endif
			<div class="overlay"></div>
			
			<div class="container">
				<h1>{{__('site.news.h1')}}</h1>
			</div>
		</section>
		
		<section class="news_content_section">
			<div class="container">
			@if($public_cetegory)
				<div class="news_tab_list__outter">
					<ul class="news_tab_list">
						<li>
							<a href="" rel="nofollow" role="button" class="filter_all active">{{__('site.type_all')}}</a>
						</li>
						<li>
							<a href="" rel="nofollow" role="button" class="video-item">{{__('site.type_video')}}</a>
						</li>
						<li>
							<a href="" rel="nofollow" role="button" class="about_us-item">{{__('site.type_about_us')}}</a>
						</li>
						<li>
							<a href="" rel="nofollow" role="button" class="articles-item">{{__('site.type_articles')}}</a>
						</li>
					</ul>
				</div>
			@endif
				
				<ul class="news_list">
			@if(count($articles))
				@foreach($articles as $item)
					<!-- -->
					<li class="{{$item->type}}-item active">
						<a href="{{url('/news/'.$item->uri)}}">
							@if($item->type == 'video' && $item->duration)
							<span class="video_time">{{$item->duration}}</span>
							@endif
							
							<div class="photo_cont">
								<img src="{{$item->image}}" alt="{{$item->name}}" class="news_preview">
								}
								
							@if($item->type == 'video')
								<span class="play_btn">
									<img src="{{asset('/img/triangle_white.svg')}}" alt="">
								</span>
							@endif
							</div>
							
							<span class="rubrika rubrika_{{$item->type}}">{{__('site.type_'.$item->type)}}</span>
							
							<p class="news_title">{{$item->name}}</p>
							<p class="date">{{$item->day}} {{$item->month}}, {{$item->year}}</p>
						</a>
					</li>
					<!-- -->
				@endforeach
			@endif
				</ul>
				
				{!!$pagination!!}
			</div>
		</section>
		
		@include('components.contact', ['settings' => $settings, 'contacts' => $contacts])
	</main>
@stop
