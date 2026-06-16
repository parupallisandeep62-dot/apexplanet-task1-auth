<?php
require 'db_connect.php';

if (isset($_SESSION['user'])) {
    header('Location: dashboard.php');
    exit;
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email    = trim($_POST['email']    ?? '');
    $password = trim($_POST['password'] ?? '');

    if (!$email || !$password) {
        $error = 'Email and password are required.';
    } else {
        $stmt = $conn->prepare('SELECT id, name, password, role FROM users WHERE email = ?');
        $stmt->bind_param('s', $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($row = $result->fetch_assoc()) {
            if (password_verify($password, $row['password'])) {
                $_SESSION['user']    = $row['name'];
                $_SESSION['user_id'] = $row['id'];
                $_SESSION['role']    = $row['role'];
                header('Location: dashboard.php');
                exit;
            } else {
                $error = 'Incorrect password.';
            }
        } else {
            $error = 'No account found with that email.';
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
    <title>Login – ApexPlanet</title>
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
        .error { background: #3a1a1a; border: 1px solid #c0392b; color: #e74c3c; }
        .link { text-align: center; margin-top: 20px; font-size: 0.9rem; color: #888; }
        .link a { color: #7c6ff7; text-decoration: none; }
        .link a:hover { text-decoration: underline; }
    </style>
</head>
<body>
<div class="card">
    <h1>ApexPlanet</h1>
    <p class="subtitle">Sign in to your account</p>

    <?php if ($error): ?><div class="msg error"><?= htmlspecialchars($error) ?></div><?php endif; ?>

    <form method="POST" action="">
        <label for="email">Email</label>
        <input type="email"    id="email"    name="email"    placeholder="jane@example.com" required>
        <label for="password">Password</label>
        <input type="password" id="password" name="password" placeholder="Your password"    required>
        <button type="submit">Login</button>
    </form>

    <div class="link">Don't have an account? <a href="register.php">Register</a></div>
</div>
</body>
</html>
