$(document).ready(function(){
	fb_share();
    langLink();
    sliderHistory()

    var animation = $('.animation-wrap');

    if(animation.length > 0){
        AOS.init();
    }

    // SLIDER 1
    $('.main_section .slider').slick({
        slidesToShow: 1,
        slidesToScroll: 1,
        autoplay: true,
        adaptiveHeight: false,
        dots: false,
        arrows: true,
        fade: false,
        nextArrow: '.main_section .arrow_right',
        prevArrow: '.main_section .arrow_left',
    });

    // SLIDER 2
    $('.building_steps .slider').slick({
        slidesToShow: 1,
        slidesToScroll: 1,
        autoplay: true,
        adaptiveHeight: false,
        dots: false,
        arrows: true,
        fade: false,
        nextArrow: '.building_steps .arrow_right',
        prevArrow: '.building_steps .arrow_left',
    });

    if($(window).width() < 992){
        $('.online_translation ul').slick({
            slidesToShow: 2,
            slidesToScroll: 1,
            autoplay: true,
            adaptiveHeight: false,
            dots: false,
            arrows: false,
            fade: false,
            responsive: [{
                breakpoint: 769,
                settings: {
                    slidesToShow: 1,
                    slidesToScroll: 1
                }
            }]
        });

        //
        $('.responsive_wrapper .inside nav .nav_list > li a').on('click', function(e){
			var current = $(this),
				next = current.next();

			if(next.length && next.hasClass('inside_list')){
				e.preventDefault();

				next.slideToggle(0);
			}
        });
    };

    // MASK JS
    $('input[type="tel"]').mask('+38 000 00 00 000');

    // MAP
    if($('#map').length){
        function initMap(){
            var map = new google.maps.Map(document.getElementById('map'), {
                center: new google.maps.LatLng(46.659972, 31.001667),
                zoom: 17,
                disableDefaultUI: true,
            });

            var uluru = new google.maps.LatLng(46.659972, 31.001667);

            var svgi = {
                url: '/img/map_geo.png',
            };

            var marker = new google.maps.Marker({
                position: uluru,
                map: map,
                icon: svgi,
                title: 'Neptune'
            });
        };

        initMap();
    };

    // HOME PAGE FORM
    sendform();

    function serializeObject(form){
        let o = {};
        let a = form.serializeArray();

        $.each(a, function(){
            if(o[this.name]){
                if(!o[this.name].push){
                    o[this.name] = [o[this.name]];
                };

                o[this.name].push(this.value || '');
            }else{
                o[this.name] = this.value || '';
            }
        });

        return o;
    };

    function removeThanksBlock(){
        $('.form_area').removeClass('active');
        $('.form_area').find('button').children('span').html(langs.send_message);
    };

    function removeThanksBlockContacts(){
        $('.form_area').removeClass('active');
        $('.form_area').find('button').children('span').html(langs.send_message);
    };

    function sendform(){
        var form = jQuery("#sendform");

        if (!form.length) {
            return false;
        };

        var lock = false,
            btn = form.find('button[type="submit"]'),
            status = form.find('.send-status');

        var input = $('#file_input');

        var file_data = '',
            file_name = '',
            file_type = '';

        form.validate({
            onkeyup: false,
            focusCleanup: true,
            rules: {
                name: {
                    required: true,
                    minlength: 2,
                    maxlength: 40
                },
                email: {
                    required: true,
                    email: true,
                },
                message: {
                    required: true,
                    minlength: 8,
                    maxlength: 2000,
                },
            },
            messages: {
                name: {
                    required: langs.required,
                    minlength: jQuery.validator.format(langs.minlen),
                    maxlength: jQuery.validator.format(langs.maxlen)
                },
                email: {
                    required: langs.required,
                    email: langs.email_invalid,
                },
                message: {
                    required: langs.required,
                    minlength: jQuery.validator.format(langs.minlen),
                    maxlength: jQuery.validator.format(langs.maxlen)
                },
            },
            submitHandler: function() {
                if (!lock) {
                    var data = serializeObject(form);

                    if (file_type.length && file_data.length) {
                        data['file'] = 'data:' + file_type + ';base64,' + file_data;
                        data['file_name'] = file_name;
                    };

                    console.log('data:');
                    console.log(data);

                    //return false;

                    $.ajax({
                        type: "POST",
                        url: form.attr("action"),
                        data: JSON.stringify(data),
                        datatype: "application/json",
                        contentType: "application/json; charset=utf-8",
                        beforeSend: function(request) {
                            lock = true;

                            btn.attr('disabled', true);
                            form.find('label.error').text('').hide();

                            status.text('').hide();
                        },
                        success: function(response) {
                            console.log('response:');
                            console.log(response);

                            lock = false;
                            btn.attr('disabled', false);

                            if (response.status) {
                                $('.form_area').addClass('active');
                                $('.form_area').find('button').children('span').html(langs.sent);

                                form.trigger('reset');

                                $('.download_cont').removeClass('hide');
                                $('.name_file').removeClass('active');
                                $('#name_file').html('');

                                setTimeout(removeThanksBlockContacts, 5000);
                            } else {
                                status.text(response.msg).show();
                            }
                        },
                        error: function(err) {
                            lock = false;
                            btn.attr('disabled', false);

                            status.text(langs.set_up_failed).show();
                        }
                    });
                };

                return false;
            }
        });

        if (input.length) {
            var reader = new FileReader();

            $.base64.utf8encode = false;

            reader.addEventListener('load', function(e) {
                //console.log('load:');
                //console.log(e.target.result);

                file_data = $.base64.btoa(e.target.result);
            });

            input.on('change', function(event) {
                var max_size = input.attr('data-max');

                // если выбрали файл
                if (input.prop('files')[0]) {
                    if (input.prop('files')[0].size > max_size) {
                        alert(input.attr('data-max-error'));
                        return false;
                    };

                    document.getElementById('name_file').innerHTML = input.prop('files')[0].name;

                    $('.download_cont').addClass('hide');
                    $('.name_file').addClass('active');

                    //console.log('file:');
                    //console.log(input.prop('files')[0]);

                    file_name = input.prop('files')[0].name;
                    file_type = input.prop('files')[0].type;

                    reader.readAsBinaryString(input.prop('files')[0]);
                } else {
                    $('.download_cont').removeClass('hide');
                    $('.name_file').removeClass('active');

                    $('.form_area .bottom_form .download_cont label input').val('');

                    document.getElementById('name_file').innerHTML = '';

                    file_data = '';
                    file_name = '';
                    file_type = '';
                }
            });

            $('.remove_file').on('click', function(event) {
                event.preventDefault();

                $('.download_cont').removeClass('hide');
                $('.name_file').removeClass('active');

                $('.form_area .bottom_form .download_cont label input').val('');

                document.getElementById('name_file').innerHTML = '';

                file_data = '';
                file_name = '';
            });
        }
    };

    // TIMELINE JS OPTIONS
    if($('#timeline-embed').length){
        var options = {
            //language		: 'uk',
            start_at_slide	: 1,
            language		: $('html').attr('lang').split('-')[0]
        };

        timeline = new TL.Timeline('timeline-embed', $('#timeline-embed').attr('data-src'), options);
    };

    // FANCYBOX
    $('.modal_link, .gallery_photo').fancybox({
        touch: false
    });

    $('.online_translation a').on('click', function(){
        var videoSrc = $(this).attr('data-src');

        $('.video_modal iframe').attr('src', videoSrc);
    });

    // MENU
    $('.menu_mob_but').on('click', function(){
        if ($(this).hasClass('menu_mob_but--active')) {
            $(this).removeClass('menu_mob_but--active');

            $('body').removeClass('active');
            $('.responsive_wrapper').removeClass('active');
        } else {
            $(this).addClass('menu_mob_but--active');

            $('body').addClass('active');
            $('.responsive_wrapper').addClass('active');
        }
    });

    // GALLERY FILTER
    function DataitemCheck3(dataItem){
        $('.gallery_list li').removeClass('active');

        $('.gallery_list li').each(function(){
            $(this).attr('class');

            if ($(this).attr('class') == dataItem){
                $(this).addClass('active');
            }
        });
    };

    $('.gallery_section .tab_list li a').on("click", function(event){
        event.preventDefault();

        if($(this).hasClass('filter_all')){
            $('.gallery_section .tab_list li a').removeClass('active');

            $(this).addClass('active');

            $('.gallery_list li').addClass('active');
        }else{
            var dataItem = $(this).attr('class');

            $('.gallery_section .tab_list li a').removeClass('active');

            $(this).addClass('active');

            DataitemCheck3(dataItem);
        }
    });

    // SCROLL
    $(".scroll_link").on("click", function(event){
        event.preventDefault();

        var id = $(this).attr('href');
        var top = $(id).offset().top;

        $('.responsive_wrap').removeClass('active');

        $('body,html').animate({
			scrollTop: top
		}, 1500);
    });

    // VACANCIES LIST
    $('.vacancies_name').on('click', function(e){
        e.preventDefault();

        $(this).toggleClass('active');
        $(this).siblings('.content').slideToggle(0);
    });

    //  VACANCIES FORM 1
    form_item();

    function removeThanksBlock(){
        $('.form_outter').removeClass('hide');
        $('.form_thanks_wrap').removeClass('active');
    };

    function form_item(){
        var forms = jQuery(".form_item");

        if(!forms.length) {
            return false;
        };

        var lock = false;

        var input = $('#file_input,#file_input2');

        var file_data = '',
            file_name = '',
            file_type = '';

        forms.each(function(n, el) {
            $(el).validate({
                onkeyup: false,
                focusCleanup: true,
                rules: {
                    name: {
                        required: true,
                        minlength: 2,
                        maxlength: 40
                    },
                    spec: {
                        required: true,
                        minlength: 2,
                        maxlength: 40
                    },
                    email: {
                        required: true,
                        email: true,
                    },
                    tel: {
                        required: true,
                        minlength: 10,
                        maxlength: 20
                    },
                },
                messages: {
                    name: {
                        required: langs.required,
                        minlength: jQuery.validator.format(langs.minlen),
                        maxlength: jQuery.validator.format(langs.maxlen)
                    },
                    spec: {
                        required: langs.required,
                        minlength: jQuery.validator.format(langs.minlen),
                        maxlength: jQuery.validator.format(langs.maxlen)
                    },
                    email: {
                        required: langs.required,
                        email: langs.email_invalid,
                    },
                    tel: {
                        required: langs.required,
                        minlength: jQuery.validator.format(langs.minlen),
                        maxlength: jQuery.validator.format(langs.maxlen)
                    },
                }
            });
        });

        forms.on('submit', function(e) {
            e.preventDefault();

            console.log('lock:', lock);

            if (!lock) {
                var form = $(this),
                    btn = form.find('button[type="submit"]'),
                    status = form.find('.send-status');

                if (!form.valid()) {
                    return false;
                };

                var data = serializeObject(form);

                if (file_type.length && file_data.length) {
                    data['file'] = 'data:' + file_type + ';base64,' + file_data;
                    data['file_name'] = file_name;
                };

                console.log('data:');
                console.log(data);

                $.ajax({
                    url: form.attr("action"),
                    type: "post",
                    data: JSON.stringify(data),
                    datatype: "application/json",
                    contentType: "application/json; charset=utf-8",
                    beforeSend: function(request) {
                        lock = true;

                        btn.attr('disabled', true);

                        form.find('label.error').text('').hide();
                        status.text('').hide();
                    },
                    success: function(resp) {
                        lock = false;
                        btn.attr('disabled', false);

                        if (resp.status) {
                            form.trigger('reset');

                            form.find('.download_cont').removeClass('hide');
                            form.find('.name_file').removeClass('active');
                            form.find('.name_file p').html('');

                            if ($('.fancybox-enabled').length) {
                                $('.fancybox-is-open .modal_container_text').addClass('hide');
                                $('.fancybox-is-open .modal_container_thanks').addClass('active');
                            } else {
                                $('.form_outter').addClass('hide');
                                $('.form_thanks_wrap').addClass('active');

                                setTimeout(removeThanksBlock, 5000);
                            }
                        } else {
                            status.text(resp.msg).show();
                        }
                    },
                    error: function(error) {
                        lock = false;
                        btn.attr('disabled', false);

                        status.text(langs.set_up_failed).show();

                    }
                });
            };

            return false;
        });

        if (input.length) {
            var reader = new FileReader();

            $.base64.utf8encode = false;

            reader.addEventListener('load', function(e) {
                //console.log('load:');
                //console.log(e.target.result);

                file_data = $.base64.btoa(e.target.result);
            });

            input.on('change', function(event) {
                var currernt_input = $(this);
                var current_form = currernt_input.parents('form.form_item');

                var max_size = currernt_input.attr('data-max');

                // если выбрали файл
                if (currernt_input.prop('files')[0]) {
                    if (currernt_input.prop('files')[0].size > max_size) {
                        alert(currernt_input.attr('data-max-error'));
                        return false;
                    };

                    current_form.find('.name_file p').html(currernt_input.prop('files')[0].name);

                    current_form.find('.download_cont').addClass('hide');
                    current_form.find('.name_file').addClass('active');

                    file_name = currernt_input.prop('files')[0].name;
                    file_type = currernt_input.prop('files')[0].type;

                    reader.readAsBinaryString(currernt_input.prop('files')[0]);
                } else {
                    current_form.find('.download_cont').removeClass('hide');
                    current_form.find('.name_file').removeClass('active');

                    current_form.find('.download_cont label input').val('');

                    current_form.find('.name_file p').html('');

                    file_data = '';
                    file_name = '';
                    file_type = '';
                }
            });

            $('.remove_file').on('click', function(event) {
                event.preventDefault();

                var current_form = $(this).parents('form.form_item');

                current_form.find('.download_cont').removeClass('hide');
                current_form.find('.name_file').removeClass('active');

                current_form.find('.download_cont label input').val('');

                current_form.find('.name_file p').html('');

                file_data = '';
                file_name = '';
            });
        }
    };

    // CHANGE TITLE IN VACANCIES MODAL
    $('.actual_vacancies .left_side .modal_link').on('click', function(e){
        e.preventDefault();

        var currVacancie = $(this).parent('.content').siblings('.vacancies_name').find('span').text();

        $('.modal_vacancies').find('.title').html(currVacancie);
        $('.modal_vacancies').find('input[name="spec"]').val(currVacancie);
    });

    // NEWS ONE SLIDER
    $('.news_one_section .slider_wrap .slider').slick({
        slidesToShow: 1,
        slidesToScroll: 1,
        autoplay: true,
        adaptiveHeight: false,
        dots: false,
        arrows: true,
        fade: false,
        nextArrow: '.news_one_section .slider_wrap .arrow_right',
        prevArrow: '.news_one_section .slider_wrap .arrow_left',
    });

    // get current number of slide
    $('.news_one_section .slider_wrap .slider').on('afterChange', function(event, slick, currentSlide){
        var currSlide = (currentSlide + 1);
        $('.news_one_section .slider_wrap .current').text(currSlide);
    });

    // get total number of slides
    $('.news_one_section .slider_wrap .total').html($(".news_one_section .slider_wrap .slider").slick("getSlick").slideCount);

    // SCROLL HEADER
    var tempScrollTop = 0;
    var currentScrollTop = 0;

    jQuery(window).scroll(function(){
        currentScrollTop = jQuery(window).scrollTop();

        if(tempScrollTop < currentScrollTop){
            $('header').addClass('header_hide');
            $('.coordinate_block .left_area').removeClass('sticky_modified');

            if($(window).scrollTop() < 20){
                $('header').removeClass('header_hide');
            }
        }else if(tempScrollTop > currentScrollTop) {
            $('.coordinate_block .left_area').addClass('sticky_modified');
            $('header').removeClass('header_hide');

            if(currentScrollTop == 0){
                $('header').removeClass('header_hide');
            }
        };

        tempScrollTop = currentScrollTop;
    });

    // PROGRESS BAR

    var progressScrollTop = 0;
    var documentHeight = $(document).height() - $(window).height();

    jQuery(window).scroll(function(){
        progressScrollTop = jQuery(window).scrollTop();

        var progressPercent = (progressScrollTop / documentHeight) * 100;
        var progressWidth = parseInt(progressPercent) + '%';

        $('.progress-bar .progress').css('width', progressWidth);
    });

    // NEWS PAGE FILTER
    function DataitemCheck2(dataItem){
        $('.news_list li').removeClass('active');

        $('.news_list li').each(function(){
            $(this).attr('class');

            if($(this).attr('class') == dataItem){
                $(this).addClass('active');
            }
        });
    };

    $('.news_tab_list li a').on("click", function(event){
        event.preventDefault();

        if($(this).hasClass('filter_all')){
            $('.news_tab_list li a').removeClass('active');
            $(this).addClass('active');

            $('.news_list li').addClass('active');
        }else{
            var dataItem = $(this).attr('class');

            $('.news_tab_list li a').removeClass('active');

            $(this).addClass('active');

            DataitemCheck2(dataItem);
        }
    });

    // ABOUT US SLIDER
    $('.our_advantages .slider_wrap .slider').slick({
        slidesToShow: 1,
        slidesToScroll: 1,
        autoplay: true,
        adaptiveHeight: false,
        dots: false,
        arrows: true,
        fade: false,
        nextArrow: '.our_advantages .slider_wrap .arrow_right',
        prevArrow: '.our_advantages .slider_wrap .arrow_left',
    });

    // TEAM MODAL
    $('.team_list li a').on('click', function(){
		// get data
		var photo = $(this).find('img').attr('src');
		var name = $(this).find('.name').text();
		var role = $(this).find('.role').text();
		var text = $(this).find('.text_modal').html();

		// push data
		$('.team_modal .left_side img').attr('src', photo);
		$('.team_modal .name').text(name);
		$('.team_modal .role').text(role);
		$('.team_modal .detailed_text').html(text);
    });

    // TEAM SLIDER
    if($(window).width() < 651){
        $('.last_li_item').remove();

        $('.team_section .team_list').slick({
            slidesToShow: 1,
            slidesToScroll: 1,
            autoplay: true,
            adaptiveHeight: false,
            dots: false,
            arrows: false,
            fade: false,
            // responsive: [
            //     {
            //       breakpoint: 451,
            //       settings: "unslick"
            //     }

            //   ]
        });
    };

    // PARTNERS CLICK ACTION
    if($(window).width() < 992){
        $('.partner_img').on('click', function(){
            $(this).siblings('.hover_block').addClass('active');
        });

        $('.close_hover_block').on('click', function(e){
            e.preventDefault();

            $(this).parent('.inside').parent('.hover_block').removeClass('active');
        });
    };
});

function fb_share(){
	$('#fb_share').on('click', function(e){
		e.preventDefault();

		u=location.href;
		t=document.title;

		window.open('http://www.facebook.com/sharer.php?u='+encodeURIComponent(u)+'&t='+encodeURIComponent(t),'sharer','toolbar=0,status=0,width=626,height=436');

		return false;
	});
};

function langLink(){
    var el = $('.lang_cont')

     if (!el.length) {
        return false
     }

    if($(window).width() < 890){
        var disabled = false;

        // language panel
        $(document).on('click touchstart', '.lang_item', function(event){
            if(disabled){
                event.preventDefault();
                return false;
            };

            var current = $(this);
            var parent = current.parent();

             if ($('.lang_item').length > 2) {
                   $('.lang_cont').children('.hidden_language').last().addClass('transform');
                }

            if(current.hasClass('current_language')){
                event.preventDefault();

                disabled = true;

                var visible = parent.find('.visible');

                if(visible.length){
                    visible.removeClass('visible');
                }else{
                    parent.find('.hidden_language').addClass('visible');

                    if ($('.lang_item').length > 2) {
                        current.closest('.lang_cont').children('.hidden_language').last().addClass('transform');
                    }
                };

                setTimeout(function(){
                    disabled = false;
                }, 500);
            }else{
                event.preventDefault();

                disabled = true;

                if ($('.lang_item').length > 2) {
                    $('.lang_cont').children('.hidden_language').removeClass('visible');
                    $('.lang_cont').children('.hidden_language').removeClass('transform');
                }

                parent.find('.current_language').removeClass('current_language').removeClass('selected').removeClass('visible').addClass('hidden_language');
                current.addClass('current_language').removeClass('hidden_language').addClass('selected');

                setTimeout(function(){
                    disabled = false;

                    location.href = current.attr('href');
                }, 500);
            };
        });

        /*
        $(document).on('click touchstart', '.current_language', function(event){
            event.preventDefault();
        });
        */
    }else{
        $('.lang_cont').hover(function(){
            $(this).children('.hidden_language').addClass('visible');
            if ($('.lang_item').length > 2) {
               $(this).children('.hidden_language').last().addClass('transform');
                }
        }, function(){
            $(this).children('.hidden_language').removeClass('visible');
            if ($('.lang_item').length > 2) {
               $(this).children('.hidden_language').last().removeClass('transform');
                }
        });

        $(document).on('click', '.lang_item', function(event){
            $(this).parent('.lang_cont').children('.lang_item').removeClass('selected');
            $(this).addClass('selected');
            $(this).removeClass('visible');

            $(this).addClass('current_language');
            $(this).removeClass('hidden_language');

            $(this).siblings('.lang_item').removeClass('current_language');
            $(this).siblings('.lang_item').addClass('hidden_language');

            if ($('.lang_item').length > 2) {
                $(this).closest('.lang_cont').children().removeClass('transform');
            }
        });
    }
};


function sliderHistory() {

    var slider = $('.sliderHistory')
    if (!slider.length) {
        return false
    }

    slider.slick({
        dots: true,
        arrows: true,
        infinite: true,
        slidesToShow: 1,
        slidesToScroll: 1,
        cssEase: 'linear',
        prevArrow: $('.left-arr'),
        nextArrow: $('.right-arr'),
        // autoplay: true,

    });

}


