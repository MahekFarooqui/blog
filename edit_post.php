<?php
require 'config.php';
if (!isset($_SESSION['username'])) {
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

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['title']);
    $content = trim($_POST['content']);
    $update = $conn->prepare("UPDATE posts SET title = ?, content = ? WHERE id = ?");
    $update->bind_param("ssi", $title, $content, $id);
    $update->execute();
    header("Location: index.php");
    exit;
}
?>
<!DOCTYPE html>
<html>
<head><title>Edit Post</title><link rel="stylesheet" href="style.css"></head>
<body>
    <h2>Edit Post</h2>
    <form method="POST">
        <input type="text" name="title" value="<?php echo htmlspecialchars($post['title']); ?>" required>
        <textarea name="content" rows="6" required><?php echo htmlspecialchars($post['content']); ?></textarea>
        <button type="submit">Update</button>
    </form>
    <a href="index.php">Back to posts</a>
</body>
</html>