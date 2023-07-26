<?php

$dsn = 'mysql:localhost;port=3306;dbname=account;charset=utf8';

$db = new PDO($dsn, 'root', 'root');
$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
$db->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

$sql = 'CREATE TABLE IF NOT EXISTS users (
    id INT(11) PRIMARY KEY AUTO_INCREMENT,
    u_login VARCHAR(100),
    u_password VARCHAR(255)
)';

$db->exec($sql);

$sql = 'CREATE TABLE IF NOT EXISTS games (
    id INT(11) PRIMARY KEY AUTO_INCREMENT,
    name_games VARCHAR(255),
    translit_name_games VARCHAR(255),
    name_photo VARCHAR(100)
)';

$db->exec($sql);

$sql = 'CREATE TABLE IF NOT EXISTS account (
    id INT(11) PRIMARY KEY AUTO_INCREMENT,
    name_acc VARCHAR(100),
    about VARCHAR(300),
    account_games_id INT(11),
    price INT(11),
    FOREIGN KEY(account_games_id) REFERENCES games(id) ON DELETE CASCADE
)';

$db->exec($sql);

$sql = 'CREATE TABLE IF NOT EXISTS news (
    id INT(11) PRIMARY KEY AUTO_INCREMENT,
    date_time_sec INT(11),
    date_time VARCHAR(100),
    news_text VARCHAR(300)
)';

$db->exec($sql);

return $db;