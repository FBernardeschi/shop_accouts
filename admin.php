<?php
session_start();

if(!isset($_SESSION['authorization'])) {
    header('Location: login.php');
    die();
}

require_once 'vendor/autoload.php';

$loader = new \Twig\Loader\FilesystemLoader('templates');
$twig = new \Twig\Environment($loader);

$db = require_once 'PDO.php';

if(isset($_SERVER['REQUEST_METHOD']) && $_SERVER['REQUEST_METHOD'] === 'POST') {
    if(isset($_POST['add_game'])) {
        $name = $_POST['name_game'];
        $photo = $_FILES['photo']['tmp_name'];
        $file_name = 'photo_' . time() . '.jpg';

        move_uploaded_file($_FILES["photo"]["tmp_name"], 'upload/' . $file_name);

        $sql = 'INSERT INTO games (name_games, translit_name_games, name_photo) VALUES (:name_games, :translit_name_games, :name_photo)';
        $stmt = $db->prepare($sql);
        $stmt->execute(array(
            'name_games'=>$name,
            'translit_name_games'=>translit($name),
            'name_photo'=>$file_name
        ));
    };

    if(isset($_POST['add_acc'])) {
        $name_acc = $_POST['name_acc'];
        $about = $_POST['about'];
        $price = $_POST['price'];
        $acc_game = $_POST['acc_game'];

        $sql = 'INSERT INTO account (name_acc, about, account_games_id, price) VALUES (:name_acc, :about, :account_games_id, :price)';
        $stmt = $db->prepare($sql);
        $stmt->execute(array(
            'name_acc'=>$name_acc,
            'about'=>$about,
            'account_games_id'=>$acc_game,
            'price'=>$price
        ));
    };

    if(isset($_POST['add_news'])) {
        $text_news = $_POST['text-news'];

        $sql = 'INSERT INTO news (news_text, date_time_sec, date_time) VALUES (:news_text, :date_time_sec, :date_time)';
        $stmt = $db->prepare($sql);
        $time_now = time();
        $stmt->execute(array(
            'news_text'=>$text_news,
            'date_time_sec'=>$time_now,
            'date_time'=>date('d.m.y', $time_now)
        ));
    };

    if(isset($_POST['add_user'])) {
        $user = $_POST['user'];
        $password = $_POST['password'];

        $sql = 'INSERT INTO users (u_login, u_password) VALUES (:u_login, :u_password)';
        $stmt = $db->prepare($sql);
        $stmt->execute(array(
            'u_login'=>$user,
            'u_password'=>password_hash($password, PASSWORD_DEFAULT)
        ));
    };

};

if(isset($_GET['name']) && isset($_GET['id'])) {
    $id = $_GET['id'];
    $table_name = $_GET['name'];

    $sql = 'DELETE FROM ' . $table_name . ' WHERE id = :id';
    $stmt = $db->prepare($sql);
    $stmt->execute(array(
        'id'=>$id
    ));
};

// games

$sql = 'SELECT * FROM games';
$stmt = $db->query($sql);
$games = $stmt->fetchAll();

// accounts

$sql = 'SELECT * FROM account';
$stmt = $db->query($sql);
$accounts = $stmt->fetchAll();

// users

$sql = 'SELECT * FROM users';
$stmt = $db->query($sql);
$users = $stmt->fetchAll();

// news

$sql = 'SELECT * FROM news';
$stmt = $db->query($sql);
$news = $stmt->fetchAll();


$aut = isset($_SESSION['authorization']) ? $_SESSION['authorization'] : '';

echo $twig->render('admin.html', array(
    'title'=>'Админ панель',
    'games'=>$games,
    'games_massiv'=>ass_massiv($games),
    'accounts'=>$accounts,
    'users'=>$users,
    'news'=>$news,
    'aut'=>$aut
));

function ass_massiv($value) {
    $result = [];
    foreach($value as $v) {
        $result[$v['id']] = $v['name_games']; 
    };

    return $result;
}

function translit($value) {
	$converter = array(
		'а' => 'a',    'б' => 'b',    'в' => 'v',    'г' => 'g',    'д' => 'd',
		'е' => 'e',    'ё' => 'e',    'ж' => 'zh',   'з' => 'z',    'и' => 'i',
		'й' => 'y',    'к' => 'k',    'л' => 'l',    'м' => 'm',    'н' => 'n',
		'о' => 'o',    'п' => 'p',    'р' => 'r',    'с' => 's',    'т' => 't',
		'у' => 'u',    'ф' => 'f',    'х' => 'h',    'ц' => 'c',    'ч' => 'ch',
		'ш' => 'sh',   'щ' => 'sch',  'ь' => '',     'ы' => 'y',    'ъ' => '',
		'э' => 'e',    'ю' => 'yu',   'я' => 'ya',  ' ' => '-'
    );

    $value = trim($value);
    $value = mb_strtolower($value);
    $value = strtr($value, $converter);
    $value = mb_ereg_replace('[^-0-9a-z]', '', $value);
    $value = mb_ereg_replace('[-]+', '-', $value);
	return $value;
};