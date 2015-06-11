<?php namespace App\Http\Controllers\WordPress;

/**
 *
 */
class AdminController extends Controller
{

	public function __construct()
	{
		$this->middleware('wordpress.admin_environment_setup', [
			'except' => ['setupConfig', 'setupInstall'],
		]);
	}

	public function dashboard()
	{
		$request = app('request');

		// need trail '/'
		if (! ends_with($request->getPathInfo(), '/')) {
			// make valid url
			if (null !== $qs = $request->getQueryString()) {
				$qs = '?' . $qs;
			}
			$url = $request->getSchemeAndHttpHost() . $request->getBaseUrl() . $request->getPathInfo() . '/' . $qs;

			// redirect to valid url
			return redirect()->to($url);
		}

		$this->runAdminScriptWithMenu('index.php', [
			'wp_db_version',
		] /*+ app('wordpress.globals')*/);
	}



	public function setupConfig()
	{
		$this->runAdminScript('setup-config.php');
	}

	public function setupInstall()
	{
		info('setupInstall');
		$this->runAdminScript('install.php');
	}




	public function updateCore()
	{
		$this->runAdminScriptWithMenu('update-core.php');
	}

	public function update()
	{
		$this->runAdminScriptWithMenu('update.php');
	}




	public function admin()
	{
		$this->runAdminScriptWithMenu('admin.php');
	}

	public function adminAjax()
	{
		$this->runAdminScript('admin-ajax.php');
	}



	public function themeList()
	{
		$this->runAdminScriptWithMenu('themes.php');
	}

	public function themeInstall()
	{
		$this->runAdminScriptWithMenu('theme-install.php');
	}

	public function themeCustomize()
	{
		$this->runAdminScriptWithMenu('customize.php', [
			'url', 'return',
		]/* + app('wordpress.globals')*/);
	}

	public function themeWidgetList()
	{
		$this->runAdminScriptWithMenu('widgets.php');
	}

	public function themeNavMenus()
	{
		$this->runAdminScriptWithMenu('nav-menus.php', [
			'current_user',
		]);
	}

	public function themeFileList()
	{
		$this->runAdminScriptWithMenu('theme-editor.php', [
			'action', 'error', 'file', 'theme',
		]);
	}



	public function pluginList()
	{
		$this->runAdminScriptWithMenu('plugins.php', [
			'plugins', 'status', 'page', 'user_ID',
		]);
	}

	public function pluginInstall()
	{
		$this->runAdminScriptWithMenu('plugin-install.php', [
			'tabs', 'tab', 'paged', 'wp_list_table',
		]);
	}

	public function pluginEditor()
	{
		$this->runAdminScriptWithMenu('plugin-editor.php', [
			'action', 'error', 'file', 'plugin',
		]);
	}



	public function userList()
	{
		$this->runAdminScriptWithMenu('users.php');
	}

	public function userNew()
	{
		$this->runAdminScriptWithMenu('user-new.php');
	}

	public function userEdit()
	{
		$this->runAdminScriptWithMenu('user-edit.php');
	}

	public function userProfile()
	{
		$this->runAdminScriptWithMenu('profile.php');
	}



	public function postList()
	{
		$this->runAdminScriptWithMenu('edit.php', [
//			'typenow',
		]);
	}

	public function postNew()
	{
		$this->runAdminScriptWithMenu('post-new.php', [
			'is_IE', 'title',
		]);
	}

	public function postEdit()
	{
		$this->runAdminScriptWithMenu('post.php', [
			'is_IE', 'action',
		]);	// app('wordpress.globals')
	}

	public function tagList()
	{
		$this->runAdminScriptWithMenu('edit-tags.php', [
			'title', 'taxonomy',
		]);
	}

	public function commentList()
	{
		$this->runAdminScriptWithMenu('edit-comments.php', [
			'title', 'post_id', 'comment', 'comment_status'
		]);
	}

	public function commentEdit()
	{
		$this->runAdminScriptWithMenu('comment.php', [
			'post_id', 'comment', 'comment_status'
		]);
	}



	public function mediaUpload()
	{
		$this->runAdminScriptWithMenu('upload.php');
	}

	public function mediaAsyncUpload()
	{
		$this->runAdminScriptWithMenu('async-upload.php');
	}

	public function mediaNew()
	{
		$this->runAdminScriptWithMenu('media-new.php');
	}



	public function tools()
	{
		$this->runAdminScriptWithMenu('tools.php');
	}

	public function toolPressThis()
	{
		$this->runAdminScriptWithMenu('press-this.php');
	}

	public function toolImport()
	{
		$this->runAdminScriptWithMenu('import.php');
	}

	public function toolExport()
	{
		$this->runAdminScriptWithMenu('export.php');
	}



	public function optionsGeneral()
	{
		$this->runAdminScriptWithMenu('options-general.php');
	}

	public function optionsWriting()
	{
		$this->runAdminScriptWithMenu('options-writing.php');
	}

	public function optionsReading()
	{
		$this->runAdminScriptWithMenu('options-reading.php');
	}

	public function optionsDiscussion()
	{
		$this->runAdminScriptWithMenu('options-discussion.php', [
			'user_email',
		]);
	}

	public function optionsMedia()
	{
		$this->runAdminScriptWithMenu('options-media.php');
	}

	public function optionsPermaLink()
	{
		$this->runAdminScriptWithMenu('options-permalink.php', [
			'wp_rewrite',
			'is_nginx',
		]);
	}

	public function optionsEdit()
	{
		$this->runAdminScriptWithMenu('options.php', [
			'action', 'option_page',
		]);
	}



	public function linkList()
	{
		$this->runAdminScriptWithMenu('link-manager.php', [
		]);
	}

	public function linkAdd()
	{
		$this->runAdminScriptWithMenu('link-add.php', [
		]);
	}

	public function linkEdit()
	{
		$this->runAdminScriptWithMenu('link.php', [
			'action', 'cat_id', 'link_id',
		]);
	}



	public function about()
	{
		$this->runAdminScriptWithMenu('about.php');
	}

	public function aboutCredits()
	{
		$this->runAdminScriptWithMenu('credits.php');
	}

	public function aboutFreedoms()
	{
		$this->runAdminScriptWithMenu('freedoms.php');
	}



	private function runAdminScript($filename, array $globals = [])
	{
		// from wp-settings.php
		$globals = array_merge($globals, ['wp_version', 'wp_db_version', 'tinymce_version', 'required_php_version', 'required_mysql_version']);

		// additional
		$globals = array_merge($globals, ['wp_db']);

		$this->runScript('wp-admin/' . $filename, $globals);
	}

	private function runAdminScriptWithMenu($filename, array $globals = [])
	{
		$globals = array_merge($globals, ['menu', 'submenu', '_wp_menu_nopriv', '_wp_submenu_nopriv']);

		// for sort_menu() in wp-admin/includes/menu.php
		$globals = array_merge($globals, ['menu_order', 'default_menu_order']);

		// for wp-admin/includes/plugin.php
		$globals = array_merge($globals, ['_wp_last_object_menu', '_wp_last_utility_menu']);

		$this->runAdminScript($filename, $globals);
	}

}
