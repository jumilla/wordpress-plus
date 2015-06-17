<!DOCTYPE html>
<html lang="ja">
<head>
	<meta charset="UTF-8">
	<title>{{ bloginfo('name') }}</title>
{!! wp_head() !!}
</head>
<body>
@yield ('content')

{!! wp_footer() !!}
</body>
</html>
