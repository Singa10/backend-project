<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);


if (!file_exists('../../config/db.php')) {
    die("ERROR: config/db.php not found!");
}
if (!file_exists('session.php')) {
    die("ERROR: session.php not found!");
}

require_once '../../config/db.php';
require_once 'session.php';


if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: ../../docs/login.html");
    exit();
}

$username = trim(htmlspecialchars($_POST['username'] ?? ''));
$password = $_POST['password'] ?? '';


if (empty($username) && empty($password)) {
    die("ERROR: No form data received. Check form has name attributes and method=POST");
}


$errors = [];

if (empty($username)) {
    $errors[] = "Username is required.";
}

if (empty($password)) {
    $errors[] = "Password is required.";
}

if (!empty($errors)) {
    $_SESSION['error'] = implode("<br>", $errors);
    header("Location: ../../docs/login.html");
    exit();
}

try {

    $stmt = $pdo->prepare("SELECT id, username, email, password, role FROM users WHERE username = ?");
    $stmt->execute([$username]);
    $user = $stmt->fetch();


    if (!$user || !password_verify($password, $user['password'])) {
        $_SESSION['error'] = "Invalid username or password.";
        header("Location: ../../docs/login.html");
        exit();
    }

    $_SESSION['user_id'] = $user['id'];
    $_SESSION['username'] = $user['username'];
    $_SESSION['email'] = $user['email'];
    $_SESSION['role'] = $user['role'];


    if ($user['role'] === 'admin') {
        $_SESSION['success'] = "Welcome back, Admin!";
        header("Location: ../../docs/admin.html");
    } else {
        $_SESSION['success'] = "Welcome back, " . $user['username'] . "!";
        header("Location: ../../docs/shop.html");
    }
    exit();

} catch (PDOException $e) {
    die("DATABASE ERROR: " . $e->getMessage());
}
?>