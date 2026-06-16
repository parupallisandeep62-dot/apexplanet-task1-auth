<?php
require 'db_connect.php';

$error   = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name     = trim($_POST['name']   ?? '');
    $email    = trim($_POST['email']  ?? '');
    $password = trim($_POST['password'] ?? '');

    if (!$name || !$email || !$password) {
        $error = 'All fields are required.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = 'Invalid email address.';
    } elseif (strlen($password) < 6) {
        $error = 'Password must be at least 6 characters.';
    } else {
        $hashed = password_hash($password, PASSWORD_DEFAULT);
        $stmt   = $conn->prepare('INSERT INTO users (name, email, password) VALUES (?, ?, ?)');
        $stmt->bind_param('sss', $name, $email, $hashed);

        if ($stmt->execute()) {
            $success = 'Account created! <a href="login.php">Login here</a>.';
        } else {
            $error = 'Email already registered.';
        }
        $stmt->close();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register – ApexPlanet</title>
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body { font-family: 'Segoe UI', sans-serif; background: #0f0f1a; color: #e0e0e0; min-height: 100vh; display: flex; align-items: center; justify-content: center; }
        .card { background: #1a1a2e; border: 1px solid #2a2a4a; border-radius: 12px; padding: 40px; width: 100%; max-width: 420px; }
        h1 { text-align: center; margin-bottom: 8px; font-size: 1.8rem; color: #7c6ff7; }
        .subtitle { text-align: center; color: #888; margin-bottom: 28px; font-size: 0.9rem; }
        label { display: block; font-size: 0.85rem; color: #aaa; margin-bottom: 6px; }
        input { width: 100%; padding: 12px 14px; background: #0f0f1a; border: 1px solid #333; border-radius: 8px; color: #e0e0e0; font-size: 1rem; margin-bottom: 18px; outline: none; transition: border 0.2s; }
        input:focus { border-color: #7c6ff7; }
        button { width: 100%; padding: 13px; background: #7c6ff7; border: none; border-radius: 8px; color: #fff; font-size: 1rem; font-weight: 600; cursor: pointer; transition: background 0.2s; }
        button:hover { background: #6a5ee0; }
        .msg { padding: 10px 14px; border-radius: 8px; margin-bottom: 18px; font-size: 0.9rem; }
        .error   { background: #3a1a1a; border: 1px solid #c0392b; color: #e74c3c; }
        .success { background: #1a3a1a; border: 1px solid #27ae60; color: #2ecc71; }
        .link { text-align: center; margin-top: 20px; font-size: 0.9rem; color: #888; }
        .link a { color: #7c6ff7; text-decoration: none; }
        .link a:hover { text-decoration: underline; }
    </style>
</head>
<body>
<div class="card">
    <h1>ApexPlanet</h1>
    <p class="subtitle">Create your account</p>

    <?php if ($error):   ?><div class="msg error"><?= htmlspecialchars($error) ?></div><?php endif; ?>
    <?php if ($success): ?><div class="msg success"><?= $success ?></div><?php endif; ?>

    <?php if (!$success): ?>
    <form method="POST" action="">
        <label for="name">Full Name</label>
        <input type="text"     id="name"     name="name"     placeholder="Jane Doe"          required>
        <label for="email">Email</label>
        <input type="email"    id="email"    name="email"    placeholder="jane@example.com"   required>
        <label for="password">Password</label>
        <input type="password" id="password" name="password" placeholder="Min. 6 characters"  required>
        <button type="submit">Create Account</button>
    </form>
    <?php endif; ?>

    <div class="link">Already have an account? <a href="login.php">Login</a></div>
</div>
</body>
</html>
