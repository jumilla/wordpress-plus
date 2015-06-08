<?php namespace App\Http\Controllers;

class WordPressAdminController extends Controller
{

	public function index()
	{
		$this->requireScriptWithAdmin('index.php');
	}

	public function loadStyles()
	{
		$this->requireScript('load-styles.php');
	}

	public function loadScripts()
	{
		$this->requireScript('load-scripts.php');
	}



	public function admin()
	{
		$this->requireScriptWithAdmin('admin.php');
	}

	public function adminAjax()
	{
		$this->requireScript('admin-ajax.php');
	}



	public function updateCore()
	{
		$this->requireScriptWithAdmin('update-core.php');
	}

	public function update()
	{
		$this->requireScriptWithAdmin('update.php');
	}




	public function themeList()
	{
		$this->requireScriptWithAdmin('themes.php');
	}

	public function themeCustomize()
	{
		$this->requireScriptWithAdmin('customize.php');
	}

	public function themeWidgetList()
	{
		$this->requireScriptWithAdmin('widgets.php');
	}

	public function themeNavMenus()
	{
		$this->requireScriptWithAdmin('nav-menus.php', [
			'current_user',
		]);
	}

	public function themeFileList()
	{
		$this->requireScriptWithAdmin('theme-editor.php', [
//			'theme',
//			'action',
		]);
	}



	public function pluginList()
	{
		$this->requireScriptWithAdmin('plugins.php', [
			'plugins', 'status', 'page',
		]);
	}

	public function pluginInstall()
	{
		$this->requireScriptWithAdmin('plugin-install.php', [
			'tabs', 'tab', 'paged', 'wp_list_table',
		]);
	}

	public function pluginEditor()
	{
		// MEMO 'plugin-editor.php' にglobal宣言を4つ追加。
		$this->requireScriptWithAdmin('plugin-editor.php');
	}



	public function userList()
	{
		$this->requireScriptWithAdmin('users.php');
	}

	public function userNew()
	{
		$this->requireScriptWithAdmin('user-new.php');
	}

	public function userEdit()
	{
		$this->requireScriptWithAdmin('user-edit.php');
	}

	public function userProfile()
	{
		$this->requireScriptWithAdmin('profile.php');
	}



	public function postList()
	{
		$this->requireScriptWithAdmin('edit.php');
	}

	public function postNew()
	{
		$this->requireScriptWithDB('post-new.php');
	}

	public function postEdit()
	{
		// MEMO 'post.php' に global $action; を追加した
		$this->requireScriptWithDB('post.php');
	}

	public function tagList()
	{
		$this->requireScriptWithDB('edit-tags.php', [
			'taxonomy',
		]);
	}

	public function commentList()
	{
		$this->requireScriptWithComment('edit-comments.php');
	}

	public function commentEdit()
	{
		$this->requireScriptWithComment('comment.php');
	}



	public function mediaUpload()
	{
		$this->requireScriptWithAdmin('upload.php');
	}

	public function mediaAsyncUpload()
	{
		$this->requireScriptWithAdmin('async-upload.php');
	}

	public function mediaNew()
	{
		$this->requireScriptWithAdmin('media-new.php');
	}



	public function tools()
	{
		$this->requireScriptWithAdmin('tools.php');
	}

	public function toolPressThis()
	{
		$this->requireScriptWithAdmin('press-this.php');
	}

	public function toolImport()
	{
		$this->requireScriptWithAdmin('import.php');
	}

	public function toolExport()
	{
		$this->requireScriptWithDB('export.php');
	}



	public function optionsGeneral()
	{
		$this->requireScriptWithAdmin('options-general.php');
	}

	public function optionsWriting()
	{
		$this->requireScriptWithAdmin('options-writing.php');
	}

	public function optionsReading()
	{
		$this->requireScriptWithAdmin('options-reading.php');
	}

	public function optionsDiscussion()
	{
		$this->requireScriptWithAdmin('options-discussion.php');
	}

	public function optionsMedia()
	{
		$this->requireScriptWithAdmin('options-media.php');
	}

	public function optionsPermaLink()
	{
		$this->requireScriptWithAdmin('options-permalink.php', [
			'wp_rewrite',
		]);
	}

	public function optionsEdit()
	{
		// MEMO 'options.php' に global $action; を追加した
		// MEMO 'options.php' に global $option_page; を追加した
		$this->requireScriptWithAdmin('options.php');
	}



	public function about()
	{
		$this->requireScriptWithAdmin('about.php');
	}

	public function aboutCredits()
	{
		$this->requireScriptWithAdmin('credits.php');
	}

	public function aboutFreedoms()
	{
		$this->requireScriptWithAdmin('freedoms.php');
	}



	private function requireScript($filename)
	{
		require_once base_path("wordpress/wp-admin/{$filename}");
	}

	private function requireScriptWithAdmin($filename, array $globals = [])
	{
		global $menu;
		global $submenu;
		global $_wp_menu_nopriv;
		global $_wp_submenu_nopriv;

		foreach ($globals as $global) {
			global ${$global};
		}

		require_once base_path("wordpress/wp-admin/{$filename}");
	}

	private function requireScriptWithDB($filename)
	{
		global $menu;
		global $submenu;
		global $_wp_menu_nopriv;
		global $_wp_submenu_nopriv;
		global $wpdb;
//		global $action;
		// MEMO wp_reset_vars() を呼び出すと、再設定した変数はglobalの効果がなくなる。wp_reset_vars()の直後にglobal $action;を呼び出す必要がある。

		require_once base_path("wordpress/wp-admin/{$filename}");
	}

	private function requireScriptWithComment($filename)
	{
		global $menu;
		global $submenu;
		global $_wp_menu_nopriv;
		global $_wp_submenu_nopriv;
//		global $wpdb;
		global $post_id;
		global $comment;
		global $comment_status;

		require_once base_path("wordpress/wp-admin/{$filename}");
	}

}
