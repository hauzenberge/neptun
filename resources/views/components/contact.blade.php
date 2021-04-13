@php $big = (isset($big) && $big) ? true : false; @endphp
<section class="contacts_section {{($big ? 'contacts_page' : '')}}">
	<div class="container">
		<div class="left_side">
		@if($big)
			<h1>{{__('site.contacts.h1')}}</h1>
		@else
			<h2>{{__('site.contacts.h1')}}</h2>
		@endif
			
			<ul class="contacts_list">
			@if($contacts['addresse'])
				<li>
					<span class="title">{{__('site.contacts.addresses')}}</span>
				@foreach($contacts['addresse'] as $item)
					@if($item->value)
					<a href="{{$item->value}}" class="string_link" target="_blank">{{$item->label}}</a>
					<a href="#map" class="string_link scroll_link">{{$item->label}}</a>
					@else
					<span>{{$item->label}}</span>
					@endif
				@endforeach
				</li>
			@endif
			@if($contacts['post'])
				<li>
					<span class="title">{{__('site.contacts.post')}}</span>
				@foreach($contacts['post'] as $item)
					@if($item->value)
					<a href="{{$item->value}}" class="string_link" target="_blank">{{$item->label}}</a>
					@else
					<span>{{$item->label}}</span>
					@endif
				@endforeach
				</li>
			@endif
			@if($contacts['phone'])
				<li>
					<span class="title">{{__('site.contacts.phone')}}</span>
				@foreach($contacts['phone'] as $item)
					<a href="tel:+{{preg_replace('/[^0-9]/', '', $item->value)}}" class="string_link" target="_blank">{{$item->value.($item->label ? ' ('.$item->label.')' : '')}}</a>
				@endforeach
				</li>
			@endif
			@if($contacts['email'])
				<li>
					<span class="title">{{__('site.contacts.email')}}</span>
				@foreach($contacts['email'] as $item)
					<a href="mailto:{{$item->value}}" class="string_link" target="_blank">{{$item->value.($item->label ? ' ('.$item->label.')' : '')}}</a>
				@endforeach
				</li>
			@endif
			@if($settings['social.fb'] || $settings['social.inst'] || $settings['social.yt'])
				<li>
					<span class="title">{{__('site.contacts.social')}}</span>
					<ul class="soc_list">
					@if($settings['social.fb'])
						<li>
							<a href="{{$settings['social.fb']}}" target="_blank">
								<svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" viewBox="0 0 30 30"><g><g clip-path="url(#clip-1686551E-D113-9A04-88FD-226DCBA0C658)"><path fill="#231f20" d="M14.775 0c8.16 0 14.775 6.616 14.775 14.775 0 8.16-6.615 14.774-14.775 14.774S0 22.934 0 14.775C0 6.616 6.616 0 14.775 0zm4.244 11.491h-2.89V9.594c0-.71.47-.877.802-.877h2.04V5.588l-2.808-.01c-3.118 0-3.828 2.332-3.828 3.826v2.087h-1.803v3.222h1.803v9.124h3.794v-9.124h2.557z"/></g></g></svg>
							</a>
						</li>
					@endif
					@if($settings['social.yt'])
						<li>
							<a href="{{$settings['social.yt']}}" target="_blank">
								<svg xmlns="http://www.w3.org/2000/svg" width="30" height="31" viewBox="0 0 30 31"><defs><clipPath id="jjzea"><path d="M0 .59h29.55v28.97H0z"/></clipPath></defs><g><g><g><g><path fill="#231f20" d="M8.47 16.297h1.07v5.21h1.034v-5.21h1.069v-.887H8.47z"/></g><g><g><path fill="#231f20" d="M14.815 11.968c.14 0 .251-.036.333-.11a.41.41 0 0 0 .125-.315v-2.7a.317.317 0 0 0-.127-.262.512.512 0 0 0-.331-.101.456.456 0 0 0-.307.101.326.326 0 0 0-.118.263v2.699c0 .136.037.24.111.314.073.075.177.111.314.111"/></g><g><path fill="#231f20" d="M17.093 16.954c.29 0 .512.097.664.288.155.19.23.472.23.84v2.497c0 .318-.071.562-.215.733-.143.17-.353.254-.623.254-.18 0-.342-.032-.48-.098a1.18 1.18 0 0 1-.392-.306v.345h-.927V15.41h.927v1.965c.124-.137.255-.243.392-.314a.924.924 0 0 1 .424-.107zm-.06 1.178c0-.153-.032-.268-.096-.347a.352.352 0 0 0-.282-.116.428.428 0 0 0-.192.045.637.637 0 0 0-.186.137v2.803a.743.743 0 0 0 .214.158c.07.03.142.048.218.048.112 0 .192-.03.245-.095.051-.063.078-.166.078-.31z"/></g><g><path fill="#231f20" d="M13.742 20.418c-.086.097-.18.176-.284.24a.525.525 0 0 1-.255.094c-.087 0-.148-.023-.19-.07-.036-.048-.056-.125-.056-.23V17h-.917v3.762c0 .269.054.466.162.603.107.133.266.2.478.2.173 0 .35-.046.533-.143.184-.097.36-.235.53-.417v.493h.917V17h-.918z"/></g><g><g/><g clip-path="url(#jjzea)"><path fill="#231f20" d="M14.775.59c8.16 0 14.775 6.485 14.775 14.485 0 8-6.616 14.486-14.775 14.486C6.615 29.56 0 23.076 0 15.075 0 7.075 6.615.59 14.775.59zm2.387 11.295c0 .297.061.518.181.664.121.148.3.223.54.223.194 0 .394-.054.599-.16a2.17 2.17 0 0 0 .594-.46v.547h1.033V7.744h-1.033v3.76a1.44 1.44 0 0 1-.318.27.615.615 0 0 1-.288.104c-.096 0-.168-.028-.21-.08-.043-.052-.066-.136-.066-.254v-3.8h-1.032zm-3.835-.44c0 .421.136.757.406 1.007.27.247.63.372 1.08.372.468 0 .834-.121 1.103-.362.265-.24.399-.571.399-.997V8.907c0-.38-.136-.69-.407-.929-.273-.239-.622-.36-1.048-.36-.467 0-.84.116-1.117.34-.277.23-.416.536-.416.92zM9.344 5.984l1.384 4.054V12.7h1.163V9.913l1.353-3.93h-1.182l-.718 2.683h-.074l-.755-2.683zm14.695 11.53c0-1.795-1.483-3.25-3.311-3.25H9.223c-1.83 0-3.312 1.455-3.312 3.25v2.613c0 1.795 1.483 3.251 3.312 3.251h11.505c1.828 0 3.311-1.456 3.311-3.25z"/></g></g><g><path fill="#231f20" d="M19.865 16.89c.42 0 .742.114.967.34.226.229.337.555.337.98v1.149h-1.753v.847c0 .238.03.401.09.494.06.09.164.135.311.135.151 0 .258-.039.319-.116.058-.077.091-.248.091-.513V20h.942v.231c0 .462-.112.81-.345 1.043-.229.233-.571.349-1.029.349-.41 0-.735-.125-.97-.372-.235-.246-.355-.586-.355-1.02V18.21c0-.387.13-.706.39-.951.26-.247.593-.369 1.005-.369zm.362 1.315c0-.19-.033-.326-.097-.404-.062-.084-.165-.124-.305-.124-.146 0-.25.04-.314.124-.065.078-.095.215-.095.404v.454h.811z"/></g></g></g></g></g></svg>
							</a>
						</li>
					@endif
					@if($settings['social.inst'])
						<li>
							<a href="{{$settings['social.inst']}}" target="_blank">
								<svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" viewBox="0 0 30 30"><g><g><g><g><path fill="#231f20" d="M14.645 12.45c-1.453 0-2.635 1.199-2.635 2.673 0 1.473 1.182 2.672 2.635 2.672 1.453 0 2.635-1.2 2.635-2.672 0-1.474-1.182-2.673-2.635-2.673"/></g><g><path fill="#231f20" d="M17.958 8.57c.961 0 1.769.305 2.334.884.563.574.86 1.389.86 2.356v6.717c0 .995-.301 1.818-.872 2.384-.565.56-1.381.856-2.36.856h-6.549c-.954 0-1.761-.298-2.334-.863-.587-.577-.897-1.412-.897-2.415v-6.68c0-.987.297-1.809.86-2.376.56-.565 1.367-.863 2.334-.863h6.624m-3.312 10.714c2.262 0 4.103-1.867 4.103-4.161 0-2.295-1.84-4.162-4.103-4.162-2.262 0-4.104 1.867-4.104 4.162 0 2.294 1.842 4.161 4.104 4.161m4.262-7.48a.94.94 0 0 0 .937-.945.94.94 0 0 0-.937-.944.941.941 0 0 0-.936.944c0 .523.42.945.936.945"/></g><g/><g><path fill="#231f20" d="M14.775 0c8.159 0 14.774 6.708 14.774 14.984 0 8.275-6.615 14.984-14.774 14.984C6.615 29.968 0 23.258 0 14.984 0 6.708 6.615 0 14.775 0zm7.844 11.802c0-1.367-.444-2.544-1.286-3.403-.848-.865-2.015-1.323-3.376-1.323h-6.624c-2.788 0-4.663 1.9-4.663 4.726v6.676c0 1.413.465 2.617 1.343 3.48.853.84 2.014 1.284 3.357 1.284h6.55c1.369 0 2.54-.443 3.385-1.28.86-.85 1.314-2.042 1.314-3.446z"/></g></g></g></g></svg>
							</a>
						</li>
					@endif
					</ul>
				</li>
			@endif
			</ul>
			
			<div class="form_area">
				<span class="form_title">{{__('site.contacts.questions')}}</span>
				
				<form action="/ajax/send/contact" method="post" id="sendform" enctype="multipart/form-data">
					<div class="form_container">
						<div class="inputs_container">
							<div class="input_cont">
								<input type="text" class="text_input valid" placeholder="{{__('site.contacts.form.name')}}" name="name" required="">
							</div>
							
							<div class="input_cont">
								<input type="email" class="text_input" placeholder="{{__('site.contacts.form.email')}}" name="email" required="">
							</div>
						</div>
						
						<div class="input_cont">
							<textarea name="message" id="message" cols="40" rows="10" placeholder="{{__('site.contacts.form.message')}}"> </textarea>
						</div>
						
						<div class="bottom_form">
							<div class="download_cont">
								<label>
									<input type="file" name="file" id="file_input" data-max="5242880" data-max-error="{{__('site.contacts.form.max-error')}}" data-error="{{__('site.contacts.form.error')}}" class="valid">
									<img src="{{asset('/img/file.svg')}}" alt="" accept="image/x-png,image/jpeg" name="photo">
									<p>{{__('site.contacts.form.add-file')}}</p>
								</label>
							</div>
							
							<div class="name_file">
								<a href="" rel="nofollow" class="remove_file">
									<img src="{{asset('/img/remove_file.svg')}}" alt="">
								</a>
								
								<p id="name_file"></p>
								
								<div class="overlay"></div>
							</div>
							
							<button name="sendMail" type="submit" id="send_input" class="orange_link"><img src="{{asset('/img/check.svg')}}" alt=""><i class="btn-animation"></i> <span>{{__('site.contacts.form.send')}}</span></button>
						</div>
						
						<div class="input_cont send-status"></div>
					</div>
				</form>
			</div>
		</div>
	</div>
	
	<div id="map"></div>
</section>
