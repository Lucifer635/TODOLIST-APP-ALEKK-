<?php
if (session_status() === PHP_SESSION_NONE) session_start();
require_once __DIR__ . '/functions.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ToDoList App</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <div class="container">
            <a class="navbar-brand" href="index.php">ToDoList App ALEKK</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav me-auto">
                <li class="nav-item">
                    <a class="nav-link active" href="index.php">Home</a>
                </li>
                <!-- Other nav items -->
            </ul>
            <div class="d-flex align-items-center">
                <div class="dropdown">
                    <a href="#" class="d-flex align-items-center text-white text-decoration-none dropdown-toggle" id="dropdownUser" data-bs-toggle="dropdown" aria-expanded="false">
                        <!-- Mini profile section -->
                        <?php if (isset($_SESSION['user_id'])): ?>
                            <div class="me-3 text-end d-none d-sm-block d-flex align-items-center">
                            <img src="<?= htmlspecialchars(getProfilePicture($_SESSION['user_id'])) ?>" class="img-thumbnail rounded-circle me-2" width="32" height="32" alt="Profile">
                                <div>
                                    <div class="fw-bold">Welcome, <?= htmlspecialchars($_SESSION['username'] ?? 'User') ?></div>
                                    <small class="text-muted"><?= htmlspecialchars($_SESSION['email'] ?? '') ?></small>
                                </div>
                            </div>
                        <?php endif; ?>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-dark dropdown-menu-end" aria-labelledby="dropdownUser">
                        <li><a class="dropdown-item" href="profile.php">Profile</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item" href="logout.php">Log out</a></li>
                    </ul>
                </div>
            </div>
        </div>
        </div>  
    </nav>
    <div class="container mt-4">