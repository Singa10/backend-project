<?php

function sanitize($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data, ENT_QUOTES, 'UTF-8');
    return $data;
}

function redirectWithMessage($url, $type, $message) {
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    $_SESSION[$type] = $message;
    header("Location: $url");
    exit();
}

function displayError() {
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    if (isset($_SESSION['error'])) {
        $error = $_SESSION['error'];
        unset($_SESSION['error']);
        return "<div class='alert alert-error'>
                    <i class='fas fa-exclamation-circle'></i>
                    <span>$error</span>
                </div>";
    }
    return "";
}

function displaySuccess() {
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    if (isset($_SESSION['success'])) {
        $success = $_SESSION['success'];
        unset($_SESSION['success']);
        return "<div class='alert alert-success'>
                    <i class='fas fa-check-circle'></i>
                    <span>$success</span>
                </div>";
    }
    return "";
}

function formatPrice($price) {
    return "$" . number_format((float)$price, 2);
}

function generateStars($rating) {
    $html = '';
    $rating = (float)$rating;
    $fullStars = floor($rating);
    $hasHalf = ($rating - $fullStars) >= 0.5;

    for ($i = 0; $i < 5; $i++) {
        if ($i < $fullStars) {
            $html .= '<i class="fas fa-star"></i>';
        } elseif ($i == $fullStars && $hasHalf) {
            $html .= '<i class="fas fa-star-half-alt"></i>';
        } else {
            $html .= '<i class="far fa-star"></i>';
        }
    }
    return $html;
}

function isValidEmail($email) {
    return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
}

function validatePassword($password) {
    if (strlen($password) < 6) {
        return "Password must be at least 6 characters.";
    }
    if (!preg_match('/[A-Z]/', $password)) {
        return "Password must contain at least 1 uppercase letter.";
    }
    if (!preg_match('/[a-z]/', $password)) {
        return "Password must contain at least 1 lowercase letter.";
    }
    if (!preg_match('/[0-9]/', $password)) {
        return "Password must contain at least 1 number.";
    }
    return true;
}

function validateUsername($username) {
    if (strlen($username) < 3 || strlen($username) > 50) {
        return "Username must be 3-50 characters.";
    }
    if (!preg_match('/^[a-zA-Z0-9_]+$/', $username)) {
        return "Username can only contain letters, numbers, and underscores.";
    }
    return true;
}

function truncateText($text, $maxLength = 100) {
    if (strlen($text) <= $maxLength) {
        return $text;
    }
    return substr($text, 0, $maxLength) . '...';
}

function formatDate($date, $format = 'M d, Y') {
    return date($format, strtotime($date));
}

function formatDateTime($date) {
    return date('M d, Y g:i A', strtotime($date));
}

function formatOrderId($id) {
    return "#ORD-" . str_pad($id, 3, '0', STR_PAD_LEFT);
}

function getStatusClass($status) {
    $classes = [
        'pending'    => 'status-pending',
        'processing' => 'status-processing',
        'shipped'    => 'status-shipped',
        'completed'  => 'status-completed',
        'cancelled'  => 'status-cancelled',
    ];
    return $classes[$status] ?? 'status-pending';
}

function sendJson($success, $message = '', $data = null) {
    header('Content-Type: application/json');
    $response = [
        'success' => $success,
        'message' => $message,
    ];
    if ($data !== null) {
        $response['data'] = $data;
    }
    echo json_encode($response);
    exit();
}

function isAjaxRequest() {
    return !empty($_SERVER['HTTP_X_REQUESTED_WITH']) &&
        strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest';
}

function getClientIP() {
    if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
        return $_SERVER['HTTP_CLIENT_IP'];
    } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
        return $_SERVER['HTTP_X_FORWARDED_FOR'];
    } else {
        return $_SERVER['REMOTE_ADDR'] ?? '0.0.0.0';
    }
}

function generateToken($length = 32) {
    return bin2hex(random_bytes($length / 2));
}
?>