@extends('layouts.master')
@section('title', 'Register')

@section('content')
<form method="POST" action="/password/reset">
	{!! csrf_field() !!}
	<input type="hidden" name="token" value="{{ $token }}">
	<div class="row">
		<div class="six columns required">
			<label for="email">email</label>
			<input class="u-full-width" value="{{ old('email') }}" type="email" placeholder="your@email.com" name="email" id="email">
		</div>
	</div>
	<div class="row"
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
			<input class="u-full-width" type="text" id="device_code" name="device_code">
		</div>
	</div>
	<label>
		<input type="checkbox">
		<span class="label-body">remember me</span>
	</label>
	<button class="button-primary" type="submit">
		Reset Password
	</button>

</form>
@endsection