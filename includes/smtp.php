<?php

require('vendor/autoload.php');

// Конфигурация траспорта
$transport = (new Swift_SmtpTransport('phpdemo.ru', 25))
    ->setUsername('keks@phpdemo.ru')
    ->setPassword('htmlacademy')
;

// Формирование сообщения
$message = new Swift_Message("Просмотры вашей гифки");
$message->setFrom("keks@phpdemo.ru", "GifTube");
$message->setTo(["dkrech07@gmail.com" => "Кекс"]);
$message->setBody("Вашу гифку «Кот и пылесос» посмотрело больше 1 млн!");

// Отправка сообщения
$mailer = new Swift_Mailer($transport);
$mailer->send($message);