<?php
/**
 * WordPress の基本設定
 *
 * このファイルは、MySQL、テーブル接頭辞、秘密鍵、ABSPATH の設定を含みます。
 * より詳しい情報は {@link http://wpdocs.sourceforge.jp/wp-config.php_%E3%81%AE%E7%B7%A8%E9%9B%86 
 * wp-config.php の編集} を参照してください。MySQL の設定情報はホスティング先より入手できます。
 *
 * このファイルはインストール時に wp-config.php 作成ウィザードが利用します。
 * ウィザードを介さず、このファイルを "wp-config.php" という名前でコピーして直接編集し値を
 * 入力してもかまいません。
 *
 * @package WordPress
 */

// 注意: 
// Windows の "メモ帳" でこのファイルを編集しないでください !
// 問題なく使えるテキストエディタ
// (http://wpdocs.sourceforge.jp/Codex:%E8%AB%87%E8%A9%B1%E5%AE%A4 参照)
// を使用し、必ず UTF-8 の BOM なし (UTF-8N) で保存してください。

// ** MySQL 設定 - この情報はホスティング先から入手してください。 ** //
app()->configure('database');

/** WordPress のためのデータベース名 */
define('DB_NAME', config('database.connections.mysql.database', 'database_name_here'));

/** MySQL データベースのユーザー名 */
define('DB_USER', config('database.connections.mysql.username', 'username_here'));

/** MySQL データベースのパスワード */
define('DB_PASSWORD', config('database.connections.mysql.password', 'password_here'));

/** MySQL のホスト名 */
define('DB_HOST', config('database.connections.mysql.host', 'localhost'));

/** データベースのテーブルを作成する際のデータベースの文字セット */
define('DB_CHARSET', 'utf8mb4');

/** データベースの照合順序 (ほとんどの場合変更する必要はありません) */
define('DB_COLLATE', '');

/**#@+
 * 認証用ユニークキー
 *
 * それぞれを異なるユニーク (一意) な文字列に変更してください。
 * {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org の秘密鍵サービス} で自動生成することもできます。
 * 後でいつでも変更して、既存のすべての cookie を無効にできます。これにより、すべてのユーザーを強制的に再ログインさせることになります。
 *
 * @since 2.6.0
 */
define('AUTH_KEY',         env('WP_AUTH_KEY', 'put your unique phrase here'));
define('SECURE_AUTH_KEY',  env('WP_SECURE_AUTH_KEY', 'put your unique phrase here'));
define('LOGGED_IN_KEY',    env('WP_LOGGED_IN_KEY', 'put your unique phrase here'));
define('NONCE_KEY',        env('WP_NONCE_KEY', 'put your unique phrase here'));
define('AUTH_SALT',        env('WP_AUTH_SALT', 'put your unique phrase here'));
define('SECURE_AUTH_SALT', env('WP_SECURE_AUTH_SALT', 'put your unique phrase here'));
define('LOGGED_IN_SALT',   env('WP_LOGGED_IN_SALT', 'put your unique phrase here'));
define('NONCE_SALT',       env('WP_NONCE_SALT', 'put your unique phrase here'));

/**#@-*/

/**
 * WordPress データベーステーブルの接頭辞
 *
 * それぞれにユニーク (一意) な接頭辞を与えることで一つのデータベースに複数の WordPress を
 * インストールすることができます。半角英数字と下線のみを使用してください。
 */
global $table_prefix;
$table_prefix  = env('WP_TABLE_PREFIX', 'wp_');

/**
 * WordPress デバッグモード
 *
 * PHPデバッグ機能はLumen Frameworkが制御するため、常にfalseを指定してください。
 */
define('WP_DEBUG', false);

/** WordPress マルチサイト機能 */
if (env('WP_MULTISITE', false)) {
    if (defined('WP_INSTALLING_NETWORK') || wordpress_multisite_installed()) {
	    define('WP_ALLOW_MULTISITE', true);
	    define('MULTISITE', true);
	    define('SUBDOMAIN_INSTALL', true);
	    define('DOMAIN_CURRENT_SITE', env('WP_SITEURL', 'localhost'));
	    define('PATH_CURRENT_SITE', '/');
	    define('SITE_ID_CURRENT_SITE', 1);
	    define('BLOG_ID_CURRENT_SITE', 1);
    }
    else {
        define('WP_ALLOW_MULTISITE', true);
    }
}

/* 編集が必要なのはここまでです ! WordPress でブログをお楽しみください。 */

/** Absolute path to the WordPress directory. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');

/** Sets up WordPress vars and included files. */
require_once(ABSPATH . 'wp-settings.php');
