-- Добавляет список типов контента для поста;
INSERT INTO content_types (type_name, class_name) VALUES ('Текст', 'text'), ('Цитата', 'quote'), ('Картинка', 'photo'), ('Видео', 'video'), ('Ссылка', 'link');

/*Добавляет пользователей;
password_hash('Q4B4e6Ap', PASSWORD_DEFAULT); // $2y$10$feI4UmS8vwRUqjGuvBkNheLQ4eL5KI4PW8YN11SmhzRi9ELC0ehUq
password_hash('gQHVnixF', PASSWORD_DEFAULT); // $2y$10$7uOaL/PzYWwnhT9Ly2mz9.6tjORSkt7D2RZZ/ZO8mlozZnpLcy9Dm
password_hash('MKLVL4Ce', PASSWORD_DEFAULT); // $2y$10$hN2SZp43qwJdZQk8QwIGtudREYDU/2lRCfhhmVcEwL6nV/OJaIVI6
password_hash('tM9EqxSD', PASSWORD_DEFAULT); // $2y$10$6Ekqhbl/mh/MyznsP3.CiuX/OIHhe0N/vdKMhVgBdNw5uQU1QjtVK
password_hash('KtG2brMZ', PASSWORD_DEFAULT); // $2y$10$Ai.9TbowPmD04wF.W5KxOeeeos1w4T3TiJZCoh3tn5ilnLt1VrgB*/
INSERT INTO users (date_add, email, login, password, avatar) VALUES ('2020-01-16 16:30:25', 'larisa@mail.ru', 'larisa', '$2y$10$feI4UmS8vwRUqjGuvBkNheLQ4eL5KI4PW8YN11SmhzRi9ELC0ehUq', 'img/userpic-larisa-small.jpg'),
                                                                    ('2020-02-15 15:45:20', 'vladik@mail.ru', 'vladik', '$2y$10$7uOaL/PzYWwnhT9Ly2mz9.6tjORSkt7D2RZZ/ZO8mlozZnpLcy9Dm', 'img/userpic.jpg'),
                                                                    ('2020-05-16 13:50:30', 'viktor@mail.ru', 'viktor', '$2y$10$hN2SZp43qwJdZQk8QwIGtudREYDU/2lRCfhhmVcEwL6nV/OJaIVI6', 'img/userpic-mark.jpg'),
                                                                    ('2020-07-16 12:30:30', 'tanya@mail.ru', 'tanya', '$2y$10$6Ekqhbl/mh/MyznsP3.CiuX/OIHhe0N/vdKMhVgBdNw5uQU1QjtVK', 'img/userpic-tanya.jpg'),
                                                                    ('2020-08-16 05:55:30', 'petro@mail.ru', 'petro', '$2y$10$Ai.9TbowPmD04wF.W5KxOeeeos1w4T3TiJZCoh3tn5ilnLt1VrgB', 'img/userpic-petro.jpg');

-- Добавляет список постов;
INSERT INTO posts (date_add, title, content, views, post_author_id, content_type_id, likes_count, comments_count) VALUES ('2020-10-16 11:31:26', 'Цитата', 'Мы в жизни любим только раз, а после ищем лишь похожих', 2, 2, 2, 1, 5),
                                                                                            ('2020-10-10 16:31:26', 'Игра престолов', 'Не могу дождаться начала финального сезона своего любимого сериала!', 5, 3, 1, 2, 25);
INSERT INTO posts (date_add, title, content, image, views, post_author_id, content_type_id, likes_count, comments_count) VALUES ('2020-10-09 16:31:26', 'Наконец, обработал фотки!', 'rock-medium.jpg', 'img/rock-medium.jpg', 99, 4, 3, 5, 7),
                                                                                                   ('2020-02-16 16:31:26', 'Моя мечта', 'coast-medium.jpg', 'img/coast-medium.jpg', 3, 1, 3, 5, 75);
INSERT INTO posts (date_add, title, content, link, views, post_author_id, content_type_id, likes_count, comments_count) VALUES ('2020-06-16 16:31:26', 'Лучшие курсы', 'https://www.htmlacademy.ru', 'www.htmlacademy.ru', 2, 5, 5, 15, 14);

-- Добавляет комментарии к постам;
INSERT INTO comments (date_add, content, comment_author_id, post_id) VALUES ('2020-10-16 11:35:26', 'Красивые стихи... Это Есенин?', 2, 1),
                                                                            ('2020-10-16 11:35:26', 'Ага, я тоже жду новый сезон', 3, 2),
                                                                            ('2020-10-16 11:35:26', 'Очень неплохо. На какую камеру снимал?', 1, 3),
                                                                            ('2020-10-16 11:35:26', 'Горизонт завален! У меня бабушка лучше фоткает!', 4, 3),
                                                                            ('2020-10-16 11:35:26', 'Красиво)))', 2, 4);

-- Получает список постов с сортировкой по популярности и вместе с именами авторов и типом контента;
SELECT p.*, u.login, ct.type_name FROM posts p INNER JOIN users u ON u.id = p.post_author_id INNER JOIN content_types ct ON ct.id = p.content_type_id ORDER BY p.views DESC;

-- Получает список постов для конкретного пользователя (по логину, результат с именем автора и названием типа контента);
SELECT p.*, u.login, ct.type_name FROM posts p INNER JOIN users u ON u.id = p.post_author_id INNER JOIN content_types ct ON ct.id = p.content_type_id WHERE login = 'larisa';

-- Получает список постов для конкретного пользователя (по id, результат с id автора и id типа контента);
SELECT * FROM posts WHERE post_author_id = 1;

-- Получает список комментариев для одного поста, в комментариях должен быть логин пользователя;
SELECT comments.content, users.login FROM comments INNER JOIN users ON users.id = comments.comment_author_id WHERE post_id = 1;

-- Добавляет лайк к посту;
INSERT INTO likes (like_author_id, post_id) VALUES (1, 1);
INSERT INTO likes (like_author_id, post_id) VALUES (2, 2);
INSERT INTO likes (like_author_id, post_id) VALUES (3, 3);
INSERT INTO likes (like_author_id, post_id) VALUES (4, 1);
INSERT INTO likes (like_author_id, post_id) VALUES (5, 2);
INSERT INTO likes (like_author_id, post_id) VALUES (1, 2);
INSERT INTO likes (like_author_id, post_id) VALUES (2, 3);
INSERT INTO likes (like_author_id, post_id) VALUES (3, 4);
INSERT INTO likes (like_author_id, post_id) VALUES (4, 5);
INSERT INTO likes (like_author_id, post_id) VALUES (5, 1);

-- Подписывается на пользователя;
INSERT INTO subscriptions (subscriber_id, author_id) VALUES (1, 2);
INSERT INTO subscriptions (subscriber_id, author_id) VALUES (1, 3);
INSERT INTO subscriptions (subscriber_id, author_id) VALUES (1, 4);
INSERT INTO subscriptions (subscriber_id, author_id) VALUES (1, 5);

-- Создает полнотекстовый индекс в таблице posts для полей "название" и "текстовое содержимое";
CREATE FULLTEXT INDEX posts_search ON posts(title, content);

-- Индексирует посты и хештеги;
CREATE FULLTEXT INDEX hashtags_search ON hashtags(hashtag_name);

-- Добавляет посты;
INSERT INTO posts (date_add, title, content, views, post_author_id, content_type_id, likes_count, comments_count) VALUES ('2020-10-16 11:31:26', 'Цитата', 'Пост - 1', 2, 1, 2, 1, 5),
                                                                                                                        ('2020-10-16 11:31:26', 'Цитата', 'Пост - 2', 2, 2, 2, 1, 5),
                                                                                                                        ('2020-10-16 11:31:26', 'Цитата', 'Пост - 3', 2, 3, 2, 1, 5),
                                                                                                                        ('2020-10-16 11:31:26', 'Цитата', 'Пост - 4', 4, 2, 2, 1, 5),
                                                                                                                        ('2020-10-16 11:31:26', 'Цитата', 'Пост - 5', 5, 2, 2, 1, 5),
                                                                                                                        ('2020-10-16 11:31:26', 'Цитата', 'Пост - 6', 5, 2, 2, 1, 5),
                                                                                                                        ('2020-10-16 11:31:26', 'Цитата', 'Пост - 7', 5, 2, 2, 1, 5),
                                                                                                                        ('2020-10-16 11:31:26', 'Цитата', 'Пост - 8', 5, 2, 2, 1, 5),
                                                                                                                        ('2020-10-16 11:31:26', 'Цитата', 'Пост - 9', 5, 2, 2, 1, 5),
                                                                                                                        ('2020-10-16 11:31:26', 'Цитата', 'Пост - 10', 5, 2, 2, 1, 5),
                                                                                                                        ('2020-10-16 11:31:26', 'Цитата', 'Пост - 11', 5, 2, 2, 1, 5),
                                                                                                                        ('2020-10-16 11:31:26', 'Цитата', 'Пост - 12', 5, 2, 2, 1, 5),
                                                                                                                        ('2020-10-16 11:31:26', 'Цитата', 'Пост - 13', 5, 2, 2, 1, 5),
                                                                                                                        ('2020-10-16 11:31:26', 'Цитата', 'Пост - 14', 5, 2, 2, 1, 5),
                                                                                                                        ('2020-10-16 11:31:26', 'Цитата', 'Пост - 15', 5, 2, 2, 1, 5);
INSERT INTO posts (date_add, title, content, image, views, post_author_id, content_type_id, likes_count, comments_count) VALUES ('2020-10-09 16:31:26', 'Пост - 16', 'rock-medium.jpg', 'img/rock-medium.jpg', 99, 1, 3, 5, 7),
                                                                                                   ('2020-02-16 16:31:26', 'Пост - 17', 'coast-medium.jpg', 'img/coast-medium.jpg', 3, 2, 3, 5, 75),
                                                                                                   ('2020-02-16 16:31:26', 'Пост - 18', 'coast-medium.jpg', 'img/coast-medium.jpg', 3, 3, 3, 5, 75),
                                                                                                   ('2020-02-16 16:31:26', 'Пост - 19', 'coast-medium.jpg', 'img/coast-medium.jpg', 3, 4, 3, 5, 75),
                                                                                                   ('2020-02-16 16:31:26', 'Пост - 20', 'coast-medium.jpg', 'img/coast-medium.jpg', 3, 5, 3, 5, 75),
                                                                                                   ('2020-02-16 16:31:26', 'Пост - 21', 'coast-medium.jpg', 'img/coast-medium.jpg', 3, 4, 3, 5, 75),
                                                                                                   ('2020-02-16 16:31:26', 'Пост - 22', 'coast-medium.jpg', 'img/coast-medium.jpg', 3, 4, 3, 5, 75),
                                                                                                   ('2020-02-16 16:31:26', 'Пост - 23', 'coast-medium.jpg', 'img/coast-medium.jpg', 3, 4, 3, 5, 75),
                                                                                                   ('2020-02-16 16:31:26', 'Пост - 24', 'coast-medium.jpg', 'img/coast-medium.jpg', 3, 4, 3, 5, 75),
                                                                                                   ('2020-02-16 16:31:26', 'Пост - 25', 'coast-medium.jpg', 'img/coast-medium.jpg', 3, 4, 3, 5, 75),
                                                                                                   ('2020-02-16 16:31:26', 'Пост - 26', 'coast-medium.jpg', 'img/coast-medium.jpg', 3, 4, 3, 5, 75),
                                                                                                   ('2020-02-16 16:31:26', 'Пост - 27', 'coast-medium.jpg', 'img/coast-medium.jpg', 3, 4, 3, 5, 75),
                                                                                                   ('2020-02-16 16:31:26', 'Пост - 28', 'coast-medium.jpg', 'img/coast-medium.jpg', 3, 4, 3, 5, 75),
                                                                                                   ('2020-02-16 16:31:26', 'Пост - 29', 'coast-medium.jpg', 'img/coast-medium.jpg', 3, 4, 3, 5, 75),
                                                                                                   ('2020-02-16 16:31:26', 'Пост - 30', 'coast-medium.jpg', 'img/coast-medium.jpg', 3, 4, 3, 5, 75);
INSERT INTO posts (date_add, title, content, link, views, post_author_id, content_type_id, likes_count, comments_count) VALUES ('2020-06-16 16:31:26', 'Пост - 31', 'https://www.htmlacademy.ru', 'www.htmlacademy.ru', 2, 1, 5, 15, 14),
                                                                                                ('2020-06-16 16:31:26', 'Пост - 31', 'https://www.htmlacademy.ru', 'www.htmlacademy.ru', 2, 1, 5, 15, 14),
                                                                                                ('2020-06-16 16:31:26', 'Пост - 32', 'https://www.htmlacademy.ru', 'www.htmlacademy.ru', 2, 1, 5, 15, 14),
                                                                                                ('2020-06-16 16:31:26', 'Пост - 33', 'https://www.htmlacademy.ru', 'www.htmlacademy.ru', 2, 1, 5, 15, 14),
                                                                                                ('2020-06-16 16:31:26', 'Пост - 34', 'https://www.htmlacademy.ru', 'www.htmlacademy.ru', 2, 1, 5, 15, 14),
                                                                                                ('2020-06-16 16:31:26', 'Пост - 35', 'https://www.htmlacademy.ru', 'www.htmlacademy.ru', 2, 1, 5, 15, 14),
                                                                                                ('2020-06-16 16:31:26', 'Пост - 36', 'https://www.htmlacademy.ru', 'www.htmlacademy.ru', 2, 1, 5, 15, 14),
                                                                                                ('2020-06-16 16:31:26', 'Пост - 37', 'https://www.htmlacademy.ru', 'www.htmlacademy.ru', 2, 1, 5, 15, 14);


                                                                                                