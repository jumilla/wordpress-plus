# WordPress+

[![Build Status](https://travis-ci.org/jumilla/wordpress-plus.svg)](https://travis-ci.org/jumilla/wordpress-plus)
[![Latest Stable Version](https://poser.pugx.org/laravel-plus/wordpress/v/stable.svg)](https://packagist.org/packages/laravel-plus/wordpress)
[![Total Downloads](https://poser.pugx.org/laravel-plus/wordpress/d/total.svg)](https://packagist.org/packages/laravel-plus/wordpress)
[![Software License](https://poser.pugx.org/laravel-plus/wordpress/license.svg)](https://packagist.org/packages/laravel-plus/wordpress)



## WordPress+（プラス）とは

WordPress+は、2015年4月にリリースされたばかりの高速マイクロフレームワーク[Lumen（るーめん）](http://lumen.laravel.com) で[WordPress](https://ja.wordpress.org)をラップしたもので、次のような特徴を持つ開発中のプロダクトです。



## 機能的な特徴

- 世界中で公開されているWordPressプラグインやテーマといった既存資産が利用可能
- WordPress本体の更新が可能（もちろんプラグインも）
- URLゲート（ブラウザからのHTTPアクセスをフィルタリングする層）搭載
- Bladeテンプレートエンジンで、HTMLテンプレートのコーディングスピードがあがる
- Laravelのスキーマビルダー・クエリービルダーを活用することで、一般的なMySQL利用ができる
- 既存システムや外部Webサービスとの連携が作りやすい
- WordPressの管理画面をベースにした独自の管理画面が作りやすい（管理画面制作のコストを下げられる）
- モバイル連携（スマートフォンへの通知など）もチョイチョイ



## 開発サイドのうれしさ

- PHPビルトインサーバーで実行できます
- Composerを使ったPHPパッケージ管理が可能
- Laravel 5のもつ各種Web職人用ツールが使える（O-Auth認証、コマンドスケジューラー、Redis、クラウドストレージ統合などなど）



## WordPress+（プラス）の動作要件

- PHP 5.6 以上 (Lumen Framework は5.5.9以上を要求しています)
	- PHP拡張: openssl, mbstring, pdo, pdo-mysql
- [Composer](https://getcomposer.org/)
- Webサーバー
	- Nginx
		- ※ Nginx 1.8 で動作確認済み
	- Apache 2
		- ※ Apacheモジュール `php5`, `rewrite` が有効になっていること
		- ※ Apache 2.2, 2,4 で動作確認済み
	- PHPビルトインサーバー
		- ※ PHP 5.6, PHP 7 で動作確認済み



## WordPress+（プラス）のサポートしている機能

- コマンドラインからのWordPressデータベースセットアップ
- コマンドラインからのWordPressテーマ・プラグイン作成
- [Bladeテンプレートエンジン](http://laravel.com/docs/5.1/blade)を使ったテーマ作成
- マルチサイト（サブドメイン型のみ対応）
	- オプション: 有効にするには、環境変数 `WP_MULTISITE=true` を指定してください。
- リンクマネージャー
	- オプション: 有効にするには、環境変数 `WP_LINK_MANAGER=true` を指定してください。



## 設定について

### Webサーバーの設定

下記の設定ファイルを参考にしてください。

- [Apach](examples/apache/wordpress+.conf)
- [Nginx](examples/nginx/wordpress+.conf)

### プロジェクト固有の環境変数

WordPress+は、**.env**ファイルにプロジェクトローカルな環境変数を指定できます。
このファイルは、Lumen と WordPressに共通の設定を記述できる領域です。

例)
- WordPressサイトURL、バックエンドURL
- データベースの接続設定

### WordPress設定ファイル wp-config.php

**wp-config.php** はWordPress+用にカスタマイズされています。
***直接編集しないでください***



## インストール方法

Composerまたはソースコードダウンロードからインストールできます。

### Composerを使ったインストール

コマンドプロンプトを開き、次のコマンドを実行してください。

```shell
$ cd <インストールしたいディレクトリ>
$ composer create-project laravel-plus/wordpress <ディレクトリ名>
```

### ソースコードをダウンロードしてインストール

GitHubの当リポジトリの[「Download ZIP」](https://github.com/jumilla/wordpress-plus/archive/master.zip)ボタンを押し、ソースコードをダウンロードします。

そして、ローカルマシンの任意のディレクトリにZIPファイルを展開します。

コマンドプロンプトを開き、次のコマンドを実行してください。

```shell
$ cd <ZIPファイルを展開したディレクトリ>
$ composer install
$ cp .env.example .env
$ php artisan key:generate
```

### 環境変数

データベース等の設定は、プロジェクト固有の環境変数設定ファイル `.env` に記述します。
設定例は [.env.example](.env.example)を参照してください。

### サーバー起動方法

PHPビルトインサーバーで起動できます。

コマンドプロンプトを開き、次のコマンドを実行してください。

```shell
$ cd <インストールしたディレクトリ>
$ php artisan serve
```

Webブラウザで `http://localhost:8000` を開くとアクセスできます。
初回起動時は、GUIでのWordPressのセットアップが始まります。
（artisanコマンド [`wordpress:install` でインストール](examples/install.png)することもできます。)

管理画面のURLは `http://localhost:8000/backend` です。(`http://localhost:8000/backend/wp-admin/` へリダイレクトされます。)

※セットアップ先のドメインに合わせて、**.env**でURLを変更してください。
サイトURL`WP_SITEURL`とバックエンドURL`WP_BACKENDURL`を別にし、セキュリティを高めることもできます。

### Webサーバーの設定

Lumen 5 / Laravel 5 は、Webサーバーの設定でURLの末尾のスラッシュ`/`を取り除くことを推奨しています。
しかし、WordPressは末尾のスラッシュ`/`を必要としていますので、設定の際には注意してください。
管理画面へのログイン後にリダイレクトループが発生する場合があります。



## artisan（アルチザン）コマンド

- `wordpress:status` WordPressの状態表示
- `wordpress:install` WordPressテーブルのインストール
- `wordpress:uninstall` WordPressテーブルのアンインストール
- `wordpress:multisite:install` マルチサイトテーブルのインストール
- `wordpress:multisite:uninstall` マルチサイトテーブルのアンインストール
- `wordpress:theme` テーマ一覧表示
- `wordpress:theme <name>` テーマ詳細表示
- `wordpress:plugin` プラグイン一覧表示
- `wordpress:plugin <name>` プラグイン詳細表示
- `make:theme <name>` テーマ作成
- `make:plugin <name>` プラグイン作成



## テーマ内でのBladeの利用

テーマディレクトリ内に`blade`というディレクトリを作成し、そこにWordPressテンプレートファイル名規約に沿ったファイルを配置します。
拡張子は`.blade.php`にします。

環境変数`WP_BLADE_PRECOMPILE`を`true`（デフォルト）に設定した場合、WordPress+でページを表示したときにコンパイルされたPHPファイルがテーマディレクトリ直下に出力されます。この時に使われるBladeエンジンはWordPress+独自の実装です。

環境変数`WP_BLADE_PRECOMPILE`を`false`に設定した場合、bladeファイルに対応する空のPHPファイルをテーマディレクトリ直下に出力します。この時に使われるBladeエンジンはLaravel 5の実装です。



## Bladeディレクティブ

- `@filter('filter-name')` フィルタを呼び出す
- `@action('action-name')` アクションを呼び出す
- `@shortcode([shortcode-name param1="value1"])` ショートコードを展開する
- `@postloop` `@postloop($posts)` Postクエリーのループブロックを開始する
- `@postempty` Postクエリーが0件の場合のブロックを開始する
- `@endpostloop` Postデータのループブロックを終了する



## 著作権表示

2015 [Fumio Furukawa](http://jumilla.me), All rights reserved.



## ライセンス

[WordPress+](https://github.com/jumilla/wordpress-plus) is open-sourced software licensed under the [MIT license](http://opensource.org/licenses/MIT)  
[Lumen framework](http://lumen.laravel.com) is open-sourced software licensed under the [MIT license](http://opensource.org/licenses/MIT)  
[WordPress](https://ja.wordpress.org) is open-sourced software licensed under the [– GNU General Public License –](https://ja.wordpress.org/gpl/)  
