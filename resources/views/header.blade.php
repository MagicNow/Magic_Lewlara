<!DOCTYPE html>

<!--[if lt IE 7 ]> <html lang="pt-br" class="ie6 lte6 lte7 lte8 lte9"> <![endif]-->
<!--[if IE 7 ]> <html lang="pt-br" class="ie7 lte7 lte8 lte9"> <![endif]-->
<!--[if IE 8 ]> <html lang="pt-br" class="ie8 lte8 lte9"> <![endif]-->
<!--[if IE 9 ]> <html lang="pt-br" class="ie9 lte9"> <![endif]-->
<!--[if (gt IE 9)|!(IE)]><!--> <html lang="pt-br"> <!--<![endif]-->



<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta name="csrf-token" content="<?php echo csrf_token(); ?>">
	@if (isset($header_title))
		<title>{{ $header_title }}</title>
	@else 
		<title>Lewlara</title>
	@endif
	

	<link href='http://fonts.googleapis.com/css?family=Cuprum:400,700' rel='stylesheet' type='text/css'>


	
	@if (!empty($css_antes) && is_array($css_antes))
		@foreach ($css_antes as $v)
			<link href="{{$v['href']}}" rel="stylesheet">
		@endforeach
	@endif
	
	<link href="{{ url('/') }}{{ elixir('css/all.css') }}" rel="stylesheet">

	
	@if (!empty($css_depois) && is_array($css_depois)) 
		@foreach ($css_depois as $v)
			<link href="{{ $v['href']}}" rel="stylesheet">
		@endforeach
	@endif
	

	
	
	<!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
	<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
	<!--[if lt IE 9]>
		<script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
		<script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
	<![endif]-->
</head>
<body>