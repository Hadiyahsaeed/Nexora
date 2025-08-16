<?php
include('includes/db.php');
session_start();

$user_id = 1; // Dummy user ID

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['product_id'])) {
    $product_id = intval($_POST['product_id']);
    $quantity = intval($_POST['quantity']);

    // Check if product is already in cart
    $checkStmt = $conn->prepare("SELECT * FROM cart WHERE user_id = ? AND product_id = ?");
    $checkStmt->bind_param("ii", $user_id, $product_id);
    $checkStmt->execute();
    $result = $checkStmt->get_result();

    if ($result->num_rows > 0) {
        // Update quantity if already in cart
        $updateStmt = $conn->prepare("UPDATE cart SET quantity = quantity + ? WHERE user_id = ? AND product_id = ?");
        $updateStmt->bind_param("iii", $quantity, $user_id, $product_id);
        $updateStmt->execute();
    } else {
        // Insert new cart item
        $insertStmt = $conn->prepare("INSERT INTO cart (user_id, product_id, quantity) VALUES (?, ?, ?)");
        $insertStmt->bind_param("iii", $user_id, $product_id, $quantity);
        $insertStmt->execute();
    }
}

header("Location: cart.php");
exit;
