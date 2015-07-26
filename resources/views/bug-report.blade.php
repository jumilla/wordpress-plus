<!DOCTYPE html>
<html lang="ja">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width,initial-scale=1.0,minimum-scale=1.0">
	<title>@lang('messages.bug_report.page_title')</title>
	<meta name="description" content="">
	<meta name="keyword" content="">
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css">
	<link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css">
</head>

<body>
<div class="container">
<div class="row">
	<div class="page-header">
		<h1>@lang('messages.bug_report.header_title')</h1>
		<p>@lang('messages.bug_report.header_message')</p>
	</div>

	<form class="form form-horizontal" method="post" action="http://datacenter.jumilla.me/contact/report/wordpress+">
		<fieldset>
			<div class="form-group">
				<div class="col-sm-offset-2 col-sm-8">
					<button type="submit" class="btn btn-primary btn-lg btn-block">@lang('messages.bug_report.send_button')</button>
				</div>
		    </div>
	    </fieldset>

		<input type="hidden" name="occurred_at" value="{{ $occurred_at }}">
		<input type="hidden" name="occurred_ip" value="{{ $occurred_ip }}">
		<input type="hidden" name="occurred_platform" value="{{ json_encode($occurred_platform) }}">
		<input type="hidden" name="occurred_runtime" value="{{ $occurred_runtime }}">
		<input type="hidden" name="occurred_runtime_version" value="{{ $occurred_runtime_version }}">
		<input type="hidden" name="occurred_context" value="{{ json_encode($occurred_context) }}">
		<input type="hidden" name="application" value="{{ $application }}">
		<input type="hidden" name="build_version" value="{{ $build_version }}">
		<input type="hidden" name="build_signature" value="{{ $build_signature }}">
		<input type="hidden" name="source_file" value="{{ $source_file }}">
		<input type="hidden" name="source_line" value="{{ $source_line }}">
		<input type="hidden" name="source_class" value="{{ $source_class }}">
		<input type="hidden" name="source_function" value="{{ $source_function }}">
		<input type="hidden" name="exception_type" value="{{ $exception_type }}">
		<input type="hidden" name="exception_class" value="{{ $exception_class }}">
		<input type="hidden" name="exception_message" value="{{ $exception_message }}">
		<input type="hidden" name="exception_backtrace" value="{{ json_encode($exception_backtrace) }}">
	</form>
</div>
</div>
</body>
</html>
