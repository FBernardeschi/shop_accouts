<?php
session_start();

if(isset($_SESSION['authorization']) && $_SESSION['authorization'] == 1) {
    header('Location: admin.php');
    die();
};

require_once 'vendor/autoload.php';

$loader = new \Twig\Loader\FilesystemLoader('templates');
$twig = new \Twig\Environment($loader);
$db = require_once 'PDO.php';

$error = '';

if(isset($_SERVER['REQUEST_METHOD']) && $_SERVER['REQUEST_METHOD'] == 'POST') {
    $login = $_POST['login'];
    $password = $_POST['password'];

    $sql = 'SELECT * FROM users WHERE u_login = :login';
    $stmt = $db->prepare($sql);
    $stmt->execute(array(
        'login'=>$login
    ));

    $account = $stmt->fetch();
    if($account && password_verify($password, $account['u_password'])) {
        $_SESSION['authorization'] = 1;
        header('Location: admin.php');
        die();
    } else {
        $error = 'Логин или пароль неверные!';
    }
}


echo $twig->render('login.html', array(
    'title'=>'Авторизуйтесь',
    'error'=>$error
));

function clear_input($val) {
    $val = trim($val);
    $val = htmlspecialchars($val);

    return $val;
}