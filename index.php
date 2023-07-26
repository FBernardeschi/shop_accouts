<?php
session_start();

require_once 'vendor/autoload.php';

$loader = new \Twig\Loader\FilesystemLoader('templates');
$twig = new \Twig\Environment($loader);
$db = require_once 'PDO.php';

$news = array(
    ['1689804204', date('d.m.y', 1689804204), 'Текст в рот ебал'],
    ['1689808204', date('d.m.y', 1689808204), 'Твоя мать потаскуха'],
    ['1689899204', date('d.m.y', 1689899204), 'Твою мать ебали турки'],
    ['1689899204', date('d.m.y', 1689899204), 'DateTimeInterface::format() - Возвращает дату, отформатированную согласно переданному формату. gmdate() - Форматирует дату/время по Гринвичу. idate() - Преобразует локальное время/дату в целое число']
);

// GAMES

$sql = 'SELECT * FROM games';
$stmt = $db->prepare($sql);
$stmt->execute();

$games = $stmt->fetchAll();

// NEWS

$sql = 'SELECT * FROM news';
$stmt = $db->prepare($sql);
$stmt->execute();

$news = $stmt->fetchAll();

$aut = isset($_SESSION['authorization']) ? $_SESSION['authorization'] : '';

echo $twig->render('index.html', [
    'title' => 'Главная',
    'news'=>$news,
    'games'=>$games,
    'aut'=>$aut
]);