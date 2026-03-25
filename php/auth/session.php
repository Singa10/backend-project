<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

function isLoggedIn() {
    return isset($_SESSION['user_id']);
}

function isAdmin() {
    return isset($_SESSION['role']) && $_SESSION['role'] === 'admin';
}

function requireLogin() {
    if (!isLoggedIn()) {
        header("Location: ../../docs/login.html");
        exit();
    }
}

function requireAdmin() {
    if (!isAdmin()) {
        header("Location: ../../docs/login.html");
        exit();
    }
}

function getUserId() {
    return $_SESSION['user_id'] ?? null;
}

function getUsername() {
    return $_SESSION['username'] ?? null;
}

function getUserRole() {
    return $_SESSION['role'] ?? null;
}

function getUserEmail() {
    return $_SESSION['email'] ?? null;
}
?>