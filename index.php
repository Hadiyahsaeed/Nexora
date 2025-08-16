<?php
include('includes/db.php');
include('includes/header.php');
?>

<div style="text-align: center; padding: 60px 20px;">
  <img src="assets/images/nexora_logo.png" alt="Nexora Logo" style="max-width:150px;">
  <h1>Welcome to Nexora</h1>
  <p>Your digital + physical marketplace</p>
</div>

<div style="text-align: center; margin-bottom: 40px;">
  <a href="category.php?id=1" class="category-btn">Crocheted Items</a>
  <a href="category.php?id=2" class="category-btn">Toys</a>
  <a href="category.php?id=3" class="category-btn">Softwares</a>
</div>

<style>
  .category-btn {
    display: inline-block;
    background: #1a73e8;
    color: white;
    margin: 0 10px;
    padding: 12px 20px;
    border-radius: 8px;
    text-decoration: none;
    font-size: 16px;
  }
  .category-btn:hover {
    background: #155ab6;
  }
</style>
