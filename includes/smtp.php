<?php

require('vendor/autoload.php');

// Конфигурация траспорта
$transport = (new Swift_SmtpTransport('phpdemo.ru', 25))
    ->setUsername('keks@phpdemo.ru')
    ->setPassword('htmlacademy')
;

// Формирование сообщения
$message = new Swift_Message($email_title);
$message->setFrom("keks@phpdemo.ru", "keks@phpdemo.ru");
$message->setTo([$email => $login]);
$message->setBody($email_message);

// Отправка сообщения
$mailer = new Swift_Mailer($transport);
$mailer->send($message);