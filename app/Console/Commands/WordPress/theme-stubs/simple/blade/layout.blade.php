<!DOCTYPE html>
<html lang="{$lang}">
<head>
	<meta charset="UTF-8">
	<title>{{ bloginfo('name') }}</title>
	<meta name="description" content="">
	<meta name="keyword" content="">
@section('styles')
{{-- スタイルシート指定のプレースホルダです。 --}}
@show

@action('wp_head')
</head>

<body>
@yield ('content')

@action('wp_footer')

@section('scripts')
{{-- JavaScript指定のプレースホルダです。 --}}
@show
</body>
</html>
