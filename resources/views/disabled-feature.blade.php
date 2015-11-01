<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width,initial-scale=1.0,minimum-scale=1.0">
    <title>@lang('messages.disabled_feature.page_title')</title>
    <meta name="description" content="">
    <meta name="keyword" content="">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css">
    <link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css">
</head>

<body>
<div class="container">
    <div class="row">
        <div class="page-header">
            <h1>{{ $title }}</h1>
            <p>{{ $message }}</p>
        </div>
    </div>

    <div class="row">
        <div>
            {!! $body !!}
        </div>
    </div>
</div>

<hr>

<div class="container">
    <div class="row">
        <p><a href="https://github.com/jumilla/wordpress-plus">WordPress+</a></p>
    </div>
</div>

</body>
</html>
