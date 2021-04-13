@extends('layout')

@section('content')
	<main>
	@if(count($slides))
		<section class="main_section">
			<a href="" rel="nofollow" role="botton" class="arrow arrow_left">
				<img src="{{asset('/img/arrow_left.png')}}" alt="">
			</a>
			<div class="slider">
			@foreach($slides as $item)
				@if($item->image)
				<div class="slide">
					<img src="{{asset('storage/'.$item->image)}}" alt="{{$item->alt}}">
                    
                    @if($item->title || $item->description)
                    <div class="slide-wrap">
                        @if($item->title)
                        <div class="slide-title">{{$item->title}}</div>
                        @endif
                        @if($item->description)
                        <div class="slide-sub-title">{!!(str_replace("\n", "<br>", $item->description))!!}</div>
                        @endif
                    </div>
                    @endif
				</div>
				@endif
			@endforeach
			</div>
			<a href="" rel="nofollow" role="botton" class="arrow arrow_right">
				<img src="{{asset('/img/arrow_right.png')}}" alt="">
			</a>
		</section>
	@endif
		
	@if((isset($contents['small']) && $contents['small']->content) || $nav)
		<section class="neptune_section">
			<div class="container">
			@if(isset($contents['small']) && $contents['small']->content)
				<div class="left_side">
					{!!$contents['small']->content!!}
				</div>
			@endif
			
			@if($nav)
				<div class="right_side">
				@foreach($nav as $item)
					@if(isset($item[1]))
					<div class="column">
						@foreach($item as $link)
						<a href="{{url(($lang != $primary_lang ? $lang.'/' : '').$link->url)}}">{{$link->label}}<img src="/img/arrow_right2.png" alt="{{$link->label}}"></a>
						@endforeach
					</div>
					@else
					<a href="{{url(($lang != $primary_lang ? $lang.'/' : '').$link->url)}}" class="big_link">{{$link->label}}<img src="{{asset('/img/arrow_right2.png')}}" alt="{{$link->label}}"></a>
					@endif
				@endforeach
				</div>
			@endif
			</div>
		</section>
	@endif
		
	@if(isset($contents['info']))
		<section class="building_area">
			<div class="container">
				<div class="left_area">
					{!!$contents['info']->content!!}
				</div>
				
			@if($contents['info']->image)
				<div class="right_area">
					<img src="{{asset('storage/'.$contents['info']->image)}}" alt="{{$contents['info']->description}}">
				</div>
			@endif
			</div>
		</section>
	@endif
		
	@if($timeline['link'] && $timeline['link'] != '#')
		<section class="building_steps">
			<div class="container">
			@if($timeline['title'])
				<h2>{{$timeline['title']}}</h2>
			@endif
				
				<div id="timeline-embed" data-src="{{$timeline['link']}}" style="width: 100%; height: 600px"></div>
			</div>
		</section>
	@endif
		
	@if(count($gallery))
		<section class="online_translation" id="online_translation">
			<div class="container">
				<h2>{{trans('site.main.online')}}</h2>
				
				<ul>
				@foreach($gallery as $i => $item)
					<!-- -->
					<li>
						<a href="" rel="nofollow" role="button" class="modal_link" data-src="{{$item->youtube}}">
							<span class="live">LIVE</span>
							
							<img src="{{asset('storage/'.$item->image)}}" alt="{{$item->alt}}" class="poster">
							
							<span class="play_btn">
								<img src="{{asset('/img/triangle_white.svg')}}" alt="">
							</span>
						</a>
					</li>
					<!-- -->
				@endforeach
				</ul>
			</div>
		</section>
	@endif
		
	@if(count($news))
		<section class="news_section">
			<div class="container">
				<div class="top_cont">
					<h2>{{trans('site.main.news.h2')}}</h2>
					<a href="{{($lang != $primary_lang ? $lang.'/' : '')}}/news">{{trans('site.main.news.more')}}</a>
				</div>
				
				<ul class="news_list">
				@foreach($news as $item)
					<!-- -->
					<li>
						<a href="{{($lang != $primary_lang ? $lang.'/' : '')}}/news/{{$item->uri}}">
							@if($item->type == 'video' && $item->duration)
							<span class="video_time">{{$item->duration}}</span>
							@endif
							
							<div class="photo_cont">
								<img src="{{$item->image}}" alt="{{$item->name}}" class="news_preview">
								
							@if($item->type == 'video')
								<span class="play_btn">
									<img src="{{asset('/img/triangle_white.svg')}}" alt="">
								</span>
							@endif
							</div>
							
							<span class="rubrika rubrika_{{$item->type}}">{{__('admin.articles.type_'.$item->type)}}</span>
							
							<p class="news_title">{{$item->name}}</p>
							<p class="date">{{$item->day}} {{$item->month}}, {{$item->year}}</p>
						</a>
					</li>
					<!-- -->
				@endforeach
				</ul>
				
				<a href="{{($lang != $primary_lang ? $lang.'/' : '')}}/news" class="more_news orange_link">{{trans('site.main.news.more')}}</a>
			</div>
		</section>
	@endif
		
	@if(count($docs))
		<section class="documentation_section">
			<div class="container">
				<h2>{{trans('site.main.docs')}}</h2>
				
			@if(isset($contents['docs_info']) && $contents['docs_info']->content)
				<span class="title_text">{!!strip_tags($contents['docs_info']->content)!!}</span>
			@endif
				
				<ul class="docs_list">
				@foreach($docs as $item)
					<!-- -->
					<li>
						<a href="{{asset('storage/'.$item->file)}}">
							<i>
								<svg xmlns="http://www.w3.org/2000/svg" width="32" height="40" viewBox="0 0 32 40"><g><g><g><path d="M24.211 20.772c.023.04.023.056.017.066-.065.114-.361.294-.875.294-.144 0-.297-.015-.454-.044-.642-.12-1.39-.563-2.283-1.354 2.42.116 3.399.7 3.595 1.038zm-7.195-2.47c-.828.079-2.088.243-3.494.576a49.156 49.156 0 0 0 1.472-3.215c.146.183.296.37.448.563.509.642 1.08 1.362 1.673 2.066zm-2.778-9.94c.113-.346.253-.503.341-.532l.006-.002c.084.095.443.637.282 2.852-.028.386-.122.874-.28 1.454-.52-1.334-.662-2.816-.35-3.772zM7.817 24.716c-.243.16-.493.254-.67.254a.301.301 0 0 1-.068-.007c-.03-.155.08-.826 1.166-1.887.51-.5 1.156-.954 1.92-1.357-.964 1.551-1.832 2.66-2.348 2.997zm11.25-6.547c-.836-.932-1.662-1.973-2.406-2.91-.35-.443-.686-.865-1-1.249l-.023-.028c.46-1.309.722-2.38.781-3.184.149-2.05-.08-3.37-.7-4.035a1.54 1.54 0 0 0-1.636-.408c-.423.141-.996.521-1.324 1.525-.488 1.496-.251 4.145 1.136 6.336-.619 1.622-1.482 3.485-2.435 5.259-1.816.636-3.263 1.473-4.301 2.488-1.357 1.325-1.909 2.64-1.514 3.606.243.6.805.957 1.503.957.487 0 1.012-.176 1.521-.507 1.285-.84 2.963-3.639 3.863-5.26 1.862-.582 3.7-.82 4.634-.91.423-.041.842-.073 1.246-.096 1.635 1.727 2.973 2.637 4.205 2.864.248.047.496.07.738.07 1.004 0 1.834-.4 2.22-1.071.293-.507.287-1.098-.016-1.623-.683-1.183-2.746-1.835-5.809-1.835-.22 0-.448.004-.683.01z"/></g><g><path d="M2.236 37.754V2.236h17.888v6.706c0 1.235 1 2.235 2.236 2.235h6.708v26.577zM22.36 3.161l5.783 5.783H22.36zm0-3.161H2.236C1.002 0 0 1 0 2.236v35.528A2.237 2.237 0 0 0 2.236 40h26.832c1.236 0 2.236-1 2.236-2.236V8.944z"/></g><g><path d="M11 30.45a.687.687 0 0 1-.364.24c-.172.054-.391.081-.653.081h-.67v-1.528h.67c.604 0 .85.12.947.219.13.139.192.316.192.541a.734.734 0 0 1-.123.446zm.961-1.847a1.63 1.63 0 0 0-.65-.347c-.246-.07-.598-.106-1.047-.106H8.776c-.283 0-.497.065-.635.194-.14.13-.211.342-.211.628v4.414c0 .257.064.457.189.596a.648.648 0 0 0 .504.215.646.646 0 0 0 .496-.216c.128-.14.194-.343.194-.602v-1.51h.951c.735 0 1.3-.16 1.677-.477.384-.321.579-.796.579-1.411 0-.287-.047-.55-.141-.78a1.577 1.577 0 0 0-.418-.598z"/></g><g><path d="M16.444 32.723a.835.835 0 0 1-.269.155 1.305 1.305 0 0 1-.313.072c-.105.01-.254.015-.442.015h-.768V29.29h.654c.343 0 .637.038.874.111.222.068.413.233.568.49.16.264.24.672.24 1.215 0 .767-.182 1.31-.544 1.617zm1.01-4.026a1.979 1.979 0 0 0-.811-.434 4.218 4.218 0 0 0-1.04-.113h-1.511c-.28 0-.489.067-.622.2-.132.134-.2.343-.2.622v4.199c0 .197.017.355.053.481.04.144.127.257.259.334.124.076.3.112.533.112h1.512c.267 0 .512-.017.727-.052.218-.035.426-.097.616-.184.192-.088.37-.206.533-.352.203-.186.372-.4.503-.639.13-.237.227-.505.288-.798a4.69 4.69 0 0 0 .092-.964c0-1.07-.314-1.882-.932-2.412z"/></g><g><path d="M22.716 28.15h-2.734c-.182 0-.33.027-.45.083a.57.57 0 0 0-.288.284c-.056.122-.084.27-.084.455v4.406c0 .266.065.47.192.607.129.14.298.212.501.212.2 0 .367-.07.498-.21.127-.137.192-.342.192-.609v-1.775h1.799c.201 0 .359-.049.468-.145.113-.1.17-.234.17-.4 0-.164-.056-.3-.167-.4-.108-.098-.266-.148-.471-.148h-1.8v-1.243h2.175c.213 0 .377-.05.487-.152a.528.528 0 0 0 .171-.408c0-.164-.057-.3-.171-.405-.112-.101-.276-.152-.488-.152z"/></g></g></g></svg>
							</i>
							<p>{{$item->description}}</p>
						</a>
					</li>
					<!-- -->
				@endforeach
				</ul>
			</div>
		</section>
	@endif
		
	@if(isset($contents['join']))
		<section class="connect_us_section">
			<div class="container">
				<div class="inside">
				@if($contents['join']->image)
					<div class="left_side">
						<img src="{{asset('storage/'.$contents['join']->image)}}" alt="{{$contents['info']->description}}">
					</div>
				@endif
					
					<div class="right_side">
						{!!$contents['join']->content!!}
						<a href="{{($lang != $primary_lang ? $lang.'/' : '')}}/vacancies">{{trans('site.main.vacancies.more')}}</a>
					</div>
				</div>
			</div>
		</section>
	@endif
		
		@include('components.contact', ['settings' => $settings, 'contacts' => $contacts])
	</main>
@stop
