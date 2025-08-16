<?php
include('includes/db.php');
session_start();

$download_links = []; // Array to store download links for digital products
$confirmed = false; // Initialize confirmed flag

if (isset($_GET['token'])) {
    $token = $_GET['token'];

    // MODIFIED: Check against the database for a valid pending order
    $stmt = $conn->prepare("SELECT * FROM pending_orders WHERE token = ?");
    $stmt->bind_param("s", $token);
    $stmt->execute();
    $result = $stmt->get_result();
    
    // Proceed only if a valid pending order is found in the database
    if ($result->num_rows > 0) {
        $db_order = $result->fetch_assoc();
        $order_session_data = json_decode($db_order['cart_data'], true);
        
        // Insert order into DB
        $insert_order_stmt = $conn->prepare("INSERT INTO orders (user_id, customer_name, total_amount, order_status, email, phone, address) VALUES (?, ?, ?, 'confirmed', ?, ?, ?)");
        $insert_order_stmt->bind_param("isdsss", $db_order['user_id'], $db_order['customer_name'], $db_order['total_amount'], $db_order['email'], $db_order['phone'], $db_order['address']);
        $insert_order_stmt->execute();
        $order_id = $insert_order_stmt->insert_id;

        // Reduce stock for physical products & clear cart for all
        foreach ($order_session_data as $item) {
            $product_id = $item['id'];
            $qty = $item['quantity'];
            $product_type = $item['product_type'] ?? 'physical'; // Default to physical if not set

            if ($product_type === 'physical') {
                // Only reduce stock for physical products
                $update_stock_stmt = $conn->prepare("UPDATE products SET quantity = quantity - ? WHERE id = ?");
                $update_stock_stmt->bind_param("ii", $qty, $product_id);
                $update_stock_stmt->execute();
            } elseif ($product_type === 'digital' && !empty($item['download_file'])) {
                // For digital products, generate a download link
                $download_links[] = "<a href=\"download.php?token={$token}&product_id={$product_id}\" class=\"btn\" style=\"margin-top:10px;\">Download " . htmlspecialchars($item['name']) . "</a>";
            }

            // Clear item from user's cart (for both physical and digital)
            $delete_cart_stmt = $conn->prepare("DELETE FROM cart WHERE user_id = ? AND product_id = ?");
            $delete_cart_stmt->bind_param("ii", $db_order['user_id'], $product_id);
            $delete_cart_stmt->execute();
        }

        // --- IMPORTANT: Removed DELETE FROM pending_orders table here as per user's request to revert that part of the logic ---

        // The session data is no longer needed after a successful database check
        if (isset($_SESSION['pending_order'])) {
            unset($_SESSION['pending_order']);
        }
        $confirmed = true;
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Order Confirmed</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <style>
        .download-section {
            margin-top: 30px;
            padding: 20px;
            background-color: #e6ffe6;
            border: 1px solid #c6ffc6;
            border-radius: 8px;
        }
        .download-section h3 {
            color: #28a745;
            margin-bottom: 15px;
        }
    </style>
</head>
<body>
    <div class="container" style="text-align:center; padding:40px;">
        <?php if ($confirmed): ?>
            <h2>🎉 Your order has been confirmed!</h2>
            <p>Thank you for shopping with Nexora.</p>
            <a href="index.php" class="btn">Back to Homepage</a>

            <?php if (!empty($download_links)): ?>
                <div class="download-section">
                    <h3>Your Downloads:</h3>
                    <?php foreach ($download_links as $link): ?>
                        <?= $link ?><br>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>

        <?php else: ?>
            <h2>Invalid or expired confirmation link.</h2>
            <p>Please ensure you clicked the latest confirmation link from your email.</p>
            <a href="index.php" class="btn">Go to Homepage</a>
        <?php endif; ?>
    </div>
</body>
</html>.