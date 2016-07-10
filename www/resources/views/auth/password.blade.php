@extends('layouts.master')
@section('title', 'Login')

@section('content')
<form method="POST" action="/password/email">
	{!! csrf_field() !!}
	<div class="row">
		<div class="six columns">
			<label for="email">EMAIL</label>
			<input class="u-full-width" value="{{ old('email') }}" type="email" placeholder="your@email.com" id="email">
		</div>
	</div>
	<button class="button-primary" type="submit">
		Send Password Reset Link
	</button>

</form>
@endsection