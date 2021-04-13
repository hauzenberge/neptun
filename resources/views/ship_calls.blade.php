@extends('layout')

@section('content')
	<main>
        	<div class="container video-bg" style="margin: auto;">
                <div class="row">
		            	@if($page['video'])
		            		 {!! $page['video'] !!}
		            	@endif 
            	 </div>
               </div>
             <div id="table-animate" class="table-content" style="height:0;">
	                <div class="container">
		                <table>
		                    <thead>
								<tr>
									<th>VESSEL</th>
									<th>DWT</th>
									<th>LOA & Breath</th>
									<th>Cargo</th>
									<th>Destination</th>
									<th>ETA</th>
									<th>ETB</th>
									<th>ETD</th>
								</tr>
		                    </thead>
		                    <tbody>
								@foreach($data as $item)
									<!-- -->
									<tr>
										<td data-label="VESSEL">{{$item->vessel}}</td>
										<td data-label="DWT">{{$item->dwt}}</td>
										<td data-label="LOA & Breath">{{$item->loa}}</td>
										<td data-label="Cargo">{{$item->cargo}}</td>
										<td data-label="Destination">{{$item->destination}}</td>
										<td data-label="ETA">{{$item->eta}}</td>
										<td data-label="ETB">{{$item->etd}}</td>
										<td data-label="ETD">{{$item->ets}}</td>
									</tr>
									<!-- -->
								@endforeach
		                    </tbody>
		                </table>
	             	</div>
        	</div>
        <script src="{{asset('/js/jquery-3.1.1.min.js')}}"></script>
	    <script> 
	        jQuery('#table-animate').hide();
	        setTimeout(function(){
	            jQuery('#table-animate').fadeIn( 1000 );
	            jQuery('#table-animate tr td:nth-of-type(1)').css('transform', 'translateX(0px)');
	        }, 2000);
	        setTimeout(function(){
	            jQuery('#table-animate tr td:nth-of-type(2)').css('transform', 'translateX(0px)');
	        }, 3000);
	        setTimeout(function(){
	            jQuery('#table-animate tr td:nth-of-type(3)').css('transform', 'translateX(0px)');
	        }, 3300);
	        setTimeout(function(){
	            jQuery('#table-animate tr td:nth-of-type(4)').css('transform', 'translateX(0px)');
	        }, 3600);
	        setTimeout(function(){
	            jQuery('#table-animate tr td:nth-of-type(5)').css('transform', 'translateX(0px)');
	        }, 3900);
	        setTimeout(function(){
	            jQuery('#table-animate tr td:nth-of-type(6)').css('transform', 'translateX(0px)');
	        }, 4200);
	        setTimeout(function(){
	            jQuery('#table-animate tr td:nth-of-type(7)').css('transform', 'translateX(0px)');
	        }, 4500);
	        setTimeout(function(){
	            jQuery('#table-animate tr td:nth-of-type(8)').css('transform', 'translateX(0px)');
	        }, 4800);
	    </script>
    </main>
@stop
