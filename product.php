<?php include('includes/db.php'); ?>
<?php include('includes/header.php'); ?>

<?php
include('includes/db.php');
include('includes/header.php');

// Validate product ID
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    echo "<p style='padding:20px;'>Invalid product ID.</p>";
    include('includes/footer.php');
    exit;
}

$productId = intval($_GET['id']);

// Fetch product data
$sql = "SELECT products.*, categories.name AS category_name 
        FROM products 
        JOIN categories ON products.category_id = categories.id 
        WHERE products.id = $productId";

$result = $conn->query($sql);
$product = $result->fetch_assoc();

if (!$product) {
    echo "<p style='padding:20px;'>Product not found.</p>";
    include('includes/footer.php');
    exit;
}
?>

<main class="product-view">
    <div class="product-detail">
        <img src="images/<?= htmlspecialchars($product['image']) ?>" alt="<?= htmlspecialchars($product['name']) ?>">
        <div class="details">
            <h2><?= htmlspecialchars($product['name']) ?></h2>
            <p class="category">Category: <?= htmlspecialchars($product['category_name']) ?></p>
            <p class="price">Rs <?= number_format($product['price'], 2) ?></p>
            <p class="description"><?= nl2br(htmlspecialchars($product['description'])) ?></p>

<div class="actions">
    <form method="post" action="cart.php">
        <input type="hidden" name="product_id" value="<?= $product['id'] ?>">
        <input type="number" name="quantity" value="1" min="1" style="width: 60px; margin-right: 10px;">
        <button type="submit" name="add_to_cart">Add to Cart</button>
    </form>

    <button>Add to Wishlist</button> <!-- Wishlist coming later -->
</div>

        </div>
    </div>
</main>


