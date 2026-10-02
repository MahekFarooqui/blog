<?php
require 'config.php';
$result = $conn->query("SELECT * FROM posts ORDER BY created_at DESC");
?>
<!DOCTYPE html>
<html>
<head><title>PHP Blog</title><link rel="stylesheet" href="style.css"></head>
<body>
    <h1>PHP Blog Project</h1>
    <nav>
        <?php if (isset($_SESSION['username'])): ?>
            <p>Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?> | <a href="logout.php">Logout</a></p>
            <a href="add_post.php">+ Add New Post</a>
        <?php else: ?>
            <a href="login.php">Login</a> | <a href="register.php">Register</a>
        <?php endif; ?>
    </nav>
    <hr>
    <?php while ($post = $result->fetch_assoc()): ?>
        <div class="post">
            <h2><?php echo htmlspecialchars($post['title']); ?></h2>
            <p><?php echo nl2br(htmlspecialchars($post['content'])); ?></p>
            <small>Posted on <?php echo $post['created_at']; ?></small>
            <?php if (isset($_SESSION['username'])): ?>
                <div>
                    <a href="edit_post.php?id=<?php echo $post['id']; ?>">Edit</a> |
                    <a href="delete_post.php?id=<?php echo $post['id']; ?>" onclick="return confirm('Delete this post?');">Delete</a>
                </div>
            <?php endif; ?>
        </div>
        <hr>
    <?php endwhile; ?>
</body>
</html>