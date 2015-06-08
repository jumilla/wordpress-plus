<?php namespace App\Http\Controllers;

/**
 *
 */
class WordPressAdminController extends Controller
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

		$this->requireAdminScriptWithMenu('index.php', [
			'wp_db_version',
		] /*+ app('wordpress.globals')*/);
	}



	public function setupConfig()
	{
		$this->requireAdminScript('setup-config.php');
	}

	public function setupInstall()
	{
		info('setupInstall');
		$this->requireAdminScript('install.php');
	}




	public function updateCore()
	{
		$this->requireAdminScriptWithMenu('update-core.php');
	}

	public function update()
	{
		$this->requireAdminScriptWithMenu('update.php');
	}




	public function admin()
	{
		$this->requireAdminScriptWithMenu('admin.php');
	}

	public function adminAjax()
	{
		$this->requireAdminScript('admin-ajax.php');
	}



	public function themeList()
	{
		$this->requireAdminScriptWithMenu('themes.php');
	}

	public function themeCustomize()
	{
		$this->requireAdminScriptWithMenu('customize.php', [
			'url', 'return',
		]/* + app('wordpress.globals')*/);
	}

	public function themeWidgetList()
	{
		$this->requireAdminScriptWithMenu('widgets.php');
	}

	public function themeNavMenus()
	{
		$this->requireAdminScriptWithMenu('nav-menus.php', [
			'current_user',
		]);
	}

	public function themeFileList()
	{
		$this->requireAdminScriptWithMenu('theme-editor.php', [
			'action', 'error', 'file', 'theme',
		]);
	}



	public function pluginList()
	{
		$this->requireAdminScriptWithMenu('plugins.php', [
			'plugins', 'status', 'page', 'user_ID',
		]);
	}

	public function pluginInstall()
	{
		$this->requireAdminScriptWithMenu('plugin-install.php', [
			'tabs', 'tab', 'paged', 'wp_list_table',
		]);
	}

	public function pluginEditor()
	{
		// MEMO 'plugin-editor.php' にglobal宣言を4つ追加。
		$this->requireAdminScriptWithMenu('plugin-editor.php');
	}



	public function userList()
	{
		$this->requireAdminScriptWithMenu('users.php');
	}

	public function userNew()
	{
		$this->requireAdminScriptWithMenu('user-new.php');
	}

	public function userEdit()
	{
		$this->requireAdminScriptWithMenu('user-edit.php');
	}

	public function userProfile()
	{
		$this->requireAdminScriptWithMenu('profile.php');
	}



	public function postList()
	{
		$this->requireAdminScriptWithMenu('edit.php');
	}

	public function postNew()
	{
		$this->requireAdminScriptWithMenu('post-new.php', [
			'is_IE',
		]);
	}

	public function postEdit()
	{
		$this->requireAdminScriptWithMenu('post.php', [
			'is_IE', 'action',
		]);	// app('wordpress.globals')
	}

	public function tagList()
	{
		$this->requireAdminScriptWithMenu('edit-tags.php', [
			'taxonomy',
		]);
	}

	public function commentList()
	{
		$this->requireAdminScriptWithMenu('edit-comments.php', [
			'post_id', 'comment', 'comment_status'
		]);
	}

	public function commentEdit()
	{
		$this->requireAdminScriptWithMenu('comment.php', [
			'post_id', 'comment', 'comment_status'
		]);
	}



	public function mediaUpload()
	{
		$this->requireAdminScriptWithMenu('upload.php');
	}

	public function mediaAsyncUpload()
	{
		$this->requireAdminScriptWithMenu('async-upload.php');
	}

	public function mediaNew()
	{
		$this->requireAdminScriptWithMenu('media-new.php');
	}



	public function tools()
	{
		$this->requireAdminScriptWithMenu('tools.php');
	}

	public function toolPressThis()
	{
		$this->requireAdminScriptWithMenu('press-this.php');
	}

	public function toolImport()
	{
		$this->requireAdminScriptWithMenu('import.php');
	}

	public function toolExport()
	{
		$this->requireAdminScriptWithMenu('export.php');
	}



	public function optionsGeneral()
	{
		$this->requireAdminScriptWithMenu('options-general.php');
	}

	public function optionsWriting()
	{
		$this->requireAdminScriptWithMenu('options-writing.php');
	}

	public function optionsReading()
	{
		$this->requireAdminScriptWithMenu('options-reading.php');
	}

	public function optionsDiscussion()
	{
		$this->requireAdminScriptWithMenu('options-discussion.php', [
			'user_email',
		]);
	}

	public function optionsMedia()
	{
		$this->requireAdminScriptWithMenu('options-media.php');
	}

	public function optionsPermaLink()
	{
		$this->requireAdminScriptWithMenu('options-permalink.php', [
			'wp_rewrite',
			'is_nginx',
		]);
	}

	public function optionsEdit()
	{
		$this->requireAdminScriptWithMenu('options.php', [
			'action', 'option_page',
		]);
	}



	public function about()
	{
		$this->requireAdminScriptWithMenu('about.php');
	}

	public function aboutCredits()
	{
		$this->requireAdminScriptWithMenu('credits.php');
	}

	public function aboutFreedoms()
	{
		$this->requireAdminScriptWithMenu('freedoms.php');
	}



	private function requireAdminScript($filename, array $globals = [])
	{
		// from wp-settings.php
		$globals = array_merge($globals, ['wp_version', 'wp_db_version', 'tinymce_version', 'required_php_version', 'required_mysql_version']);

		// additional
		$globals = array_merge($globals, ['wp_db']);

		$this->requireScript('wp-admin/' . $filename, $globals);
	}

	private function requireAdminScriptWithMenu($filename, array $globals = [])
	{
		$globals = array_merge($globals, ['menu', 'submenu', '_wp_menu_nopriv', '_wp_submenu_nopriv']);

		$this->requireAdminScript($filename, $globals);
	}

}
