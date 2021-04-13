@extends('layout')

@section('content')
	<main>
		<section class="gallery_section">
			<div class="container">
				<div class="top_cont">
					<h2>{{trans('site.gallery.top')}}</h2>
					
					<ul class="tab_list">
						<li>
							<a href="" rel="nofollow" role="button" class="active filter_all">{{trans('site.gallery.tab.all')}}</a>
						</li>
						<li>
							<a href="" rel="nofollow" role="button" class="gallery_video">{{trans('site.gallery.tab.video')}}</a>
						</li>
						<li>
							<a href="" rel="nofollow" role="button" class="gallery_images">{{trans('site.gallery.tab.images')}}</a>
						</li>
					</ul>
				</div>
				
				<div class="gallery_content">
					<ul class="gallery_list">
				@if(count($gallery))
					@foreach($gallery as $i => $item)
						<!-- -->
						@if($item->type == 'video' || $item->type == 'live')
						<li class="gallery_video active">
							<a href="" rel="nofollow" role="button" class="modal_link gallery_video" data-src="{{$item->youtube}}">
							@if($item->duration)
								<span class="video_time">{{$item->duration}}</span>
							@endif
								<img src="{{asset('storage/'.$item->image)}}" alt="{{$item->alt}}" class="poster">
								
								<div class="overlay"></div>
								
								<span class="play_btn">
									<img src="{{asset('/img/triangle_white.svg')}}" alt="">
								</span>
							</a>
                            @if($item->alt)
                            <div class="gallery_title">{{$item->alt}}</div>
                            @endif
						</li>
						@else
						<li class="gallery_images active">
							<a href="{{asset('storage/'.$item->image)}}" rel="gallery1" class="gallery_photo">
								<img src="{{asset('storage/'.$item->image)}}" alt="{{$item->alt}}">
							</a>
                            @if($item->alt)
                            <div class="gallery_title">{{$item->alt}}</div>
                            @endif
						</li>
						@endif
						<!-- -->
					@endforeach
				@endif
					</ul>
				</div>
			</div>
		</section>
		
		@include('components.contact', ['settings' => $settings, 'contacts' => $contacts])
	</main>
@stop
