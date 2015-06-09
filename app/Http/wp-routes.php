<?php

$wp_prefix = env('WP_ROOT', '/');

$wp_namespace = 'App\Http\Controllers\WordPress';



// Login Gate
$app->group(['prefix' => $wp_prefix, 'namespace' => $wp_namespace], function ($app) {
	$app->get('wp-login.php', 'GateController@login');
	$app->post('wp-login.php', 'GateController@login');
});

// /wp-admin
$app->group(['prefix' => $wp_prefix . 'wp-admin', 'namespace' => $wp_namespace], function ($app) {
	//--- Dashboard ---//

	$app->get('', ['as' => 'wordpress.admin.dashboard', 'uses' => 'AdminController@dashboard']);
	$app->get('index.php', function () {
		return redirect()->route('wordpress.admin.dashboard');
	});

	//--- Setup ---//

	$app->get('setup-config.php', 'AdminController@setupConfig');
	$app->post('setup-config.php', 'AdminController@setupConfig');
	$app->get('install.php', 'AdminController@setupInstall');
	$app->post('install.php', 'AdminController@setupInstall');

	//--- Updates ---//

	$app->get('update-core.php', 'AdminController@updateCore');
	$app->post('update-core.php', 'AdminController@updateCore');
	$app->get('update.php', 'AdminController@update');
	$app->post('update.php', 'AdminController@update');

	//--- Admin ---//

	$app->get('admin.php', 'AdminController@admin');
	$app->post('admin.php', 'AdminController@admin');
	$app->get('admin-ajax.php', 'AdminController@adminAjax');
	$app->post('admin-ajax.php', 'AdminController@adminAjax');

	//--- Themes ---//

	$app->get('themes.php', 'AdminController@themeList');
	$app->get('theme-install.php', 'AdminController@themeInstall');
	$app->get('customize.php', 'AdminController@themeCustomize');
	$app->get('widgets.php', 'AdminController@themeWidgetList');
	$app->get('nav-menus.php', 'AdminController@themeNavMenus');
	$app->post('nav-menus.php', 'AdminController@themeNavMenus');
	$app->get('theme-editor.php', 'AdminController@themeFileList');
	$app->post('theme-editor.php', 'AdminController@themeFileList');

	//--- Plugins ---//

	$app->get('plugins.php', 'AdminController@pluginList');
	$app->post('plugins.php', 'AdminController@pluginList');
	$app->get('plugin-install.php', 'AdminController@pluginInstall');
	$app->get('plugin-editor.php', 'AdminController@pluginEditor');

	//--- Users ---//

	$app->get('users.php', 'AdminController@userList');
	$app->get('user-new.php', 'AdminController@userNew');
	$app->post('user-new.php', 'AdminController@userNew');
	$app->get('user-edit.php', 'AdminController@userEdit');
	$app->post('user-edit.php', 'AdminController@userEdit');
	$app->get('profile.php', 'AdminController@userProfile');
	$app->post('profile.php', 'AdminController@userProfile');

	//--- Posts ---//

	$app->get('edit.php', 'AdminController@postList');
	$app->get('post-new.php', 'AdminController@postNew');
	$app->post('post-new.php', 'AdminController@postNew');
	$app->get('post.php', 'AdminController@postEdit');
	$app->post('post.php', 'AdminController@postEdit');
	$app->get('edit-tags.php', 'AdminController@tagList');
	$app->post('edit-tags.php', 'AdminController@tagList');
	$app->get('edit-comments.php', 'AdminController@commentList');
	$app->get('comment.php', 'AdminController@commentEdit');
	$app->post('comment.php', 'AdminController@commentEdit');

	//--- Media ---//

	$app->get('upload.php', 'AdminController@mediaUpload');
	$app->post('async-upload.php', 'AdminController@mediaAsyncUpload');
	$app->get('media-new.php', 'AdminController@mediaNew');

	//--- Tools ---//

	$app->get('tools.php', 'AdminController@tools');
	$app->get('press-this.php', 'AdminController@toolPressThis');
	$app->get('import.php', 'AdminController@toolImport');
	$app->get('export.php', 'AdminController@toolExport');

	//--- Links ---//

	$app->get('link-manager.php', 'AdminController@linkList');
	$app->get('link-add.php', 'AdminController@linkAdd');
	$app->post('link.php', 'AdminController@linkEdit');

	//--- Settings ---//

	$app->get('options-general.php', 'AdminController@optionsGeneral');
	$app->get('options-writing.php', 'AdminController@optionsWriting');
	$app->get('options-reading.php', 'AdminController@optionsReading');
	$app->get('options-discussion.php', 'AdminController@optionsDiscussion');
	$app->get('options-media.php', 'AdminController@optionsMedia');
	$app->get('options-permalink.php', 'AdminController@optionsPermaLink');
	$app->post('options-permalink.php', 'AdminController@optionsPermaLink');
	$app->post('options.php', 'AdminController@optionsEdit');

	//--- About ---//

	$app->get('about.php', 'AdminController@about');
	$app->get('credits.php', 'AdminController@aboutCredits');
	$app->get('freedoms.php', 'AdminController@aboutFreedoms');

	//--- File Content Provider ---//

	$app->get('load-styles.php', 'FileProvideController@loadStyles');
	$app->get('load-scripts.php', 'FileProvideController@loadScripts');
	add_file_download_routes($app);
});

// /wp-includes
$app->group(['prefix' => $wp_prefix . 'wp-includes', 'namespace' => $wp_namespace], function ($app) {
	// irregular
	$app->get('js/tinymce/wp-mce-help.php', function () { require wordpress_path(app('request')->path()); });
	$app->get('js/tinymce/wp-tinymce.php', function () { require wordpress_path(app('request')->path()); });

	// provide files, about css, js, png, ...others.
	add_file_download_routes($app);
});

// /wp-content
$app->group(['prefix' => $wp_prefix . 'wp-content', 'namespace' => $wp_namespace], function($app) {
	// provide files, about css, js, png, ...others.
	add_file_download_routes($app);
});

// Users
$app->group(['prefix' => $wp_prefix, 'namespace' => $wp_namespace], function($app) {
	$app->get('wp-signup.php', 'UserController@signup');
	$app->post('wp-signup.php', 'UserController@signup');
	$app->get('wp-activate.php', 'UserController@activate');

	$app->post('wp-comments-post.php', 'UserController@commentPost');
});

// Collaborations
$app->group(['prefix' => $wp_prefix, 'namespace' => $wp_namespace], function ($app) {
	//--- Site information ---//
//		$app->get('?feed=rss2', 'TemplateController@provide');
//		$app->get('?feed=comments-rss2', 'TemplateController@provide');
	$app->get('wp-links-opml.php', 'CollaborationController@opml');

	$app->get('wp-mail.php', 'CollaborationController@mail');
	$app->get('wp-trackback.php', 'CollaborationController@trackback');

	$app->get('xmlrpc.php', 'CollaborationController@xmlrpc');
	$app->post('xmlrpc.php', 'CollaborationController@xmlrpc');
	$app->get('wp-cron.php', 'CollaborationController@cron');
});

// Templates
$app->group(['prefix' => $wp_prefix, 'namespace' => $wp_namespace], function ($app) {
	$action = 'TemplateController@provide';

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
