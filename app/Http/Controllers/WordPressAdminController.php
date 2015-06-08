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

	public function ajax()
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



	public function pluginList()
	{
		global $menu;
		global $submenu;
		global $_wp_menu_nopriv;
		global $_wp_submenu_nopriv;
		global $plugins;
		global $status;
		global $page;

$filename = 'plugins.php';
		require_once base_path("wordpress/wp-admin/{$filename}");
//		$this->requireScriptWithAdmin('plugins.php');
	}

	public function pluginInstall()
	{
		global $menu;
		global $submenu;
		global $_wp_menu_nopriv;
		global $_wp_submenu_nopriv;
		global $tabs;
		global $tab;
		global $paged;
		global $wp_list_table;

$filename = 'plugin-install.php';
		require_once base_path("wordpress/wp-admin/{$filename}");
//		$this->requireScriptWithAdmin('plugin-install.php');
	}

	public function pluginEditor()
	{
		global $menu;
		global $submenu;
		global $_wp_menu_nopriv;
		global $_wp_submenu_nopriv;
//		global $file;
//		global $action;

		// MEMO global宣言を4つ追加。
$filename = 'plugin-editor.php';
		require_once base_path("wordpress/wp-admin/{$filename}");
//		$this->requireScriptWithAdmin('plugin-editor.php');
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
		global $menu;
		global $submenu;
		global $_wp_menu_nopriv;
		global $_wp_submenu_nopriv;
//		global $wpdb;
		global $taxonomy;

		require_once base_path("wordpress/wp-admin/edit-tags.php");
//		$this->requireScriptWithDB('edit-tags.php');
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

	public function import()
	{
		$this->requireScriptWithAdmin('import.php');
	}

	public function export()
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
		$this->requireScriptWithAdmin('options-permalink.php');
	}

	public function optionsEdit()
	{
		// MEMO 'options.php' に global $action; を追加した
		// MEMO 'options.php' に global $option_page; を追加した
		$this->requireScriptWithAdmin('options.php');
	}



	public function admin()
	{
		$this->requireScriptWithAdmin('admin.php');
	}



	public function download(\Illuminate\Http\Request $request)
	{
		info('Download: ' . $request->path());

		$path = base_path('wordpress/' . $request->path());
		$extension = pathinfo($path, PATHINFO_EXTENSION);
		$headers = [];
		$headers['Content-Type'] = $this->getMimeType($extension);
		return response()->download($path, null, $headers);
	}

	private function getMimeType($extension)
	{
		switch ($extension) {
		case 'css':
			return 'text/css';
		case 'js':
			return 'application/javascript';
		case 'svg':
			return 'image/svg+xml';
		}
	}



	private function requireScript($filename)
	{
		require_once base_path("wordpress/wp-admin/{$filename}");
	}

	private function requireScriptWithAdmin($filename)
	{
		global $menu;
		global $submenu;
		global $_wp_menu_nopriv;
		global $_wp_submenu_nopriv;

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
