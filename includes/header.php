<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Nexora</title>
  <link href="https://fonts.googleapis.com/css2?family=Poppins&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="assets/css/style.css?v=1.1"> <style>
    body { font-family: 'Poppins', sans-serif; margin: 0; padding: 0; }
    .top-bar {
      background: #007BFF;
      padding: 10px;
      display: flex;
      justify-content: center;
      gap: 20px;
    }
    .top-bar a {
      color: white;
      text-decoration: none;
      font-weight: bold;
      padding: 8px 16px;
      border-radius: 20px;
      transition: background 0.3s;
    }
    .top-bar a:hover {
      background: #0056b3;
    }
  </style>
</head>
<body>
  <div class="top-bar">
    <a href="index.php?id=1">Home</a>
    <a href="cart.php?id=2">Cart</a>
    <a href="wishlist.php?id=3">Wishlist</a>
  </div>