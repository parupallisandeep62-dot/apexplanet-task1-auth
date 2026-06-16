<?php
require 'db_connect.php';

if (!isset($_SESSION['user'])) {
    header('Location: login.php');
    exit;
}

$name = htmlspecialchars($_SESSION['user']);
$role = htmlspecialchars($_SESSION['role']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard – ApexPlanet</title>
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body { font-family: 'Segoe UI', sans-serif; background: #0f0f1a; color: #e0e0e0; min-height: 100vh; }
        header { background: #1a1a2e; border-bottom: 1px solid #2a2a4a; padding: 16px 32px; display: flex; align-items: center; justify-content: space-between; }
        header h1 { font-size: 1.4rem; color: #7c6ff7; }
        header a { color: #e74c3c; text-decoration: none; font-size: 0.9rem; font-weight: 600; padding: 8px 16px; border: 1px solid #e74c3c; border-radius: 6px; transition: all 0.2s; }
        header a:hover { background: #e74c3c; color: #fff; }
        main { max-width: 900px; margin: 60px auto; padding: 0 24px; }
        .welcome { background: #1a1a2e; border: 1px solid #2a2a4a; border-radius: 12px; padding: 40px; margin-bottom: 28px; }
        .welcome h2 { font-size: 2rem; color: #7c6ff7; margin-bottom: 8px; }
        .welcome p { color: #888; font-size: 1rem; }
        .badge { display: inline-block; margin-top: 16px; background: #7c6ff7; color: #fff; padding: 4px 14px; border-radius: 20px; font-size: 0.8rem; font-weight: 600; letter-spacing: 0.05em; text-transform: uppercase; }
        .cards { display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 20px; }
        .stat-card { background: #1a1a2e; border: 1px solid #2a2a4a; border-radius: 12px; padding: 28px; text-align: center; }
        .stat-card .value { font-size: 2rem; font-weight: 700; color: #7c6ff7; }
        .stat-card .label { color: #888; font-size: 0.85rem; margin-top: 6px; }
    </style>
</head>
<body>
<header>
    <h1>ApexPlanet</h1>
    <a href="logout.php">Logout</a>
</header>
<main>
    <div class="welcome">
        <h2>Welcome, <?= $name ?>!</h2>
        <p>You are logged in to your ApexPlanet account.</p>
        <span class="badge">Role: <?= $role ?></span>
    </div>
    <div class="cards">
        <div class="stat-card"><div class="value">Task 1</div><div class="label">ApexPlanet Auth</div></div>
        <div class="stat-card"><div class="value">PHP</div><div class="label">Language</div></div>
        <div class="stat-card"><div class="value">MySQLi</div><div class="label">Database Driver</div></div>
    </div>
</main>
</body>
</html>
