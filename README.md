# 概要

サンプルプロジェクト

カテゴリーとブログの編集画面を想定。

トップ画面では一覧表示。  
カテゴリーの登録と、記事の登録が可能。 

# 環境構築手順

Dockerを起動すれば、ローカル環境にて確認可能。  
apache 80番ポート、mysql 3306ポートを使用するため、すでに起動しているapache,mysqlは先に停止しておく必要がある。  

## Docker起動
$docker-compose up -d

## Docker停止
$docker-compose stop


## データベース作成
docker-compose exec db bash
mysql -u root -proot

### データベース作成
- utf8指定 utf8mb4 にすると絵文字が使えるが、今回は使用しないためutf8。  
CREATE DATABASE sample_project DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;
### ユーザー作成
CREATE USER sample_project IDENTIFIED BY 'sample_project';
### 権限付与
GRANT ALL PRIVILEGES ON sample_project.* TO sample_project;

### テーブル作成
docker/db/sql/create_table.sqlを実行する

docker-compose exec sample-db bash
mysql -u sample_project -p sample_project < /docker-entrypoint-initdb.d/create_table.sql

Enter password: sample_project 


# 動作確認用URL

## 表示画面
http://localhost/  




