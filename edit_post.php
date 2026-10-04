<?php
require 'config.php';
if (!isLoggedIn()) {
    header("Location: login.php");
    exit;
}
$id = intval($_GET['id'] ?? 0);
$stmt = $conn->prepare("SELECT * FROM posts WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$post = $stmt->get_result()->fetch_assoc();

if (!$post) {
    die("Post not found.");
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
        $update = $conn->prepare("UPDATE posts SET title = ?, content = ? WHERE id = ?");
        $update->bind_param("ssi", $title, $content, $id);
        $update->execute();
        header("Location: index.php");
        exit;
    }
    $post['title'] = $title;
    $post['content'] = $content;
}
?>
<!DOCTYPE html>
<html>
<head><title>Edit Post</title><link rel="stylesheet" href="style.css"></head>
<body>
<div class="container">
    <h2>Edit Post</h2>
    <?php if ($error) echo "<p style='color:red;'>" . htmlspecialchars($error) . "</p>"; ?>
    <form method="POST">
        <input type="text" name="title" value="<?php echo htmlspecialchars($post['title']); ?>" required maxlength="255">
        <textarea name="content" rows="6" required minlength="5"><?php echo htmlspecialchars($post['content']); ?></textarea>
        <button type="submit">Update</button>
    </form>
    <a href="index.php">Back to posts</a>
</div>
</body>
</html>