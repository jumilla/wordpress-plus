<?php

namespace App\Http\Controllers\WordPress;

/**
 *
 */
class BlogAdminController extends Controller
{
    public function __construct()
    {
        $this->middleware('wordpress.blog_admin_bootstrap');
    }

    public function dashboard()
    {
        $request = app('request');

        // need trail '/'
        if (!ends_with($request->getPathInfo(), '/')) {
            // make valid url
            $url = $request->getSchemeAndHttpHost().$request->getBaseUrl().$request->getPathInfo().'/';

            $qs = $request->getQueryString();
            if ($qs !== null) {
                $url .= '?'.$qs;
            }

            // redirect to valid url
            return redirect()->to($url);
        }

        $this->runAdminScriptWithMenu('index.php');
    }

    public function updateCore()
    {
        $this->runAdminScriptWithMenu('update-core.php');
    }

    public function update()
    {
        $this->runAdminScriptWithMenu('update.php');
    }

    public function upgrade()
    {
        $this->runAdminScriptWithMenu('upgrade.php');
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
        $this->runAdminScriptWithMenu('customize.php');
    }

    public function themeWidgetList()
    {
        $this->runAdminScriptWithMenu('widgets.php');
    }

    public function themeNavMenus()
    {
        $this->runAdminScriptWithMenu('nav-menus.php');
    }

    public function themeFileList()
    {
        $this->runAdminScriptWithMenu('theme-editor.php');
    }

    public function pluginList()
    {
        $this->runAdminScriptWithMenu('plugins.php');
    }

    public function pluginInstall()
    {
        $this->runAdminScriptWithMenu('plugin-install.php');
    }

    public function pluginEditor()
    {
        $this->runAdminScriptWithMenu('plugin-editor.php');
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
        $this->runAdminScriptWithMenu('edit.php');
    }

    public function postRevision()
    {
        $this->runAdminScriptWithMenu('revision.php');
    }

    public function postNew()
    {
        $this->runAdminScriptWithMenu('post-new.php');
    }

    public function postEdit()
    {
        $this->runAdminScriptWithMenu('post.php');
    }

    public function tagList()
    {
        $this->runAdminScriptWithMenu('edit-tags.php');
    }

    public function commentList()
    {
        $this->runAdminScriptWithMenu('edit-comments.php');
    }

    public function commentEdit()
    {
        $this->runAdminScriptWithMenu('comment.php');
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

    public function mediaManagerOld()
    {
        $this->runAdminScriptWithMenu('media.php');
    }

    public function mediaUploadOld()
    {
        $this->runAdminScriptWithMenu('media-upload.php');
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

    public function toolNetwork()
    {
        $this->runAdminScriptWithMenu('network.php');
    }

    public function linkList()
    {
        $this->runAdminScriptWithMenu('link-manager.php');
    }

    public function linkAdd()
    {
        $this->runAdminScriptWithMenu('link-add.php');
    }

    public function linkEdit()
    {
        $this->runAdminScriptWithMenu('link.php');
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
        $this->runAdminScriptWithMenu('options-discussion.php');
    }

    public function optionsMedia()
    {
        $this->runAdminScriptWithMenu('options-media.php');
    }

    public function optionsPermaLink()
    {
        $this->runAdminScriptWithMenu('options-permalink.php');
    }

    public function optionsEdit()
    {
        $this->runAdminScriptWithMenu('options.php');
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

    public function multisiteList()
    {
        $this->runAdminScriptWithMenu('my-sites.php');
    }

    public function runPhpScript()
    {
        $path = app('request')->path();

        $prefix = config('wordpress.url.backend_prefix');

        // trim prefix
        if (starts_with($path, $prefix)) {
            $path = substr($path, strlen($prefix));
        }

        $path = wordpress_path($path);

        // ERROR: file not found
        if (!is_file($path)) {
            abort(404);
        }

        require $path;
    }
}
