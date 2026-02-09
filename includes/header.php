<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['user_id'])) {
    header("Location: /bhcis/login.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>BHCIS</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="/bhcis/assets/style.css" rel="stylesheet">
</head>
<body class="bg-light">

<nav class="navbar navbar-expand-lg navbar-dark bg-primary shadow-sm">
    <div class="container-fluid">
        <a class="navbar-brand fw-bold d-flex align-items-center gap-2" href="/bhcis/index.php">
            <div style="width:35px;height:35px;background:#fff;border-radius:50%;display:flex;justify-content:center;align-items:center;color:#0d6efd;font-weight:bold;font-size:16px;">
                L
            </div>
            BHCIS
        </a>

        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#bhcisNav">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="bhcisNav">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <li class="nav-item">
                    <a class="nav-link" href="/bhcis/index.php?page=dashboard">Dashboard</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="/bhcis/index.php?page=patients">Patients</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="/bhcis/index.php?page=consultations">Consultations</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="/bhcis/index.php?page=prenatal">Prenatal</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="/bhcis/index.php?page=immunization">Immunization</a>
                </li>
            </ul>

            <div class="d-flex align-items-center text-white small gap-3">
                <span class="navbar-text text-white p-0">
                    Welcome, <strong><?= htmlspecialchars($_SESSION['username'] ?? 'User') ?></strong>
                </span>
                <span class="badge bg-light text-primary">
                    <?= htmlspecialchars($_SESSION['role'] ?? 'User') ?>
                </span>
                <a href="/bhcis/auth/logout.php" class="btn btn-sm btn-outline-light">
                    Logout
                </a>
            </div>
        </div>
    </div>
</nav>

<div class="container-fluid mt-4">