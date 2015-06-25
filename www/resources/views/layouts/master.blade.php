<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<title>@yield('title', "Hello!") | Alexa, Find Me A Bike</title>
	<link href='http://fonts.googleapis.com/css?family=Euphoria+Script' rel='stylesheet' type='text/css'>
	<link rel="stylesheet" href="css/master.css">
	<!--[if IE]>
	<script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script>
	<![endif]-->
	<script src="https://code.jquery.com/jquery-2.1.4.min.js"></script>
	<script>
		$(window).scroll(function() {
			if ($(document).scrollTop() > 30) {
				$('header').addClass('shrink');
			} else {
				$('header').removeClass('shrink');
			}
		});
	</script>
</head>

<body id="home">
	<div id="header-container">
		<header class="container">
			<h1 class="row"><a href="/">Alexa, Find Me a <img src="/img/bike_basket.svg" class="bike-icon" alt="An Old Bike" /></a></h1>
			<ul class="row navigation">
				<li><a href="/">About</a></li>
				@if(!Auth::check())
				<li><a href="/register">Register</a></li>
				<li><a href="/login">Sign in</a></li>
				@else
				<li><a href="/logout">Log out</a></li>
				@endif
			</ul>
		</header>
	</div>
	<div class="container" id="content">
		@yield('content')
	</div>
	<div class="container" id="footer">
		<ul class="row">
			<li class="three columns"><a href="mailto:kevin@develpr.com">Contact</a></li>
			<li class="three columns"><a href="/privacy-policy">Privacy Policy</a></li>
			<li class="three columns"><a href="https://www.github.com/develpr/alexa-app">alexa-app</a></li>
			<li class="three columns"><a href="http://www.develpr.com">Hire me!</a></li>
		</ul>
	</div>

</body>
</html>