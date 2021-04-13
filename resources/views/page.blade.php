@extends('layout')

@section('content')
	<div class="progress-bar">
		<span class="progress"></span>
	</div>
	
	<main>
		<section class="news_one_section">
			<div class="container">
				<h1 class="name_news">{{$page['title']}}</h1>
				
			@foreach($contents['other'] as $item)
				@if($item->image)
				<img src="{{url('storage/'.$item->image)}}" alt="{{$page['title']}}" class="main_photo">
				@endif
				
				{!!$item->content!!}
			@endforeach
			</div>
			
			<div class="share_block">
				<a href="#" rel="nofollow" id="fb_share">{{__('site.share')}} <i>
					<svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" viewBox="0 0 30 30"><g><g clip-path="url(#clip-1686551E-D113-9A04-88FD-226DCBA0C658)"><path fill="#231f20" d="M14.775 0c8.16 0 14.775 6.616 14.775 14.775 0 8.16-6.615 14.774-14.775 14.774S0 22.934 0 14.775C0 6.616 6.616 0 14.775 0zm4.244 11.491h-2.89V9.594c0-.71.47-.877.802-.877h2.04V5.588l-2.808-.01c-3.118 0-3.828 2.332-3.828 3.826v2.087h-1.803v3.222h1.803v9.124h3.794v-9.124h2.557z"/></g></g></svg></i>
				</a>
			</div>
		</section>
	</section>
@stop
