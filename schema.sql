CREATE DATABASE readme
  DEFAULT CHARACTER SET utf8
  DEFAULT COLLATE utf8_general_ci

USE readme;

CREATE TABLE users (
  id  INT AUTO_INCREMENT PRIMARY KEY,
  date_add  DATETIME,
  email VARCHAR(128) NOT NULL UNIQUE,
  login VARCHAR(128) NOT NULL UNIQUE,
  password  CHAR(64) NOT NULL,
  avatar  VARCHAR(128)
);

CREATE TABLE content_types (
  id  INT AUTO_INCREMENT PRIMARY KEY,
  type_name VARCHAR(128) NOT NULL,
  class_name VARCHAR(128) NOT NULL
);

CREATE TABLE posts (
  id  INT AUTO_INCREMENT PRIMARY KEY,
  date_add  DATETIME,
  title VARCHAR(128) NOT NULL,
  description TEXT,
  quote_author  VARCHAR(128),
  image VARCHAR(128),
  video VARCHAR(128),
  link  VARCHAR(128),
  views INT NOT NULL DEFAULT 0,
  user_id INT NOT NULL,
  content_type_id INT NOT NULL,
  FOREIGN KEY (user_id) REFERENCES users(id),
  FOREIGN KEY (content_type_id) REFERENCES content_types(id)
);

CREATE TABLE comments (
  id INT AUTO_INCREMENT PRIMARY KEY,
  date_add  DATETIME,
  description TEXT,
  user_id INT NOT NULL,
  post_id INT NOT NULL,
  FOREIGN KEY (user_id) REFERENCES users(id),
  FOREIGN KEY (post_id) REFERENCES posts(id)
);

CREATE TABLE likes (
  id INT AUTO_INCREMENT PRIMARY KEY,
  user_id INT NOT NULL,
  post_id INT NOT NULL,
  FOREIGN KEY (user_id) REFERENCES users(id),
  FOREIGN KEY (post_id) REFERENCES posts(id)
);

CREATE TABLE subscriptions (
  id  INT AUTO_INCREMENT PRIMARY KEY,
  subscriber_id INT NOT NULL,
  user_id INT NOT NULL,
  FOREIGN KEY (subscriber_id) REFERENCES users(id),
  FOREIGN KEY (user_id) REFERENCES users(id)
);

CREATE TABLE messages (
  id  INT AUTO_INCREMENT PRIMARY KEY,
  date_add  DATETIME,
  description TEXT,
  sender_id  INT NOT NULL,
  recipient_id INT NOT NULL,
  FOREIGN KEY (sender_id) REFERENCES users(id),
  FOREIGN KEY (recipient_id) REFERENCES users(id)
);

CREATE TABLE hashtags (
  id INT AUTO_INCREMENT PRIMARY KEY,
  hashtag_name VARCHAR(128)
);

CREATE TABLE post_hashtags (
  id INT AUTO_INCREMENT PRIMARY KEY,
  hashtag_id INT NOT NULL,
  user_id INT NOT NULL,
  FOREIGN KEY (hashtag_id) REFERENCES hashtags(id),
  FOREIGN KEY (user_id) REFERENCES users(id)
);

CREATE INDEX c_email_login ON users(email, login);
CREATE INDEX c_title ON posts(title);
CREATE INDEX c_user_post ON comments(user_id, post_id);
CREATE INDEX c_user_post ON likes(user_id, post_id);
CREATE INDEX c_subscriber_user ON subscriptions(subscriber_id, user_id);
CREATE INDEX c_sender_recipient ON messages(sender_id, recipient_id);
CREATE INDEX c_hashtags ON hashtags(hashtag_name);
