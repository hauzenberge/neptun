@extends('layout')

@section('content')
	<main>
		@include('components.contact', ['settings' => $settings, 'contacts' => $contacts, 'big' => true])
	</main>
@stop
