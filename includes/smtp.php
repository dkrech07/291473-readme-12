<?php

require('vendor/autoload.php');

// Конфигурация траспорта
$transport = new Swift_SmtpTransport('smtp.example.org', 25);

// Формирование сообщения
$message = new Swift_Message("Просмотры вашей гифки");
$message->setTo(["keks@htmlacademy.ru" => "Кекс"]);
$message->setBody("Вашу гифку «Кот и пылесос» посмотрело больше 1 млн!");
$message->setFrom("mail@giftube.academy", "GifTube");

// Отправка сообщения
$mailer = new Swift_Mailer($transport);
$mailer->send($message);