@extends('layouts.master')
@section('title', 'Register')

@section('content')
<form method="POST" action="/register">
	{!! csrf_field() !!}
	<div class="row">
		<div class="six columns required">
			<label for="email">email</label>
			<input class="u-full-width" value="{{ old('email') }}" type="email" placeholder="your@email.com" name="email" id="email">
		</div>
	</div>
	<div class="row">
		<div class="six columns required">
			<label for="password">password</label>
			<input class="u-full-width" type="password" id="password" name="password">
		</div>
		<div class="six columns required">
			<label for="password_confirmation">password</label>
			<input class="u-full-width" type="password" id="password_confirmation" name="password_confirmation">
		</div>
	</div>
	<div class="row">
		<div class="six columns">
			<label for="name">name</label>
			<input class="u-full-width" value="{{ old('name') }}" type="text" placeholder="Dale Cooper" id="name" name="name">
		</div>
		<div class="six columns">
			<label for="device_code" class="optional">device code</label>
			<input class="u-full-width" type="text" id="device_code" name="device_code" placeholder="A7C9G2B0">
		</div>
	</div>
	<button class="button-primary" type="submit">
		Register
	</button>

</form>
@endsection