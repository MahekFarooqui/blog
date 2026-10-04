<?php
require 'config.php';
if (!isLoggedIn()) {
    header("Location: login.php");
    exit;
}
$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['title']);
    $content = trim($_POST['content']);

    if ($title === '' || $content === '') {
        $error = "Both title and content are required.";
    } elseif (strlen($title) > 255) {
        $error = "Title is too long (max 255 characters).";
    } elseif (strlen($content) < 5) {
        $error = "Content must be at least 5 characters.";
    } else {
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
<div class="container">
    <h2>Add New Post</h2>
    <?php if ($error) echo "<p style='color:red;'>" . htmlspecialchars($error) . "</p>"; ?>
    <form method="POST">
        <input type="text" name="title" placeholder="Title" required maxlength="255" value="<?php echo isset($title) ? htmlspecialchars($title) : ''; ?>">
        <textarea name="content" placeholder="Content" rows="6" required minlength="5"><?php echo isset($content) ? htmlspecialchars($content) : ''; ?></textarea>
        <button type="submit">Publish</button>
    </form>
    <a href="index.php">Back to posts</a>
</div>
</body>
</html>