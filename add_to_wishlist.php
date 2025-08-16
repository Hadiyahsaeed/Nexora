<?php
session_start();
include('includes/db.php');

$user_id = 1;

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['product_id'])) {
    $product_id = intval($_POST['product_id']);

    // Check if already in wishlist
    $stmt = $conn->prepare("SELECT * FROM wishlist WHERE user_id = ? AND product_id = ?");
    $stmt->bind_param("ii", $user_id, $product_id);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows == 0) {
        $stmt = $conn->prepare("INSERT INTO wishlist (user_id, product_id) VALUES (?, ?)");
        $stmt->bind_param("ii", $user_id, $product_id);
        $stmt->execute();
    }

    // 🔁 Redirect to wishlist page
    header("Location: wishlist.php");
    exit;
}
?>
