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
  hashtag_id INT REFERENCES hashtags(id),
  FOREIGN KEY (user_id) REFERENCES users(id),
  FOREIGN KEY (content_type_id) REFERENCES content_types(id)
);

CREATE TABLE comments (
  id INT AUTO_INCREMENT PRIMARY KEY,
  date_add  DATETIME,
  description TEXT,
  user_id VARCHAR(128) NOT NULL,
  post_id VARCHAR(128) NOT NULL,
  FOREIGN KEY (user_id) REFERENCES users(id),
  FOREIGN KEY (post_id) REFERENCES posts(id)
);

CREATE TABLE likes (
  id INT AUTO_INCREMENT PRIMARY KEY,
  user_id VARCHAR(128) NOT NULL,
  post_id VARCHAR(128) NOT NULL,
  FOREIGN KEY (user_id) REFERENCES users(id),
  FOREIGN KEY (post_id) REFERENCES posts(id)
);

CREATE TABLE content_types (
  id  INT AUTO_INCREMENT PRIMARY KEY,
  type_name VARCHAR(128) NOT NULL,
  class_name VARCHAR(128) NOT NULL
);

CREATE TABLE hashtags (
  id INT AUTO_INCREMENT PRIMARY KEY,
  hashtag_name VARCHAR(128)
);
