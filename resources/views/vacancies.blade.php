@extends('layout')

@section('content')
	<main>
		<section class="vacancies_main">
		@if($page['image'])
			<img src="{{url('storage/'.$page['image'])}}" alt="{{$page['name']}}" class="back_img">
		@endif
			<div class="overlay"></div>
			
			<div class="container">
				<h1>{{trans('site.vacancies.h1')}}</h1>
				<p>{{trans('site.vacancies.description')}}</p>
			</div>
		</section>
		
		<section class="actual_vacancies">
			<div class="container">
				<div class="left_side">
				@if($vacancies)
					<h2>{{trans('site.vacancies.list')}}</h2>
					
					<ul class="vacancies_list">
					@foreach($vacancies as $i => $item)
						<!-- -->
						<li>
							<a href="" rel="nofollow" role="button" class="vacancies_name">
								<i><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24">
									<g><g><g><g><path d="M7.41 8.59L12 13.17l4.59-4.58L18 10l-6 6-6-6z"/></g><g/></g></g></g>
								</svg></i>
								<span>{{$item->name}}</span>
							</a>
							<div class="content">
							@if($item->description)
								<div class="content_block">
								@if($item->label)
									<p><b>{{$item->label}}</b></p>
								@endif
									{!!$item->description!!}
								</div>
							@endif
								
								<a href="#modal_vacancies" rel="nofollow" role="button" class="send_resume orange_link modal_link">{{trans('site.vacancies.send')}}</a>
							</div>
						</li>
						<!-- -->
					@endforeach
					</ul>
				@else
					<h2 style="margin-bottom:0px;">{{trans('site.vacancies.empty')}}</h2>
				@endif
				</div>
				
				<div class="right_side">
					<div class="form_outter">
						<span class="title">{{trans('site.vacancies.didnt_find_vacancy')}}</span>
						
						<p class="title_p">{{trans('site.vacancies.job_form_text')}}</p>
						
						<form action="/ajax/send/job" id="job_form" class="form_item" novalidate="novalidate">
							<div class="input_cont">
								<input type="text" class="text_input valid" placeholder="{{trans('site.vacancies.form.name')}}" required="" name="name">
							</div>
							
							<div class="input_cont">
								<input type="text" class="text_input" required="" placeholder="{{trans('site.vacancies.form.spec')}}" name="spec">
							</div>
							
							<div class="input_cont">
								<input type="email" class="text_input" required="" placeholder="{{trans('site.vacancies.form.email')}}" name="email">
							</div>
							
							<div class="input_cont">
								<input type="tel" class="text_input" placeholder="{{trans('site.vacancies.form.tel')}}" required="" name="tel" maxlength="18">
							</div>
							
							<!-- start download form -->
							<div class="bottom_form">
								<div class="download_cont">
									<label>
										<input type="file" name="file" id="file_input" data-max="5242880" data-max-error="{{trans('site.vacancies.form.max-error')}}" data-error="{{trans('site.vacancies.form.error')}}" class="valid" aria-invalid="false">
										<img src="/img/file.svg" alt="" accept="image/x-png,image/gif,image/jpeg" name="photo">
										<p>{{trans('site.vacancies.form.add_resume')}}</p>
									</label>
								</div>
								
								<div class="name_file">
									<a href="" role="button" rel="nofollow" class="remove_file">
										<img src="/img/remove_file.svg" alt="">
									</a>
									<p id="name_file"></p>
									<div class="overlay"></div>
								</div>
							</div>
							<!-- end download form -->
							<input type="hidden" class="form_id" name="form_id" value="job_main_form">
							
							<button type="submit" class="orange_link"><i class="btn-animation"></i>{{trans('site.vacancies.send_btn')}}</button>
							
							<div class="input_cont send-status"></div>
						</form>
					</div>
					
					<div class="form_thanks_wrap">
						<img src="/img/thanks_img.svg" alt="">
						<span>{{trans('site.vacancies.send_success')}}</span>
					</div>
				</div>
			</div>
		</section>
		
		@include('components.contact', ['settings' => $settings, 'contacts' => $contacts])
	</main>
	
	<div class="modal_wrap modal_vacancies" id="modal_vacancies">
        <div class="modal_vacancies_top">
            <span class="page_name">{{trans('site.vacancies.h1')}}</span>
            
            <span class="close_modal_link">
                <img src="/img/close_modal_white_icon.svg" alt="">
            </span>
        </div>
        
        <div class="modal_container modal_container_text active">
            <div class="inner">
                <span class="title"></span>
                
                <form action="/ajax/send/job" id="job_form2" class="form_item" novalidate="novalidate">
                    <div class="input_cont">
                        <input type="text" class="text_input valid" placeholder="{{trans('site.vacancies.form.name')}}" required="" name="name">
                    </div>
                    
                    <div class="input_cont">
                        <input type="email" class="text_input" required="" placeholder="{{trans('site.vacancies.form.email')}}" name="email">
                    </div>
                    
                    <div class="input_cont">
                        <input type="tel" class="text_input" placeholder="{{trans('site.vacancies.form.tel')}}" required="" name="tel" maxlength="18">
                    </div>
                    
                    <!-- start download form -->
                    <div class="bottom_form">
                        <div class="download_cont">
                            <label>
                                <input type="file" name="file" id="file_input2" data-max="5242880" data-max-error="{{trans('site.vacancies.form.max-error')}}" data-error="{{trans('site.vacancies.form.error')}}" class="valid" aria-invalid="false">
                                <img src="/img/file.svg" alt="" accept="image/x-png,image/gif,image/jpeg" name="photo">
                                <p>{{trans('site.vacancies.form.add_resume')}}</p>
                            </label>
                        </div>

                        <div class="name_file">
                            <a href="" role="button" rel="nofollow" class="remove_file">
                                <img src="/img/remove_file.svg" alt="">
                            </a>
                            <p id="name_file"></p>
                            <div class="overlay"></div>
                        </div>
                    </div>
                    <!-- end download form -->
                    
                    <input type="hidden" class="form_id" name="form_id" value="job_main_form">
                    <input type="hidden" name="spec" value="">
                    
                    <button type="submit" class="orange_link"><i class="btn-animation"></i>{{trans('site.vacancies.send_btn')}}</button>
                    
                    <div class="input_cont send-status"></div>
                </form>
            </div>
        </div>
        
        <div class="modal_container modal_container_thanks hide">
            <img src="/img/thanks_img.svg" alt="">
            <p class="thx_text">{{trans('site.vacancies.send_success')}}</p>
        </div>
    </div>
@stop
