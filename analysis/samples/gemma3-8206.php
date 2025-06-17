
<!DOCTYPE html>
<html>
<head>
  <title>Shopping Cart</title>
  <style>
    .cart-item {
      margin-bottom: 10px;
    }
    .cart-total {
      font-weight: bold;
    }
  </style>
</head>
<body>

  <h1>Shopping Cart</h1>

  <?php
  $cart_total = calculateTotal();
  ?>
