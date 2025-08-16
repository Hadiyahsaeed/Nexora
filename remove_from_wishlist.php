<?php
session_start();
include('includes/db.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['product_id'])) {
    $product_id = intval($_POST['product_id']);
    $conn->query("DELETE FROM wishlist WHERE product_id = $product_id");
}

header("Location: wishlist.php");
exit();
