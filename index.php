<?php
require 'config.php';

$search = isset($_GET['search']) ? trim($_GET['search']) : '';
$postsPerPage = 5;
$page = isset($_GET['page']) ? max(1, intval($_GET['page'])) : 1;
$offset = ($page - 1) * $postsPerPage;

if ($search !== '') {
    $searchTerm = "%$search%";
    $countStmt = $conn->prepare("SELECT COUNT(*) AS total FROM posts WHERE title LIKE ? OR content LIKE ?");
    $countStmt->bind_param("ss", $searchTerm, $searchTerm);
    $countStmt->execute();
    $totalPosts = $countStmt->get_result()->fetch_assoc()['total'];

    $stmt = $conn->prepare("SELECT * FROM posts WHERE title LIKE ? OR content LIKE ? ORDER BY created_at DESC LIMIT ? OFFSET ?");
    $stmt->bind_param("ssii", $searchTerm, $searchTerm, $postsPerPage, $offset);
} else {
    $totalPosts = $conn->query("SELECT COUNT(*) AS total FROM posts")->fetch_assoc()['total'];

    $stmt = $conn->prepare("SELECT * FROM posts ORDER BY created_at DESC LIMIT ? OFFSET ?");
    $stmt->bind_param("ii", $postsPerPage, $offset);
}
$stmt->execute();
$result = $stmt->get_result();
$totalPages = ceil($totalPosts / $postsPerPage);
?>
<!DOCTYPE html>
<html>
<head>
    <title>PHP Blog</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<div class="container">
    <h1>PHP Blog Project</h1>

    <?php if (isset($_GET['error'])): ?>
        <p style="color:red;">
            <?php
            if ($_GET['error'] === 'unauthorized') echo "You don't have permission to do that.";
            elseif ($_GET['error'] === 'notfound') echo "That post doesn't exist.";
            ?>
        </p>
    <?php endif; ?>

    <nav>
        <?php if (isLoggedIn()): ?>
            <p>Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?> (<?php echo htmlspecialchars($_SESSION['role']); ?>) | <a href="logout.php">Logout</a></p>
            <a class="btn" href="add_post.php">+ Add New Post</a>
        <?php else: ?>
            <a href="login.php">Login</a> | <a href="register.php">Register</a>
        <?php endif; ?>
    </nav>

    <form method="GET" action="index.php" class="search-form">
        <input type="text" name="search" placeholder="Search posts..." value="<?php echo htmlspecialchars($search); ?>">
        <button type="submit">Search</button>
        <?php if ($search !== ''): ?>
            <a href="index.php" class="clear-link">Clear</a>
        <?php endif; ?>
    </form>

    <hr>

    <?php if ($result->num_rows === 0): ?>
        <p>No posts found.</p>
    <?php endif; ?>

    <?php while ($post = $result->fetch_assoc()): ?>
        <div class="post">
            <h2><?php echo htmlspecialchars($post['title']); ?></h2>
            <p><?php echo nl2br(htmlspecialchars($post['content'])); ?></p>
            <small>Posted on <?php echo $post['created_at']; ?></small>
            <?php if (isLoggedIn()): ?>
                <div class="post-actions">
                    <a href="edit_post.php?id=<?php echo $post['id']; ?>">Edit</a>
                    <?php if (isAdmin()): ?>
                        | <a href="delete_post.php?id=<?php echo $post['id']; ?>" onclick="return confirm('Delete this post?');">Delete</a>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
        </div>
    <?php endwhile; ?>

    <?php if ($totalPages > 1): ?>
        <div class="pagination">
            <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                <a class="<?php echo $i === $page ? 'active' : ''; ?>"
                   href="index.php?page=<?php echo $i; ?><?php echo $search !== '' ? '&search=' . urlencode($search) : ''; ?>">
                    <?php echo $i; ?>
                </a>
            <?php endfor; ?>
        </div>
    <?php endif; ?>
</div>
</body>
</html>