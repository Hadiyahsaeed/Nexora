<?php
include('includes/db.php');
include('includes/header.php');
session_start();
$user_id = 1;
$stmt = $conn->prepare("
  SELECT p.id, p.name, p.description, p.price, p.image, p.quantity
  FROM wishlist w
  JOIN products p ON w.product_id = p.id
  WHERE w.user_id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
?>

<div class="container">
  <h2>Your Wishlist</h2>
  <?php if ($result->num_rows > 0): ?>
    <div class="products-grid">
      <?php while ($item = $result->fetch_assoc()): ?>
        <div class="product-card">
          <img src="assets/images/<?= htmlspecialchars($item['image']) ?>" class="product-image" alt="<?= htmlspecialchars($item['name']) ?>">
          <h3><?= htmlspecialchars($item['name']) ?></h3>
          <p class="product-description"><?= htmlspecialchars($item['description']) ?></p>
          <p class="product-price"><?= htmlspecialchars($item['price']) ?> Rs</p>
          <?php if ($item['quantity'] == 0): ?>
            <p class="out-of-stock">Out of Stock</p>
          <?php endif; ?>
          <form action="remove_from_wishlist.php" method="POST">
            <input type="hidden" name="product_id" value="<?= $item['id'] ?>">
            <button type="submit" class="remove-btn">Remove</button>
          </form>
        </div>
      <?php endwhile; ?>
    </div>
  <?php else: ?>
    <p>You haven't added anything to your wishlist yet.</p>
  <?php endif; ?>
</div>

