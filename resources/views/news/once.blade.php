@extends('layout')

@section('content')
	<div class="progress-bar">
		<span class="progress"></span>
	</div>
	
	<main>
		<section class="news_one_section">
			<div class="container">
				<span class="rubrik_title rubrika_{{$data->type}}">{{__('site.type_'.$data->type)}}</span>
				
				<h1 class="name_news">{{$data->name}}</h1>
				
				<p class="date_published">{{$data->day}} {{$data->month}}, {{$data->year}}</p>
				
			@if($data->main_image)
				<img src="{{asset($data->main_image)}}" alt="{{$data->name}}" class="main_photo">
			@endif
				
				{!!$data->text!!}
				
				@if($data->interview)
				<div class="coordinate_block">
					<div class="left_area">
					@if($data->respondent_photo)
						<figure>
							<img src="{{asset($data->respondent_photo)}}" alt="{{$data->respondent_name}}">
						</figure>
					@endif
						
						<div class="text_bottom">
						@if($data->respondent_name)
							<p class="name">{{$data->respondent_name}}</p>
						@endif
						@if($data->respondent_position)
							<p class="role">{{$data->respondent_position}}</p>
						@endif
						</div>
					</div>
					
					<div class="right_area">
					@foreach($data->interview as $item)
						<!-- -->
						@if($item->question || $item->answer || $item->image)
						<div class="text_block">
						@if($item->question)
							<p><b>{{$item->question}}</b></p>
						@endif
							{!!$item->answer!!}
						
						@if($item->image)
							<img src="{{asset($item->image)}}" alt="">
						@endif
						</div>
						@endif
						@if($item->video)
						<iframe width="560" height="315" src="{{$item->video}}" frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen class="secondary_video"></iframe>
						@endif
						<!-- -->
					@endforeach
					</div>
				</div>
				@endif
			</div>
			
			<div class="share_block">
				<a href="#" rel="nofollow" id="fb_share">{{__('site.news.share')}} <i>
					<svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" viewBox="0 0 30 30"><g><g clip-path="url(#clip-1686551E-D113-9A04-88FD-226DCBA0C658)"><path fill="#231f20" d="M14.775 0c8.16 0 14.775 6.616 14.775 14.775 0 8.16-6.615 14.774-14.775 14.774S0 22.934 0 14.775C0 6.616 6.616 0 14.775 0zm4.244 11.491h-2.89V9.594c0-.71.47-.877.802-.877h2.04V5.588l-2.808-.01c-3.118 0-3.828 2.332-3.828 3.826v2.087h-1.803v3.222h1.803v9.124h3.794v-9.124h2.557z"/></g></g></svg></i>
				</a>
			</div>
		</section>
		
		@if(count($other))
		<section class="news_section">
			<div class="container">
				<div class="top_cont">
					<h2>{{__('site.news.related-news')}}</h2>
				</div>
				
				<ul class="news_list">
				@foreach($other as $item)
					<!-- -->
					<li>
						<a href="/news/{{$item->uri}}">
							@if($item->type == 'video' && $item->duration)
							<span class="video_time">{{$item->duration}}</span>
							@endif
							
							<div class="photo_cont">
								<img src="{{asset($item->image)}}" alt="{{$item->name}}" class="news_preview">
								
							@if($item->type == 'video')
								<span class="play_btn">
									<img src="{{asset(/img/triangle_white.svg)}}" alt="">
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
				</ul>
			</div>
		</section>
		@endif
	</main>
@stop
