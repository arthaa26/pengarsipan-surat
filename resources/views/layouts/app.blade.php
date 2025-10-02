<!DOCTYPE html>
<html lang="id">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>@yield('title', 'E-Arsip')</title>
</head>
<body>
	@include('partials.header')
	<div style="display:flex;min-height:80vh">
		@include('partials.sidebar')
		<main style="flex:1;padding:24px">
			@yield('content')
		</main>
	</div>
	@include('partials.footer')
</body>
</html>
