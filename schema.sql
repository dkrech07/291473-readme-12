CREATE DATABASE readme
  DEFAULT CHARACTER SET utf8
  DEFAULT COLLATE utf8_general_ci

USE readme;

CREATE TABLE users (
  id  INT UNSIGNED AUTO_INCREMENT PRIMARY KEY NOT NULL,
  date_add  DATETIME NOT NULL,
  email VARCHAR(128) NOT NULL UNIQUE,
  login VARCHAR(128) NOT NULL UNIQUE,
  password  CHAR(64) NOT NULL,
  avatar  VARCHAR(128)
);

CREATE TABLE content_types (
  id  INT UNSIGNED AUTO_INCREMENT PRIMARY KEY NOT NULL,
  type_name VARCHAR(128) NOT NULL,
  class_name VARCHAR(128) NOT NULL
);

CREATE TABLE posts (
  id  INT UNSIGNED AUTO_INCREMENT PRIMARY KEY NOT NULL,
  date_add  DATETIME NOT NULL,
  title VARCHAR(128) NOT NULL,
  content TEXT NOT NULL,
  quote_author  VARCHAR(128),
  image VARCHAR(128),
  video VARCHAR(128),
  link  VARCHAR(128),
  views INT UNSIGNED NOT NULL DEFAULT 0,
  post_author_id INT UNSIGNED NOT NULL,
  content_type_id INT UNSIGNED NOT NULL,
  FOREIGN KEY (post_author_id) REFERENCES users(id),
  FOREIGN KEY (content_type_id) REFERENCES content_types(id)
);

CREATE TABLE comments (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY NOT NULL,
  date_add  DATETIME NOT NULL,
  content TEXT NOT NULL,
  comment_author_id INT UNSIGNED NOT NULL,
  post_id INT UNSIGNED NOT NULL,
  FOREIGN KEY (comment_author_id) REFERENCES users(id),
  FOREIGN KEY (post_id) REFERENCES posts(id)
);

CREATE TABLE likes (
  like_author_id INT UNSIGNED NOT NULL,
  post_id INT UNSIGNED NOT NULL,
  FOREIGN KEY (like_author_id) REFERENCES users(id),
  FOREIGN KEY (post_id) REFERENCES posts(id)
);

CREATE TABLE subscriptions (
  subscriber_id INT UNSIGNED NOT NULL,
  author_id INT UNSIGNED NOT NULL,
  FOREIGN KEY (subscriber_id) REFERENCES users(id),
  FOREIGN KEY (author_id) REFERENCES users(id)
);

CREATE TABLE messages (
  id  INT UNSIGNED AUTO_INCREMENT PRIMARY KEY NOT NULL,
  date_add  DATETIME NOT NULL,
  content TEXT NOT NULL,
  sender_id  INT UNSIGNED NOT NULL,
  recipient_id INT UNSIGNED NOT NULL,
  FOREIGN KEY (sender_id) REFERENCES users(id),
  FOREIGN KEY (recipient_id) REFERENCES users(id)
);

CREATE TABLE hashtags (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY NOT NULL,
  hashtag_name VARCHAR(128)
);

CREATE TABLE post_hashtags (
  hashtag_id INT UNSIGNED NOT NULL,
  post_id INT UNSIGNED NOT NULL,
  FOREIGN KEY (hashtag_id) REFERENCES hashtags(id),
  FOREIGN KEY (post_id) REFERENCES posts(id)
);

CREATE INDEX c_email ON users(email);
CREATE INDEX c_login ON users(login);

-- CREATE INDEX c_email_login ON users(email, login);
-- CREATE INDEX c_title ON posts(title);
-- CREATE INDEX c_user_post ON comments(user_id, post_id);
-- CREATE INDEX c_user_post ON likes(user_id, post_id);
-- CREATE INDEX c_subscriber_user ON subscriptions(subscriber_id, user_id);
-- CREATE INDEX c_sender_recipient ON messages(sender_id, recipient_id);
-- CREATE INDEX c_hashtags ON hashtags(hashtag_name);
