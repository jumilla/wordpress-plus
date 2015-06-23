<?php

namespace App\Http\Controllers\WordPress;

/**
 *
 */
class CollaborationController extends Controller
{
    public function __construct()
    {
    }

    public function opml()
    {
        $this->runScript('wp-links-opml.php');
    }

    public function mail()
    {
        $this->runScript('wp-mail.php');
    }

    public function trackback()
    {
        $this->runScript('wp-trackback.php');
    }

    public function xmlrpc()
    {
        // これを定義しないと以下のエラーが出る
        // XMLRPC -32601: requested method jetpack.verifyRegistration does not exist
        define('XMLRPC_REQUEST', true);

        // MEMO PHP7で、apply_filters() がないと言われる対策。
        require_once wordpress_path('wp-config.php');
//		info (function_exists('apply_filters'));

        // TODO xmlrpc.phpは書き直した方がいい
        require wordpress_path('xmlrpc.php');
    }

    public function cron()
    {
        $this->runScript('wp-cron.php', ['wpdb']);
    }
}
