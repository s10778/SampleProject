
DROP TABLE IF EXISTS categories;
CREATE TABLE categories(
    id BIGINT NOT NULL AUTO_INCREMENT,
    category_name VARCHAR(100) NOT NULL,
    created DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    is_deleted TINYINT DEFAULT 0,
    PRIMARY KEY (id)
)comment='ブログのカテゴリー';

DROP TABLE IF EXISTS blogs;

CREATE TABLE blogs(
    id BIGINT NOT NULL AUTO_INCREMENT,
    category_id INT,
    title VARCHAR(200) NOT NULL,
    body TEXT,
    created DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    is_deleted TINYINT DEFAULT 0,
    PRIMARY KEY (id)
)comment='ブログ';

create index blogs_category_index on blogs(category_id);

DROP TABLE IF EXISTS app_posts;
CREATE TABLE app_posts (
  post_id INT AUTO_INCREMENT PRIMARY KEY,
  sent_date DATETIME NOT NULL,
  title VARCHAR(50) NOT NULL,
  body TEXT NOT NULL,
  sender VARCHAR(20) NOT NULL,
  attachment VARCHAR(200),
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
)comment='新規投稿';

DROP TABLE IF EXISTS app_posts_members;
CREATE TABLE app_posts_members (
  post_member_id INT AUTO_INCREMENT PRIMARY KEY,
  post_id INT,
  member_id INT,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
)comment='ポストidと紐づいたメンバーid';

DROP TABLE IF EXISTS app_posts_categories;
CREATE TABLE app_posts_categories (
  post_category_id INT AUTO_INCREMENT PRIMARY KEY,
  post_id INT,
  category_id INT,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
)comment='ポストidと紐づいたカテゴリーid';

DROP TABLE IF EXISTS app_categories;
CREATE TABLE app_categories (
  category_id INT AUTO_INCREMENT PRIMARY KEY,
  category_name TEXT,
  division INT,
  display_order INT,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
)comment='カテゴリーid';
