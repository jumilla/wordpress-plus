<?php

$wp_backend_prefix = config('wordpress.url.backend_prefix');
$wp_site_prefix = config('wordpress.url.site_prefix');

$wp_namespace = 'App\Http\Controllers\WordPress';

if (!function_exists('add_backend_file_download_routes')) {
    function add_backend_file_download_routes($app)
    {
        $action = 'FileProvideController@downloadOnBackend';
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
}

if (!function_exists('add_site_file_download_routes')) {
    function add_site_file_download_routes($app)
    {
        $action = 'FileProvideController@downloadOnSite';

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
}

// Login Gate
$app->group(['prefix' => $wp_backend_prefix, 'namespace' => $wp_namespace], function ($app) {
    // ?action = ['postpass', 'logout', logout', 'lostpassword', 'retrievepassword', 'resetpass', 'rp', 'register']
    $app->get('wp-login.php', 'GateController@login');
    $app->post('wp-login.php', 'GateController@login');
});

// Shortcuts
$app->group(['prefix' => $wp_backend_prefix, 'namespace' => $wp_namespace], function ($app) {
    $admin_url = config('wordpress.url.backend').'/wp-admin/';

    // WordPress+ original routing
    if (config('wordpress.url.backend') != config('wordpress.url.site')) {
        $app->get('', function () use ($admin_url) { return redirect()->to($admin_url); });
    }

    // MEMO from wp_redirect_admin_locations() on 'wp-includes/canonical.php'
    $app->get('login', 'GateController@login');
    $app->get('admin', function () use ($admin_url) { return redirect()->to($admin_url); });
    $app->get('dashboard', function () use ($admin_url) { return redirect()->to($admin_url); });
});

// /wp-admin/network
$app->group(['prefix' => $wp_backend_prefix.'wp-admin/network', 'namespace' => $wp_namespace], function ($app) {
    $app->get('', 'SiteAdminController@siteDashboard');
    foreach (\App\Services\WordPress::siteAdminScripts() as $script) {
        $app->get($script, 'SiteAdminController@sitePage');
        $app->post($script, 'SiteAdminController@sitePage');
    }
});

// /wp-admin
$app->group(['prefix' => $wp_backend_prefix.'wp-admin', 'namespace' => $wp_namespace], function ($app) {
    //--- Dashboard ---//

    $app->get('', ['as' => 'wordpress.admin.dashboard', 'uses' => 'BlogAdminController@dashboard']);
    $app->get('index.php', function () {
        return redirect()->route('wordpress.admin.dashboard');
    });

    //--- Setup ---//

    $app->get('setup-config.php', 'SetupController@setupConfig');
    $app->post('setup-config.php', 'SetupController@setupConfig');
    $app->get('install.php', 'SetupController@setupInstall');
    $app->post('install.php', 'SetupController@setupInstall');

    //--- Updates ---//

    $app->get('update-core.php', 'BlogAdminController@updateCore');
    $app->post('update-core.php', 'BlogAdminController@updateCore');
    $app->get('update.php', 'BlogAdminController@update');
    $app->post('update.php', 'BlogAdminController@update');
    $app->get('upgrade.php', 'BlogAdminController@upgrade');

    //--- Admin ---//

    $app->get('admin.php', 'BlogAdminController@admin');
    $app->post('admin.php', 'BlogAdminController@admin');
    $app->get('admin-ajax.php', 'BlogAdminController@adminAjax');
    $app->post('admin-ajax.php', 'BlogAdminController@adminAjax');

    //--- Themes ---//

    $app->get('themes.php', 'BlogAdminController@themeList');
    $app->post('themes.php', 'BlogAdminController@themeList');
    $app->get('theme-install.php', 'BlogAdminController@themeInstall');
    $app->get('customize.php', 'BlogAdminController@themeCustomize');
    $app->get('widgets.php', 'BlogAdminController@themeWidgetList');
    $app->get('nav-menus.php', 'BlogAdminController@themeNavMenus');
    $app->post('nav-menus.php', 'BlogAdminController@themeNavMenus');
    $app->get('theme-editor.php', 'BlogAdminController@themeFileList');
    $app->post('theme-editor.php', 'BlogAdminController@themeFileList');

    //--- Plugins ---//

    $app->get('plugins.php', 'BlogAdminController@pluginList');
    $app->post('plugins.php', 'BlogAdminController@pluginList');
    $app->get('plugin-install.php', 'BlogAdminController@pluginInstall');
    $app->get('plugin-editor.php', 'BlogAdminController@pluginEditor');

    //--- Users ---//

    $app->get('users.php', 'BlogAdminController@userList');
    $app->post('users.php', 'BlogAdminController@userList');        // action=remove
    $app->get('user-new.php', 'BlogAdminController@userNew');
    $app->post('user-new.php', 'BlogAdminController@userNew');
    $app->get('user-edit.php', 'BlogAdminController@userEdit');
    $app->post('user-edit.php', 'BlogAdminController@userEdit');
    $app->get('profile.php', 'BlogAdminController@userProfile');
    $app->post('profile.php', 'BlogAdminController@userProfile');

    //--- Posts ---//

    $app->get('edit.php', 'BlogAdminController@postList');
    $app->post('edit.php', 'BlogAdminController@postList');
    $app->get('revision.php', 'BlogAdminController@postRevision');
    $app->get('post-new.php', 'BlogAdminController@postNew');
    $app->post('post-new.php', 'BlogAdminController@postNew');
    $app->get('post.php', 'BlogAdminController@postEdit');
    $app->post('post.php', 'BlogAdminController@postEdit');
    $app->get('edit-tags.php', 'BlogAdminController@tagList');
    $app->post('edit-tags.php', 'BlogAdminController@tagList');
    $app->get('edit-comments.php', 'BlogAdminController@commentList');
    $app->get('comment.php', 'BlogAdminController@commentEdit');
    $app->post('comment.php', 'BlogAdminController@commentEdit');

    //--- Media ---//

    $app->get('upload.php', 'BlogAdminController@mediaUpload');
    $app->post('async-upload.php', 'BlogAdminController@mediaAsyncUpload');
    $app->get('media-new.php', 'BlogAdminController@mediaNew');
        $app->get('media.php', 'BlogAdminController@mediaManagerOld');
        $app->get('media.php', 'BlogAdminController@mediaManagerOld');
        $app->get('media-upload.php', 'BlogAdminController@mediaUploadOld');
        $app->post('media-upload.php', 'BlogAdminController@mediaUploadOld');

    //--- Tools ---//

    $app->get('tools.php', 'BlogAdminController@tools');
    $app->get('press-this.php', 'BlogAdminController@toolPressThis');
    $app->get('import.php', 'BlogAdminController@toolImport');
    $app->get('export.php', 'BlogAdminController@toolExport');
    $app->get('network.php', 'BlogAdminController@toolNetwork');
    $app->post('network.php', 'BlogAdminController@toolNetwork');

    //--- Links ---//

    $app->get('link-manager.php', 'BlogAdminController@linkList');
    $app->get('link-add.php', 'BlogAdminController@linkAdd');
    $app->get('link.php', 'BlogAdminController@linkEdit');
    $app->post('link.php', 'BlogAdminController@linkEdit');

    //--- Settings ---//

    $app->get('options-general.php', 'BlogAdminController@optionsGeneral');
    $app->post('options-general.php', 'BlogAdminController@optionsGeneral');
    $app->get('options-writing.php', 'BlogAdminController@optionsWriting');
    $app->get('options-reading.php', 'BlogAdminController@optionsReading');
    $app->get('options-discussion.php', 'BlogAdminController@optionsDiscussion');
    $app->get('options-media.php', 'BlogAdminController@optionsMedia');
    $app->get('options-permalink.php', 'BlogAdminController@optionsPermaLink');
    $app->post('options-permalink.php', 'BlogAdminController@optionsPermaLink');
    $app->get('options.php', 'BlogAdminController@optionsEdit');
    $app->post('options.php', 'BlogAdminController@optionsEdit');

    //--- About ---//

    $app->get('about.php', 'BlogAdminController@about');
    $app->get('credits.php', 'BlogAdminController@aboutCredits');
    $app->get('freedoms.php', 'BlogAdminController@aboutFreedoms');

    //--- Multisite ---//

    $app->get('my-sites.php', 'BlogAdminController@multisiteList');

    //--- File Content Provider ---//

    $app->get('load-styles.php', 'FileProvideController@loadStyles');
    $app->get('load-scripts.php', 'FileProvideController@loadScripts');
    add_backend_file_download_routes($app);
});

// /wp-includes for backend
$app->group(['prefix' => $wp_backend_prefix.'wp-includes', 'namespace' => $wp_namespace], function ($app) {
    // irregular
    $app->get('js/tinymce/wp-mce-help.php', 'BlogAdminController@runPhpScript');
    $app->get('js/tinymce/wp-tinymce.php', 'BlogAdminController@runPhpScript');

    // provide files, about css, js, png, ...others.
    add_backend_file_download_routes($app);
});

// /wp-content for backend
$app->group(['prefix' => $wp_backend_prefix.'wp-content', 'namespace' => $wp_namespace], function ($app) {
    // provide files, about css, js, png, ...others.
    add_backend_file_download_routes($app);
});

// /wp-includes for site
// MEMO: theme 'twentyfifteen' using...
$app->group(['prefix' => $wp_site_prefix.'wp-includes', 'namespace' => $wp_namespace], function ($app) {
    add_site_file_download_routes($app);
});

// /wp-content for site
$app->group(['prefix' => $wp_site_prefix.'wp-content', 'namespace' => $wp_namespace], function ($app) {
    // provide files, about css, js, png, ...others.
    add_site_file_download_routes($app);
});

// Users
$app->group(['prefix' => $wp_backend_prefix, 'namespace' => $wp_namespace], function ($app) {
    $app->get('wp-signup.php', 'UserController@signup');
    $app->post('wp-signup.php', 'UserController@signup');
    $app->get('wp-activate.php', 'UserController@activate');

    $app->post('wp-comments-post.php', 'UserController@commentPost');
});

// Collaborations
$app->group(['prefix' => $wp_backend_prefix, 'namespace' => $wp_namespace], function ($app) {
    //--- Site information ---//
//		$app->get('?feed=rss2', 'TemplateController@provide');
//		$app->get('?feed=comments-rss2', 'TemplateController@provide');
    $app->get('wp-links-opml.php', 'CollaborationController@opml');

    $app->get('wp-mail.php', 'CollaborationController@mail');
    $app->get('wp-trackback.php', 'CollaborationController@trackback');

    $app->get('xmlrpc.php', 'CollaborationController@xmlrpc');
    $app->post('xmlrpc.php', 'CollaborationController@xmlrpc');
    $app->get('wp-cron.php', 'CollaborationController@cron');
    $app->post('wp-cron.php', 'CollaborationController@cron');
});

// Templates
$app->group(['prefix' => $wp_site_prefix, 'namespace' => $wp_namespace], function ($app) {
    $action = 'TemplateController@provide';

    $app->get('', $action);
    $app->post('', $action);
    $app->get('{p1}', $action);
    $app->post('{p1}', $action);
    $app->get('{p1}/{p2}', $action);
    $app->post('{p1}/{p2}', $action);
    $app->get('{p1}/{p2}/{p3}', $action);
    $app->post('{p1}/{p2}/{p3}', $action);
    $app->get('{p1}/{p2}/{p3}/{p4}', $action);
    $app->post('{p1}/{p2}/{p3}/{p4}', $action);
    $app->get('{p1}/{p2}/{p3}/{p4}/{p5}', $action);
    $app->post('{p1}/{p2}/{p3}/{p4}/{p5}', $action);
    $app->get('{p1}/{p2}/{p3}/{p4}/{p5}/{p6}', $action);
    $app->post('{p1}/{p2}/{p3}/{p4}/{p5}/{p6}', $action);
    $app->get('{p1}/{p2}/{p3}/{p4}/{p5}/{p6}/{p7}', $action);
    $app->post('{p1}/{p2}/{p3}/{p4}/{p5}/{p6}/{p7}', $action);
    $app->get('{p1}/{p2}/{p3}/{p4}/{p5}/{p6}/{p7}/{p8}', $action);
    $app->post('{p1}/{p2}/{p3}/{p4}/{p5}/{p6}/{p7}/{p8}', $action);
    $app->get('{p1}/{p2}/{p3}/{p4}/{p5}/{p6}/{p7}/{p8}/{p9}', $action);
    $app->post('{p1}/{p2}/{p3}/{p4}/{p5}/{p6}/{p7}/{p8}/{p9}', $action);
});
