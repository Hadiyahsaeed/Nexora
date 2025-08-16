<?php
include('includes/db.php');
include('includes/header.php');

$category_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

// Get category name
$category_stmt = $conn->prepare("SELECT name FROM categories WHERE id = ?");
$category_stmt->bind_param("i", $category_id);
$category_stmt->execute();
$category_result = $category_stmt->get_result();
$category_name = $category_result->fetch_assoc()['name'] ?? '';

// Get products
$product_stmt = $conn->prepare("SELECT * FROM products WHERE category_id = ?");
$product_stmt->bind_param("i", $category_id);
$product_stmt->execute();
$products = $product_stmt->get_result();
?>

<div class="top-buttons">
    <a href="category.php?id=1" class="top-btn">Crocheted Items</a>
    <a href="category.php?id=2" class="top-btn">Toys</a>
    <a href="category.php?id=3" class="top-btn">Softwares</a>
</div>

<div class="container">
    <h2 class="category-title"><?= htmlspecialchars($category_name) ?></h2>
    <div class="products-grid">
        <?php while ($row = $products->fetch_assoc()): ?>
            <div class="product-card">
                <img src="assets/images/<?= htmlspecialchars($row['image']) ?>" class="product-image" alt="<?= htmlspecialchars($row['name']) ?>">
                <div class="product-info">
                    <h3><?= htmlspecialchars($row['name']) ?></h3>
                    <p class="product-description"><?= htmlspecialchars($row['description']) ?></p>
                    <p class="product-price"><?= number_format($row['price'], 2) ?> Rs</p>

                    <?php if ($row['quantity'] > 0): ?>
                        <form action="add_to_cart.php" method="POST">
                            <input type="hidden" name="product_id" value="<?= $row['id'] ?>">
                            <input type="number" name="quantity" class="qty-input" value="1" min="1" max="<?= $row['quantity'] ?>">
                            <button type="submit" class="cart-btn">Add to Cart</button>
                        </form>

                        <form action="add_to_cart.php" method="POST">
                            <input type="hidden" name="product_id" value="<?= $row['id'] ?>">
                            <input type="hidden" name="quantity" value="1">
                            <button type="submit" class="buy-btn">Buy Now</button>
                        </form>
                    <?php else: ?>
                        <p class="out-of-stock">Out of Stock</p>
                    <?php endif; ?>

                    <form action="add_to_wishlist.php" method="POST">
                        <input type="hidden" name="product_id" value="<?= $row['id'] ?>">
                        <button type="submit" class="wishlist-btn">Add to Wishlist</button>
                    </form>
                </div>
            </div>
        <?php endwhile; ?>
    </div>
</div>

