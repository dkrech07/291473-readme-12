-- Добавление списока типов контента для поста;
INSERT INTO content_types SET type_name = 'Текст', class_name = 'text';
INSERT INTO content_types SET type_name = 'Цитата', class_name = 'quote';
INSERT INTO content_types SET type_name = 'Картинка', class_name = 'photo';
INSERT INTO content_types SET type_name = 'Видео', class_name = 'video';
INSERT INTO content_types SET type_name = 'Ссылка', class_name = 'link';

-- Добавление пользователей;
-- password_hash('Q4B4e6Ap', PASSWORD_DEFAULT); // $2y$10$feI4UmS8vwRUqjGuvBkNheLQ4eL5KI4PW8YN11SmhzRi9ELC0ehUq
-- password_hash('gQHVnixF', PASSWORD_DEFAULT); // $2y$10$7uOaL/PzYWwnhT9Ly2mz9.6tjORSkt7D2RZZ/ZO8mlozZnpLcy9Dm
-- password_hash('MKLVL4Ce', PASSWORD_DEFAULT); // $2y$10$hN2SZp43qwJdZQk8QwIGtudREYDU/2lRCfhhmVcEwL6nV/OJaIVI6
-- password_hash('tM9EqxSD', PASSWORD_DEFAULT); // $2y$10$6Ekqhbl/mh/MyznsP3.CiuX/OIHhe0N/vdKMhVgBdNw5uQU1QjtVK
-- password_hash('KtG2brMZ', PASSWORD_DEFAULT); // $2y$10$Ai.9TbowPmD04wF.W5KxOeeeos1w4T3TiJZCoh3tn5ilnLt1VrgB
INSERT INTO users SET date_add = '2020-01-16 16:30:25', email = 'larisa@mail.ru', login = 'larisa', password = '$2y$10$feI4UmS8vwRUqjGuvBkNheLQ4eL5KI4PW8YN11SmhzRi9ELC0ehUq', avatar='img/userpic-larisa-small.jpg';
INSERT INTO users SET date_add = '2020-02-15 15:45:20', email = 'vladik@mail.ru', login = 'vladik', password = '$2y$10$7uOaL/PzYWwnhT9Ly2mz9.6tjORSkt7D2RZZ/ZO8mlozZnpLcy9Dm', avatar='img/userpic.jpg';
INSERT INTO users SET date_add = '2020-05-16 13:50:30', email = 'viktor@mail.ru', login = 'viktor', password = '$2y$10$hN2SZp43qwJdZQk8QwIGtudREYDU/2lRCfhhmVcEwL6nV/OJaIVI6', avatar='img/userpic-mark.jpg';
INSERT INTO users SET date_add = '2020-07-16 12:30:30', email = 'tanya@mail.ru', login = 'tanya', password = '$2y$10$6Ekqhbl/mh/MyznsP3.CiuX/OIHhe0N/vdKMhVgBdNw5uQU1QjtVK', avatar='img/userpic-tanya.jpg';
INSERT INTO users SET date_add = '2020-08-16 05:55:30', email = 'petro@mail.ru', login = 'petro', password = '$2y$10$Ai.9TbowPmD04wF.W5KxOeeeos1w4T3TiJZCoh3tn5ilnLt1VrgB', avatar='img/userpic-petro.jpg';

-- Добавление комментариев к постам;
INSERT INTO comments SET date_add = '2020-10-16 11:35:26', content = 'Красивые стихи!', comment_author_id = '2', post_id = '1';

-- Добавление списка постов;
INSERT INTO posts SET date_add = '2020-10-16 11:31:26', title = 'Цитата', content = 'Мы в жизни любим только раз, а после ищем лишь похожих', quote_author = '', post_author_id = '1', content_type_id = '2';
INSERT INTO posts SET date_add = '2020-10-10 16:31:26', title = 'Игра престолов', content = 'Не могу дождаться начала финального сезона своего любимого сериала!', post_author_id = '2', content_type_id = '1';
INSERT INTO posts SET date_add = '2020-10-09 16:31:26', title = 'Наконец, обработал фотки!', content = 'rock-medium.jpg', image = 'img/rock-medium.jpg', post_author_id = '3', content_type_id = '3';
INSERT INTO posts SET date_add = '2020-02-16 16:31:26', title = 'Моя мечта', content = 'coast-medium.jpg', image = 'img/coast-medium.jpg', post_author_id = '1', content_type_id = '3';
INSERT INTO posts SET date_add = '2020-06-16 16:31:26', title = 'Лучшие курсы', content = 'www.htmlacademy.ru', link = 'www.htmlacademy.ru', post_author_id = '2', content_type_id = '5';
