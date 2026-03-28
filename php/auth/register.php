<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once '../../config/db.php';
require_once 'session.php';

$EMAIL_API_KEY = "83b51714da95412ca65a43eb6a17deb7";


$canSendEmail = false;
if (file_exists('../../includes/email_helper.php') && file_exists('../../config/mail.php')) {
    require_once '../../includes/email_helper.php';
    $canSendEmail = true;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: ../../docs/login.html");
    exit();
}

$username = trim(htmlspecialchars($_POST['username'] ?? ''));
$email = trim($_POST['email'] ?? '');
$password = $_POST['password'] ?? '';

if (empty($username) && empty($email) && empty($password)) {
    $_SESSION['error'] = "No form data received.";
    header("Location: ../../docs/login.html?form=register");
    exit();
}


function verifyEmailExists($email, $apiKey) {

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        return array('valid' => false, 'message' => 'Invalid email format.');
    }


    $domain = strtolower(substr($email, strrpos($email, '@') + 1));
    $blocked = array(
        'mailinator.com', 'guerrillamail.com', 'tempmail.com',
        'yopmail.com', 'trashmail.com', 'fakeinbox.com',
        'throwaway.email', 'temp-mail.org', '10minutemail.com',
        'maildrop.cc', 'discard.email', 'sharklasers.com'
    );
    if (in_array($domain, $blocked)) {
        return array('valid' => false, 'message' => 'Disposable emails are not allowed.');
    }


    $url = "https://emailvalidation.abstractapi.com/v1/?api_key=" . $apiKey . "&email=" . urlencode($email);

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 10);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    // If API fails, use fallback
    if ($response === false || $httpCode !== 200) {
        return verifyEmailFallback($email);
    }

    $result = json_decode($response, true);

    if (!$result) {
        return verifyEmailFallback($email);
    }


    if (isset($result['is_disposable_email']) && $result['is_disposable_email'] === true) {
        return array('valid' => false, 'message' => 'Disposable emails are not allowed.');
    }

    if (isset($result['is_valid_format']) && isset($result['is_valid_format']['value']) && $result['is_valid_format']['value'] === false) {
        return array('valid' => false, 'message' => 'Invalid email format.');
    }

    if (isset($result['deliverability'])) {
        if ($result['deliverability'] === 'UNDELIVERABLE') {
            return array('valid' => false, 'message' => 'This email does not exist. Please use a real email.');
        }
    }

    if (isset($result['is_smtp_valid']) && isset($result['is_smtp_valid']['value']) && $result['is_smtp_valid']['value'] === false) {
        return array('valid' => false, 'message' => 'This email could not be verified. Please use a different email.');
    }

    return array('valid' => true, 'message' => 'Email verified.');
}


function verifyEmailFallback($email) {
    $domain = strtolower(substr($email, strrpos($email, '@') + 1));

    $allowedDomains = array(
        'gmail.com',
        'outlook.com',
        'hotmail.com',
        'yahoo.com',
        'icloud.com',
        'protonmail.com'
    );

    if (in_array($domain, $allowedDomains)) {
        return array('valid' => true, 'message' => 'OK');
    }

    return array('valid' => false, 'message' => 'Could not verify email. Please use Gmail, Outlook, Yahoo, or iCloud.');
}


$errors = array();

// Username
if (empty($username)) {
    $errors[] = "Username is required.";
} elseif (strlen($username) < 3 || strlen($username) > 50) {
    $errors[] = "Username must be 3-50 characters.";
} elseif (!preg_match('/^[a-zA-Z0-9_]+$/', $username)) {
    $errors[] = "Username: only letters, numbers, underscores.";
}


if (empty($email)) {
    $errors[] = "Email is required.";
} else {
    $emailResult = verifyEmailExists($email, $EMAIL_API_KEY);
    if (!$emailResult['valid']) {
        $errors[] = $emailResult['message'];
    }
}


if (empty($password)) {
    $errors[] = "Password is required.";
} elseif (strlen($password) < 6) {
    $errors[] = "Password must be at least 6 characters.";
} elseif (!preg_match('/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).{6,}$/', $password)) {
    $errors[] = "Password: 1 uppercase, 1 lowercase, 1 number required.";
}


if (!empty($errors)) {
    $_SESSION['error'] = implode("<br>", $errors);
    header("Location: ../../docs/login.html?form=register");
    exit();
}

$email = htmlspecialchars($email);

try {

    $stmt = $pdo->prepare("SELECT id FROM users WHERE username = ?");
    $stmt->execute(array($username));
    if ($stmt->fetch()) {
        $_SESSION['error'] = "Username already taken.";
        header("Location: ../../docs/login.html?form=register");
        exit();
    }


    $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ?");
    $stmt->execute(array($email));
    if ($stmt->fetch()) {
        $_SESSION['error'] = "Email already registered.";
        header("Location: ../../docs/login.html?form=register");
        exit();
    }


    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
    $stmt = $pdo->prepare("INSERT INTO users (username, email, password, role) VALUES (?, ?, ?, 'user')");
    $result = $stmt->execute(array($username, $email, $hashedPassword));

    if (!$result) {
        $_SESSION['error'] = "Registration failed. Please try again.";
        header("Location: ../../docs/login.html?form=register");
        exit();
    }


    $userId = $pdo->lastInsertId();
    $_SESSION['user_id'] = $userId;
    $_SESSION['username'] = $username;
    $_SESSION['email'] = $email;
    $_SESSION['role'] = 'user';

    if ($canSendEmail) {
        sendWelcomeEmail($email, $username);
    }

    $_SESSION['success'] = "Registration successful! Welcome, $username!";
    header("Location: ../../docs/shop.html");
    exit();

} catch (PDOException $e) {
    $_SESSION['error'] = "Something went wrong. Please try again.";
    header("Location: ../../docs/login.html?form=register");
    exit();
}
?>