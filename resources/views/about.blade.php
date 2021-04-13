@extends('layout')

@section('content')
	<main>
	@if($page['content'] || $page['image'])
		<section class="about_us_main">
			<div class="container">
			@if($page['content'])
				<div class="left_side">
					{!!$page['content']!!}
				</div>
			@endif
            
            @if($page['video'] && !$page['image'])
                <video class="mainVideo" reload="auto" autoplay="true" loop="true" muted="muted">
                    <source src="{{asset($page['video'])}}">
                </video>
            @endif
            
			@if($page['image'] && !$page['video'])
				<img src="{{asset('storage/'.$page['image'])}}" alt="" class="back_img">
            @endif
			</div>
		</section>
	@endif
		
	@if(count($indicators))
		<section class="statistic_wrap">
			<div class="container">
				<ul>
				@foreach($indicators as $item)
					<!-- -->
					<li>
						<span class="digit">{{$item->digit}}</span>
						<p class="bold_p">{{$item->bold}}</p>
						<p class="reg_p">{{$item->description}}</p>
					</li>
					<!-- -->
				@endforeach
				</ul>
			</div>
		</section>
	@endif
		
	@if(isset($contents['background']))
		<section class="terminal_info">
		@if($contents['background']->image)
			<img src="{{asset('storage/'.$contents['background']->image)}}" alt="" class="back_img">
		@endif
			
			<div class="overlay"></div>
			
			<div class="container">
				{!!$contents['background']->content!!}
			</div>
		</section>
	@endif
		
	@if(isset($contents['investments']))
		<section class="total_summ_invest">
			<div class="container">
				<div class="left_side">
					{!!(str_replace(['<p>'], ['<p class="standart_text">'], $contents['investments']->content))!!}
				</div>
				
			@if($contents['investments']->image)
				<div class="right_side">
					<img src="{{asset('storage/'.$contents['investments']->image)}}" alt="">
				</div>
			@endif
			</div>
		</section>
	@endif
		
	@if(count($advantages) || count($slides))
		<section class="our_advantages animation-wrap" id="our_advantages">
			<div class="container">
			@if($advantages_label)
				<h2>{{$advantages_label}}</h2>
			@endif
				
				<ul class="our_advantages-animation">
				@foreach($advantages as $item)
					<!-- -->
					<li>
					@if($item->icon)
						<img data-aos="fade-right" data-aos-duration="800" class="our_advantages-img" src="{{asset('storage/'.$item->icon)}}" alt="{{$item->title}}">
					@endif
					@if($item->title)
						<span data-aos="fade-up" data-aos-duration="800" data-aos-anchor-placement="center-bottom" class="title">{{$item->title}}</span>
					@endif
                        <div data-aos="fade-up" data-aos-duration="800" data-aos-anchor-placement="center-bottom">{!!$item->description!!}</div>
					</li>
					<!-- -->
				@endforeach
				</ul>
				
			@if(count($slides))
				<div class="slider_wrap">
					<a href="" rel="nofollow" role="button" class="arrow arrow_left">
						<svg aria-hidden="true" focusable="false" data-prefix="fal" data-icon="angle-left" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 192 512" class="svg-inline--fa fa-angle-left fa-w-6 fa-2x"><path fill="currentColor" d="M25.1 247.5l117.8-116c4.7-4.7 12.3-4.7 17 0l7.1 7.1c4.7 4.7 4.7 12.3 0 17L64.7 256l102.2 100.4c4.7 4.7 4.7 12.3 0 17l-7.1 7.1c-4.7 4.7-12.3 4.7-17 0L25 264.5c-4.6-4.7-4.6-12.3.1-17z" class=""></path></svg>
					</a>
					
					<div class="slider">
					@foreach($slides as $item)
						<!-- -->
						<div class="slide">
							<img src="{{asset('storage/'.$item->image)}}" alt="{{$item->alt}}">
						</div>
						<!-- -->
					@endforeach
					</div>
					
					<a href="" rel="nofollow" role="button" class="arrow arrow_right">
						<svg aria-hidden="true" focusable="false" data-prefix="fal" data-icon="angle-right" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 192 512" class="svg-inline--fa fa-angle-right fa-w-6 fa-2x"><path fill="currentColor" d="M166.9 264.5l-117.8 116c-4.7 4.7-12.3 4.7-17 0l-7.1-7.1c-4.7-4.7-4.7-12.3 0-17L127.3 256 25.1 155.6c-4.7-4.7-4.7-12.3 0-17l7.1-7.1c4.7-4.7 12.3-4.7 17 0l117.8 116c4.6 4.7 4.6 12.3-.1 17z" class=""></path></svg>
					</a>
				</div>
			@endif
			</div>
		</section>
	@endif
	
	@if(count($facts))
		<section class="our_advantages" id="facts_section">
			<div class="container">
				<h2>{{trans('site.about.facts')}}</h2>
				
				<ul>
				@foreach($facts as $item)
					<!-- -->
					<li>
					@if($item->icon)
						<img class="our_advantages-img" src="{{asset('storage/'.$item->icon)}}" alt="{{$item->title}}">
					@endif
					@if($item->title)
						<span class="title">{{$item->title}}</span>
					@endif
						{!!$item->text!!}
					</li>
					<!-- -->
				@endforeach
				</ul>
			</div>
		</section>
	@endif
		
	@if(count($commands))
		<section class="team_section" id="team_section">
			<img src="{{asset('/img/swap_img.png')}}" alt="" class="swap_img">
			
			<div class="container">
				<h2>{{trans('site.about.commands.h2')}}</h2>
				
				<ul class="team_list">
				@foreach($commands as $i => $item)
					<!-- -->
					<li>
						<a href="#team_modal" class="modal_link">
							<figure>
								<img src="{{asset('storage/'.$item->photo)}}" alt="{{$item->name}}">
							</figure>
							
							<p class="name">{{$item->name}}</span>
							<p class="role">{{$item->position}}</p>
							<p class="text_modal">{!!(str_replace("\n", "<br>", $item->description))!!}</p>
						</a>
					</li>
					<!-- -->
				@endforeach
				@if($about_last)
					<li class="last_li_item">
						<div class="left_side">
						@if($about_last['title'])
							<p class="title_p">{{$about_last['title']}}</p>
						@endif
						@if($about_last['url'] && $about_last['label'])
							<a href="{{($lang != $primary_lang ? '/'.$lang.'/' : '/').$about_last['url']}}" class="orange_link">{{$about_last['label']}}</a>
						@endif
						</div>
						
					@if($about_last['image'])
						<div class="right_side">
							<img src="{{asset('storage/'.$about_last['image'])}}" alt="{{$about_last['title']}}">
						</div>
					@endif
					</li>
				@endif
				</ul>
			</div>
		</section>
	@endif
		
	@if(count($partners))
		<section class="partners_section" id="partners_section">
			<div class="container">
				<h2>{{trans('site.about.partners')}}</h2>
				
				<ul>
				@foreach($partners as $item)
					<!-- -->
					<li>
						<span class="item">
							<img src="{{asset('storage/'.$item->partner_logo)}}" alt="{{$item->partner_name}}">
						@if($item->partner_description)
							<div class="hover_block">
								<div class="inside">
									<a href="" rel="nofollow" role="button" class="close_hover_block">
										<img src="{{asset('/img/close_modal.svg')}}" alt="">
									</a>
									<img src="{{asset('/img/hover_img.svg')}}" alt="" class="hover_back">
									<p>{{$item->partner_description}}</p>
								</div>
							</div>
						@endif
						</span>
					</li>
					<!-- -->
				@endforeach
				</ul>
			</div>
		</section>
	@endif
				
		@include('components.contact', ['settings' => $settings, 'contacts' => $contacts])
	</main>
	
	<div class="modal_wrap team_modal" id="team_modal">
        <div class="modal_vacancies_top">
            <span class="page_name">{{trans('site.about.commands.h2')}}</span>
            
            <span class="close_modal_link">
                <img src="{{asset('/img/close_modal_white_icon.svg')}}" alt="">
            </span>
        </div>
        
        <div class="modal_container">
            <div class="inside">
                <div class="left_side">
                    <img src="" alt="">
                </div>
                
                <div class="right_side">
                    <p class="name"></p>
                    <p class="role"></p>
                    <p class="detailed_text"></p>
                </div>
            </div>
        </div>
    </div>
@stop

