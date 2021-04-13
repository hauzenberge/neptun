@php $count = count($slides); @endphp
<div class="slider_wrap">
	@if($count > 1)
	<div class="count_slides">
		<p>
			<span class="current">1</span> /
			<span class="total">{{$count}}</span>
		</p>
	</div>
	
	<a href="" rel="nofollow" role="button" class="arrow arrow_left">
		<svg aria-hidden="true" focusable="false" data-prefix="fal" data-icon="angle-left" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 192 512" class="svg-inline--fa fa-angle-left fa-w-6 fa-2x"><path fill="currentColor" d="M25.1 247.5l117.8-116c4.7-4.7 12.3-4.7 17 0l7.1 7.1c4.7 4.7 4.7 12.3 0 17L64.7 256l102.2 100.4c4.7 4.7 4.7 12.3 0 17l-7.1 7.1c-4.7 4.7-12.3 4.7-17 0L25 264.5c-4.6-4.7-4.6-12.3.1-17z" class=""></path></svg>
	</a>
	@endif
	
	<div class="slider">
		@foreach($slides as $item)
		<div class="slide">
			<img src="{{asset($item->slide_image)}}" alt="{{$item->slide_alt}}">
		</div>
		@endforeach
	</div>
	
	@if($count > 1)
	<a href="" rel="nofollow" role="button" class="arrow arrow_right">
		<svg aria-hidden="true" focusable="false" data-prefix="fal" data-icon="angle-right" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 192 512" class="svg-inline--fa fa-angle-right fa-w-6 fa-2x"><path fill="currentColor" d="M166.9 264.5l-117.8 116c-4.7 4.7-12.3 4.7-17 0l-7.1-7.1c-4.7-4.7-4.7-12.3 0-17L127.3 256 25.1 155.6c-4.7-4.7-4.7-12.3 0-17l7.1-7.1c4.7-4.7 12.3-4.7 17 0l117.8 116c4.6 4.7 4.6 12.3-.1 17z" class=""></path></svg>
	</a>
	@endif
</div>
