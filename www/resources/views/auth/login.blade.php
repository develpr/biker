@extends('layouts.master')
@section('title', 'Login')

@section('content')
<form method="POST" action="/login">
	{!! csrf_field() !!}
	<div class="row">
		<div class="twelve columns">
			<p>
				Login with your email and password. If you've forgotten your password, you can <a href="{{url('/reset')}}">reset your password here.</a>
			</p>
		</div>
	</div>
	<div class="row">
		<div class="six columns">
			<label for="email">EMAIL</label>
			<input class="u-full-width" value="{{ old('email') }}" type="email" placeholder="your@email.com" id="email">
		</div>
		<div class="six columns">
			<label for="password">PASSWORD</label>
			<input class="u-full-width" type="password" id="password">
		</div>
	</div>
	<label>
		<input type="checkbox">
		<span class="label-body">remember me</span>
	</label>
	<button class="button-primary" type="submit">
		Login
	</button>

</form>
@endsection