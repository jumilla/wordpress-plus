## WordPress+とは

WordPress+は、2015年4月にリリースされたばかりの高速マイクロフレームワーク[Lumen（るーめん）](http://lumen.laravel.com) で[WordPress](https://ja.wordpress.org)をラップしたもので、次のような特徴を持つ開発中のプロダクトです。

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

## インストール方法

ComposerまたはソースコードのZipダウンロードからインストールできます。

```shell
$> composer create-project laravel-plus/wordpress <ディレクトリ名>
```

## ライセンス
WordPress+ is open-sourced software licensed under the [MIT license](http://opensource.org/licenses/MIT)
The Lumen framework is open-sourced software licensed under the [MIT license](http://opensource.org/licenses/MIT)
WordPress のライセンス [– GNU General Public License –](https://ja.wordpress.org/gpl/)

## 著作権
2015 Fumio Furukawa, All rights reserved.
