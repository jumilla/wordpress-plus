## WordPress+とは

WordPress+は、2015年4月にリリースされたばかりの高速マイクロフレームワーク[Lumen（るーめん）](http://lumen.laravel.com) で[WordPress](https://ja.wordpress.org)をラップしたもので、次のような特徴を持つ開発中のプロダクトです。

***まだα版レベルの開発中です。フィードバック大好物な時期ですので、お気軽にメッセージください！***

## 機能的な特徴

- 世界中で公開されているWordPressプラグインやテーマといった既存資産が利用可能
- WordPress本体の更新が可能（もちろんプラグインも）
- URLゲート（ブラウザからのHTTPアクセスをフィルタリングする層）搭載
- Bladeテンプレートエンジンで、HTMLテンプレートのコーディングスピードがあがる
- Laravelのスキーマビルダー・クエリービルダーを活用することで、一般的なMySQL利用ができる
- 既存システムや外部Webサービスとの連携が作りやすい
- WordPressの管理画面をベースにした独自の管理画面が作りやすい（管理画面製作のコストを下げられる）
- モバイル連携（スマートフォンへの通知など）もチョイチョイ

## 開発サイドのうれしさ

- PHPビルトインサーバーで実行可能（Apacheに依存しない運用も可能）
- Composerを使ったPHPパッケージ管理が可能
- Laravel 5のもつ各種Web職人用ツールが使える（O-Auth認証、コマンドスケジューラー、Redis、クラウドストレージ統合などなど）

## WordPress+（プラス）の動作要件

- PHP 5.5.9 以上
- PHP拡張: [必須] openssl, mbstring, pdo, pdo-mysql
- Composer: [推奨] パスの通ったディレクトリ(例えば`/usr/bin`)にインストールしておくこと

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

そして、ローカルマシンの任意のディレクトリにZIPを展開します。

コマンドプロンプトを開き、次のコマンドを実行してください。

```shell
$ cd <インストールしたディレクトリ>
$ php composer update
```

### 起動方法

PHPビルトインサーバーで起動できます。

コマンドプロンプトを開き、次のコマンドを実行してください。

```shell
$ cd <インストールしたディレクトリ>
$ php artisan serve
```

Webブラウザで `http://localhost:8000` を開くとアクセスできます。

管理画面は `http://localhost:8000/wp-admin/` です。



## ライセンス
[WordPress+](https://github.com/jumilla/wordpress-plus) is open-sourced software licensed under the [MIT license](http://opensource.org/licenses/MIT)  
[Lumen framework](http://lumen.laravel.com) is open-sourced software licensed under the [MIT license](http://opensource.org/licenses/MIT)  
[WordPress](https://ja.wordpress.org) is open-sourced software licensed under the [– GNU General Public License –](https://ja.wordpress.org/gpl/)  

## 著作権
2015 [Fumio Furukawa](http://jumilla.me), All rights reserved.
