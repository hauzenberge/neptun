<!DOCTYPE html>
<html lang="{{$html_lang}}">
    <head>
        <meta charset="utf-8">
		
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
		
        <title>{{$page['title']}}</title>
		
        <meta name="keywords" content="{{$page['keywords']}}">
        <meta name="description" content="{{$page['description']}}">
		
	@if($settings['copyright'])
        <meta name="copyright" content="{!!$settings['copyright']!!}">
	@endif
		
        <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0">
		
    @if($settings['favicon'])
        <link rel="icon" href="{{asset('/'.$settings['favicon'])}}" type="image/png" />
        <link rel="shortcut icon" href="asset('/'.$settings['favicon'])}}" type="image/png" />
    @else
        <link rel="shortcut icon" href="{{asset('/favicon.ico')}}" type="image/x-icon" />
    @endif
		
        <meta property="og:site_name"       content="{{$settings['appname']}}" />
        <meta property="og:title"           content="{{$page['title']}}" />
        <meta property="og:type"            content="website" />
        <meta property="og:description"     content="{{$page['description']}}" />
		<meta property="og:url"     		content="{{url()->current()}}" />
		
    @if($page['og_image'])
        <meta property="og:image"           content="{{asset('/'.$page['og_image'])}}" />
	@else
		<meta property="og:image"           content="{{asset('/img/og-200x200.jpg')}}" />
		<meta property="og:image:secure_url" content="{{asset('/img/og-200x200.jpg')}}" />
		<meta property="og:image:width"		content="200" />
		<meta property="og:image:height"	content="200" />
		<meta property="og:image:type"		content="image/jpeg" />
    @endif
		
    @if($settings['google_api_key'])
        <meta name="google-site-verification"   content="{{$settings['google_api_key']}}">
    @endif
		
        <meta name="csrf-token" content="{{ csrf_token() }}">
        
		<link rel="stylesheet" href="{{asset('/css/reset.css')}}">
		<link async title="timeline-styles" rel="stylesheet" href="https://cdn.knightlab.com/libs/timeline3/latest/css/timeline.css">
		<link rel="stylesheet" href="{{asset('/css/add.css')}}">
		<link rel="stylesheet" href="{{asset('/css/slick.css')}}">
        <link href="https://unpkg.com/aos@2.3.1/dist/aos.css')}}" rel="stylesheet">
		<link rel="stylesheet" href="{{asset('/css/font.css')}}">
		<link rel="stylesheet" href="{{asset('/css/content.css?v=10')}}">
		<link rel="stylesheet" href="{{asset('/css/jquery.fancybox.min.css')}}">
		<link rel="stylesheet" href="{{asset('/css/media.css?v=11')}}">
		
		{!!$settings['head_code']!!}
    </head>
    <body>
		{!!$settings['body_code']!!}
		
		<div class="wrapper">
			<div class="responsive_wrapper">
				<div class="inside">
					<nav>
						<ul class="nav_list">
					@if($menu)
						@foreach($menu as $item)
							<li>
							@if($item->submenu)
								<a href="{{$item->uri}}" data-sub="1">
									<i class="angle">
										<svg aria-hidden="true" focusable="false" data-prefix="far" data-icon="angle-down" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 320 512" class="svg-inline--fa fa-angle-down fa-w-10 fa-2x"><path fill="currentColor" d="M151.5 347.8L3.5 201c-4.7-4.7-4.7-12.3 0-17l19.8-19.8c4.7-4.7 12.3-4.7 17 0L160 282.7l119.7-118.5c4.7-4.7 12.3-4.7 17 0l19.8 19.8c4.7 4.7 4.7 12.3 0 17l-148 146.8c-4.7 4.7-12.3 4.7-17 0z" class=""></path></svg>
									</i> {{$item->label}}
								</a>
								<ul class="inside_list">
								@foreach($item->submenu as $submenu)
									<li>
										<a href="{{$submenu->uri}}">{{$submenu->label}}</a>
									</li>
								@endforeach
								</ul>
							@else
								<a href="{{$item->uri}}" data-sub="0" {!!(($item->uri == $page['url'] || $item->uri == $active) ? 'class="active"' : '')!!}>{{$item->label}}</a>
							@endif
							</li>
						@endforeach
					@endif
						</ul>
					</nav>
				</div>
			</div>
			
			<header>
				<div class="container">
					<div class="header-group">
						<a href="/{{($lang != $primary_lang ? $lang : '')}}" class="logo"><img src="{{asset('/storage/logo-new.png')}}" alt="{{$settings['appname']}}"></a>
					@if($settings['livebtn_show'])
						<a class="header-live" href="/#online_translation">{{($settings['livebtn_label'] ? $settings['livebtn_label'] : 'Live video')}}</a>
					@endif
					</div>
					
					<div class="right_side">
						<nav>
							<ul class="nav_list">
						@if($menu)
							@foreach($menu as $item)
								<li>
								@if($item->submenu)
									<a href="{{$item->uri}}" data-sub="1">
										<i class="angle">
											<svg aria-hidden="true" focusable="false" data-prefix="far" data-icon="angle-down" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 320 512" class="svg-inline--fa fa-angle-down fa-w-10 fa-2x"><path fill="currentColor" d="M151.5 347.8L3.5 201c-4.7-4.7-4.7-12.3 0-17l19.8-19.8c4.7-4.7 12.3-4.7 17 0L160 282.7l119.7-118.5c4.7-4.7 12.3-4.7 17 0l19.8 19.8c4.7 4.7 4.7 12.3 0 17l-148 146.8c-4.7 4.7-12.3 4.7-17 0z" class=""></path></svg>
										</i> {{$item->label}}
									</a>
									<ul class="inside_list">
									@foreach($item->submenu as $submenu)
										<li>
											<a href="{{$submenu->uri}}">{{$submenu->label}}</a>
										</li>
									@endforeach
									</ul>
								@else
									<a href="{{$item->uri}}" data-sub="0" {!!(($item->uri == $page['url'] || $item->uri == $active) ? 'class="active"' : '')!!}>{{$item->label}}</a>
								@endif
								</li>
							@endforeach
						@endif
							</ul>
						</nav>
						
					@if(count($langs) > 1)
						<div class="lang_cont">
						@foreach($langs as $item)
							<a href="{{url('/')}}{{$primary_lang == $item ? ($page['uri'] != 'index' ? '' : '/') : '/'.($item != 'ua' ? $item : '')}}{{($page['uri'] != 'index' ? '/'.$page['uri'] : '')}}" class="lang_item {{($lang == $item ? 'current_language selected' : 'hidden_language')}}">{{strtoupper($item)}}</a>
						@endforeach
						</div>
					@endif
						
						<button class="menu_mob_but">
							<span></span>
							<span></span>
							<span></span>
						</button>
					</div>
				</div>
			</header>
			
			@yield('content')
		</div>
		
		<div class="modal_wrap video_modal" id="video_modal">
			<div class="modal_container">
				<iframe width="560" height="315" src="" frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
			</div>
		</div>
		
		<script src="{{asset('/js/jquery-3.1.1.min.js')}}"></script>
		<script src="{{asset('/js/slick.js')}}"></script>
		<script src="{{asset('/js/jquery.mask.js')}}"></script>
		<script src="{{asset('/js/jquery.validate.min.js')}}"></script>
		<script src="{{asset('/js/jquery.base64.js')}}"></script>
		<script src="{{asset('/js/timeline.js')}}"></script>
		<script src="{{asset('/js/jquery.fancybox.min.js')}}"></script>
        <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
		<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAxF4nz4Tjr34MakvDvId2Q1AiLYOgngcw"></script>
		<script src="{{asset('/js/lang_'.$lang.'.js')}}"></script>
		<script src="{{asset('/js/main.js?v=2')}}"></script>
		
		{!!$settings['footer_code']!!}
    </body>
</html>
