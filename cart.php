<?php
include('includes/db.php');
include('includes/header.php');
session_start();

$user_id = 1; // Dummy user id for now

$sql = "SELECT p.id, p.name, p.price, p.image, c.quantity
        FROM cart c
        JOIN products p ON c.product_id = p.id
        WHERE c.user_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

$total = 0;
?>

<div class="container">
    <h2>Your Cart</h2>
    <?php if ($result->num_rows > 0): ?>
        <div class="products-grid"> <?php while ($row = $result->fetch_assoc()): ?>
            <?php $total += $row['price'] * $row['quantity']; ?>
            <div class="product-card">
                <img src="assets/images/<?= htmlspecialchars($row['image']) ?>" class="product-image" alt="<?= htmlspecialchars($row['name']) ?>">
                <div class="product-info">
                    <h3><?= htmlspecialchars($row['name']) ?></h3>
                    <p class="product-price">Price: <?= number_format($row['price'], 2) ?> Rs</p>
                    <p>Quantity: <?= $row['quantity'] ?></p>

                    <form action="remove_from_cart.php" method="POST">
                        <input type="hidden" name="product_id" value="<?= $row['id'] ?>">
                        <button type="submit" class="remove-btn">Remove Item</button>
                    </form>
                </div>
            </div>
        <?php endwhile; ?>
        </div>

        <div class="cart-summary">
            <h3>Total: <?= number_format($total, 2) ?> Rs</h3>
        </div>

        <h3 style="text-align: center; margin-top: 40px; color: #333;">Enter Your Details for Checkout</h3>
        <form action="checkout.php" method="POST" class="checkout-form">
            <label for="customer_name">Your Name:</label>
            <input type="text" id="customer_name" name="customer_name" placeholder="Full Name" required>

            <label for="email">Your Email:</label>
            <input type="email" id="email" name="email" placeholder="Email Address" required>

            <label for="phone">Your Phone Number:</label>
            <input type="tel" id="phone" name="phone" placeholder="Phone Number" required>

            <label for="address">Shipping Address:</label>
            <textarea id="address" name="address" placeholder="Shipping Address" required></textarea>

            <button type="submit">Confirm Order</button>
        </form>

    <?php else: ?>
        <p style="text-align: center; font-size: 1.2em; margin-top: 50px;">Your cart is empty. <a href="index.php" class="btn">Start Shopping!</a></p>
    <?php endif; ?>
</div>

