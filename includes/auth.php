<?php
function isLoggedIn() {
    return isset($_SESSION['user_id']);
}

function isAdmin() {
    return isset($_SESSION['role']) && $_SESSION['role'] === 'admin';
}

function requireLogin() {
    if (!isLoggedIn()) {
        header("Location: ../index.html");
        exit();
    }
}

function requireAdmin() {
    requireLogin();
    if (!isAdmin()) {
        header("Location: ../dashboard.php");
        exit();
    }
}

function loginUser($pdo, $registration_number, $password = null) {
    if ($password) {
        // Login para administradores
        $stmt = $pdo->prepare("SELECT * FROM users WHERE registration_number = ? AND role = 'admin'");
        $stmt->execute([$registration_number]);
        $user = $stmt->fetch();
        
        if ($user && password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['registration_number'] = $user['registration_number'];
            $_SESSION['name'] = $user['name'];
            $_SESSION['role'] = $user['role'];
            return true;
        }
    } else {
        // Login para estudiantes
        $stmt = $pdo->prepare("SELECT * FROM users WHERE registration_number = ? AND role = 'student'");
        $stmt->execute([$registration_number]);
        $user = $stmt->fetch();
        
        if ($user) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['registration_number'] = $user['registration_number'];
            $_SESSION['name'] = $user['name'];
            $_SESSION['role'] = $user['role'];
            return true;
        }
    }
    return false;
}

function logoutUser() {
    session_destroy();
    header("Location: index.html");
    exit();
}
?>
