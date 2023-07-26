<?php
session_start();

require_once 'vendor/autoload.php';

$loader = new \Twig\Loader\FilesystemLoader('templates');
$twig = new \Twig\Environment($loader);
$db = require_once 'PDO.php';

$aut = isset($_SESSION['authorization']) ? $_SESSION['authorization'] : '';

if(isset($_SERVER['REQUEST_METHOD']) && $_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['game'])) {
    $game = $_GET['game'];

    $sql = 'SELECT id, name_games, name_photo FROM games WHERE translit_name_games = :translit_name_games';
    $stmt = $db->prepare($sql);
    $stmt->execute(array(
        'translit_name_games'=>$game
    ));
    $game = $stmt->fetch();

    if($game) {
        $id = $game['id'];
        $photo = $game['name_photo'];
        $name = $game['name_games'];
        $sql = 'SELECT * FROM account WHERE account_games_id = :account_games_id';
        $stmt = $db->prepare($sql);
        $stmt->execute(array(
            'account_games_id'=>$id
        ));

        $accounts = $stmt->fetchAll();

        echo $twig->render('games.html', array(
            'title'=>'GAMES',
            'accounts'=>$accounts,
            'photo'=>$photo,
            'name'=>$name,
            'aut'=>$aut
        ));
        die();
    }
}

echo $twig->render('games.html', array(
    'title'=>'GAMES',
    'accounts'=>[],
    'photo'=>'no_photo.png',
    'name'=>'Игра не выбрана',
    'aut'=>$aut
));