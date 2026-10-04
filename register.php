<?php
require 'config.php';
$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $password = $_POST['password'];
    $confirm  = $_POST['confirm_password'];

    if ($username === '' || $password === '' || $confirm === '') {
        $error = "Please fill in all fields.";
    } elseif (!preg_match('/^[A-Za-z0-9_]{3,20}$/', $username)) {
        $error = "Username must be 3-20 characters (letters, numbers, underscore only).";
    } elseif (strlen($password) < 6) {
        $error = "Password must be at least 6 characters.";
    } elseif ($password !== $confirm) {
        $error = "Passwords do not match.";
    } else {
        $check = $conn->prepare("SELECT id FROM users WHERE username = ?");
        $check->bind_param("s", $username);
        $check->execute();
        if ($check->get_result()->num_rows > 0) {
            $error = "Username already taken.";
        } else {
            $hashed = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $conn->prepare("INSERT INTO users (username, password, role) VALUES (?, ?, 'editor')");
            $stmt->bind_param("ss", $username, $hashed);
            $stmt->execute();
            header("Location: login.php");
            exit;
        }
    }
}
?>
<!DOCTYPE html>
<html>
<head><title>Register</title><link rel="stylesheet" href="style.css"></head>
<body>
<div class="container">
    <h2>Register</h2>
    <?php if ($error) echo "<p style='color:red;'>" . htmlspecialchars($error) . "</p>"; ?>
    <form method="POST">
        <input type="text" name="username" placeholder="Username" required minlength="3" maxlength="20" pattern="[A-Za-z0-9_]+" title="Letters, numbers, underscore only">
        <input type="password" name="password" placeholder="Password" required minlength="6">
        <input type="password" name="confirm_password" placeholder="Confirm Password" required minlength="6">
        <button type="submit">Register</button>
    </form>
    <p>Already have an account? <a href="login.php">Login</a></p>
</div>
</body>
</html>