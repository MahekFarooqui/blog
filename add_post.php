<?php
require 'config.php';
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit;
}
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['title']);
    $content = trim($_POST['content']);
    if ($title !== '' && $content !== '') {
        $stmt = $conn->prepare("INSERT INTO posts (title, content) VALUES (?, ?)");
        $stmt->bind_param("ss", $title, $content);
        $stmt->execute();
        header("Location: index.php");
        exit;
    }
}
?>
<!DOCTYPE html>
<html>
<head><title>Add Post</title><link rel="stylesheet" href="style.css"></head>
<body>
    <h2>Add New Post</h2>
    <form method="POST">
        <input type="text" name="title" placeholder="Title" required>
        <textarea name="content" placeholder="Content" rows="6" required></textarea>
        <button type="submit">Publish</button>
    </form>
    <a href="index.php">Back to posts</a>
</body>
</html>