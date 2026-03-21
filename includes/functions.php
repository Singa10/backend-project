<?php
// =============================================
// COMMON HELPER FUNCTIONS — TechBooks
// =============================================
// Reusable functions used across the project
// Include this file wherever you need these helpers
// =============================================

// =============================================
// 1. SANITIZE USER INPUT
// Removes whitespace, backslashes, and HTML tags
// =============================================
function sanitize($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data, ENT_QUOTES, 'UTF-8');
    return $data;
}

// =============================================
// 2. REDIRECT WITH MESSAGE
// Stores a message in session and redirects
// Usage: redirectWithMessage('page.html', 'success', 'Done!')
// =============================================
function redirectWithMessage($url, $type, $message) {
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    $_SESSION[$type] = $message;
    header("Location: $url");
    exit();
}

// =============================================
// 3. DISPLAY ERROR MESSAGE (HTML)
// Checks session for 'error' and returns HTML
// =============================================
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

// =============================================
// 4. DISPLAY SUCCESS MESSAGE (HTML)
// Checks session for 'success' and returns HTML
// =============================================
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

// =============================================
// 5. FORMAT PRICE
// Converts number to dollar format
// Usage: formatPrice(49.99) → "$49.99"
// =============================================
function formatPrice($price) {
    return "$" . number_format((float)$price, 2);
}

// =============================================
// 6. GENERATE STAR RATING HTML
// Converts rating number to star icons
// Usage: generateStars(4.5) → ★★★★½
// =============================================
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

// =============================================
// 7. VALIDATE EMAIL
// Returns true if email is valid format
// =============================================
function isValidEmail($email) {
    return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
}