<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

$app->get('wp-login.php', function() {
	global $user_login;
	global $error;
	require base_path('wordpress/wp-login.php');
});
$app->post('wp-login.php', function() {
	global $user_login;
	global $error;
	require base_path('wordpress/wp-login.php');
});

$app->group(['prefix' => 'wp-admin', 'namespace' => 'App\Http\Controllers'], function($app) {
	$app->get('', ['as' => 'admin.dashboard', 'uses' => 'WordPressAdminController@dashboard']);
	$app->get('index.php', function () {
		return redirect()->route('admin.dashboard');
	});

	//--- Setup ---//

	$app->get('setup-config.php', 'WordPressAdminController@setupConfig');
	$app->post('setup-config.php', 'WordPressAdminController@setupConfig');
	$app->get('install.php', 'WordPressAdminController@setupInstall');
	$app->post('install.php', 'WordPressAdminController@setupInstall');

	//--- Updates ---//

	$app->get('update-core.php', 'WordPressAdminController@updateCore');
	$app->post('update-core.php', 'WordPressAdminController@updateCore');
	$app->get('update.php', 'WordPressAdminController@update');
	$app->post('update.php', 'WordPressAdminController@update');

	//--- Admin ---//

	$app->get('admin.php', 'WordPressAdminController@admin');
	$app->post('admin.php', 'WordPressAdminController@admin');
	$app->get('admin-ajax.php', 'WordPressAdminController@adminAjax');
	$app->post('admin-ajax.php', 'WordPressAdminController@adminAjax');

	//--- Themes ---//

	$app->get('themes.php', 'WordPressAdminController@themeList');
	$app->get('theme-install.php', 'WordPressAdminController@themeInstall');
	$app->get('customize.php', 'WordPressAdminController@themeCustomize');
	$app->get('widgets.php', 'WordPressAdminController@themeWidgetList');
	$app->get('nav-menus.php', 'WordPressAdminController@themeNavMenus');
	$app->post('nav-menus.php', 'WordPressAdminController@themeNavMenus');
	$app->get('theme-editor.php', 'WordPressAdminController@themeFileList');
	$app->post('theme-editor.php', 'WordPressAdminController@themeFileList');

	//--- Plugins ---//

	$app->get('plugins.php', 'WordPressAdminController@pluginList');
	$app->post('plugins.php', 'WordPressAdminController@pluginList');
	$app->get('plugin-install.php', 'WordPressAdminController@pluginInstall');
	$app->get('plugin-editor.php', 'WordPressAdminController@pluginEditor');

	//--- Users ---//

	$app->get('users.php', 'WordPressAdminController@userList');
	$app->get('user-new.php', 'WordPressAdminController@userNew');
	$app->post('user-new.php', 'WordPressAdminController@userNew');
	$app->get('user-edit.php', 'WordPressAdminController@userEdit');
	$app->post('user-edit.php', 'WordPressAdminController@userEdit');
	$app->get('profile.php', 'WordPressAdminController@userProfile');
	$app->post('profile.php', 'WordPressAdminController@userProfile');

	//--- Posts ---//

	$app->get('edit.php', 'WordPressAdminController@postList');
	$app->get('post-new.php', 'WordPressAdminController@postNew');
	$app->post('post-new.php', 'WordPressAdminController@postNew');
	$app->get('post.php', 'WordPressAdminController@postEdit');
	$app->post('post.php', 'WordPressAdminController@postEdit');
	$app->get('edit-tags.php', 'WordPressAdminController@tagList');
	$app->post('edit-tags.php', 'WordPressAdminController@tagList');
	$app->get('edit-comments.php', 'WordPressAdminController@commentList');
	$app->get('comment.php', 'WordPressAdminController@commentEdit');
	$app->post('comment.php', 'WordPressAdminController@commentEdit');

	//--- Media ---//

	$app->get('upload.php', 'WordPressAdminController@mediaUpload');
	$app->post('async-upload.php', 'WordPressAdminController@mediaAsyncUpload');
	$app->get('media-new.php', 'WordPressAdminController@mediaNew');

	//--- Tools ---//

	$app->get('tools.php', 'WordPressAdminController@tools');
	$app->get('press-this.php', 'WordPressAdminController@toolPressThis');
	$app->get('import.php', 'WordPressAdminController@toolImport');
	$app->get('export.php', 'WordPressAdminController@toolExport');

	//--- Settings ---//

	$app->get('options-general.php', 'WordPressAdminController@optionsGeneral');
	$app->get('options-writing.php', 'WordPressAdminController@optionsWriting');
	$app->get('options-reading.php', 'WordPressAdminController@optionsReading');
	$app->get('options-discussion.php', 'WordPressAdminController@optionsDiscussion');
	$app->get('options-media.php', 'WordPressAdminController@optionsMedia');
	$app->get('options-permalink.php', 'WordPressAdminController@optionsPermaLink');
	$app->post('options-permalink.php', 'WordPressAdminController@optionsPermaLink');
	$app->post('options.php', 'WordPressAdminController@optionsEdit');

	//--- About ---//

	$app->get('about.php', 'WordPressAdminController@about');
	$app->get('credits.php', 'WordPressAdminController@aboutCredits');
	$app->get('freedoms.php', 'WordPressAdminController@aboutFreedoms');

	//--- File Content Provider ---//

	$app->get('load-styles.php', 'FileProvideController@loadStyles');
	$app->get('load-scripts.php', 'FileProvideController@loadScripts');
	add_file_download_routes($app);
});

$app->group(['prefix' => 'wp-includes', 'namespace' => 'App\Http\Controllers'], function($app) {
	$app->get('js/tinymce/wp-mce-help.php', function() { require base_path('wordpress/' . app('request')->path()); });
	$app->get('js/tinymce/wp-tinymce.php', function() { require base_path('wordpress/' . app('request')->path()); });
	add_file_download_routes($app);
});

$app->group(['prefix' => 'wp-content', 'namespace' => 'App\Http\Controllers'], function($app) {
	add_file_download_routes($app);
});

function add_file_download_routes($app)
{
	$action = 'FileProvideController@download';

	$app->get('{f1}', $action);
	$app->get('{f1}/{f2}', $action);
	$app->get('{f1}/{f2}/{f3}', $action);
	$app->get('{f1}/{f2}/{f3}/{f4}', $action);
	$app->get('{f1}/{f2}/{f3}/{f4}/{f5}', $action);
	$app->get('{f1}/{f2}/{f3}/{f4}/{f5}/{f6}', $action);
	$app->get('{f1}/{f2}/{f3}/{f4}/{f5}/{f6}/{f7}', $action);
	$app->get('{f1}/{f2}/{f3}/{f4}/{f5}/{f6}/{f7}/{f8}', $action);
	$app->get('{f1}/{f2}/{f3}/{f4}/{f5}/{f6}/{f7}/{f8}/{f9}', $action);
}

$app->group(['namespace' => 'App\Http\Controllers'], function($app) {
	$app->get('wp-activate.php', function() {
		require base_path('wordpress/wp-activate.php');
	});

	$app->post('wp-comments-post.php', function() {
		require base_path('wordpress/wp-comments-post.php');
	});

	$app->get('wp-cron.php', function() {
		require base_path('wordpress/wp-cron.php');
	});

	$app->get('wp-link-opml.php', function() {
		require base_path('wordpress/wp-link-opml.php');
	});

	$app->get('wp-mail.php', function() {
		require base_path('wordpress/wp-mail.php');
	});

	$app->get('wp-signup.php', function() {
		require base_path('wordpress/wp-signup.php');
	});

	$app->get('wp-trackback.php', 'WordPressCollaborationController@trackback');
	$app->get('xmlrpc.php', 'WordPressCollaborationController@xmlrpc');
	$app->post('xmlrpc.php', 'WordPressCollaborationController@xmlrpc');
});

$app->group(['namespace' => 'App\Http\Controllers'], function($app) {
	$action = 'WordPressTemplateController@provide';

	$app->get('', $action);
	$app->post('', $action);
	$app->get('{f1}', $action);
	$app->get('{f1}/{f2}', $action);
	$app->get('{f1}/{f2}/{f3}', $action);
	$app->get('{f1}/{f2}/{f3}/{f4}', $action);
	$app->get('{f1}/{f2}/{f3}/{f4}/{f5}', $action);
	$app->get('{f1}/{f2}/{f3}/{f4}/{f5}/{f6}', $action);
	$app->get('{f1}/{f2}/{f3}/{f4}/{f5}/{f6}/{f7}', $action);
	$app->get('{f1}/{f2}/{f3}/{f4}/{f5}/{f6}/{f7}/{f8}', $action);
	$app->get('{f1}/{f2}/{f3}/{f4}/{f5}/{f6}/{f7}/{f8}/{f9}', $action);
});
