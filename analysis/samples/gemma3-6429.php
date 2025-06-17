<!DOCTYPE html>
<html>
<head>
  <title>Shopping Cart</title>
</head>
<body>

  <h1>Shopping Cart</h1>

  <?php
  $cart = getCartContents();
  if (empty($cart)) {
    echo "<p>Your cart is empty.</p>";
  } else {
    echo "<ul>";
    foreach ($cart as $product_id => $item) {
      echo "<li>" . $item['name'] . " - $" . $item['price'] . " x " . $item['quantity'] . " = $" . ($item['price'] * $item['quantity']) . "</li>";
    }
    echo "</ul>";
  }
  ?>
