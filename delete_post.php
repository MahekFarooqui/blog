<?php
require 'config.php';
if (!isAdmin()) {
    header("Location: index.php?error=unauthorized");
    exit;
}
$id = intval($_GET['id'] ?? 0);
$stmt = $conn->prepare("DELETE FROM posts WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
header("Location: index.php");
exit;
?>