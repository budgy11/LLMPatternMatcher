
<!DOCTYPE html>
<html>
<head>
  <title>Shopping Cart</title>
</head>
<body>

  <h1>Shopping Cart</h1>

  <?php
  // Display Cart Items
  if (!empty($cart)) {
    echo "<h2>Cart Items:</h2>";
    echo "<ul>";
    foreach ($cart as $product_id => $item) {
      echo "<li>" . $item['name'] . " - $" . $item['price'] . " x " . $item['quantity'] . " = $" . ($item['price'] * $item['quantity']) . "</li>";
    }
    echo "</ul>";

    // Cart Total
    $total = 0;
    foreach ($cart as $product_id => $item) {
      $total += ($item['price'] * $item['quantity']);
    }
    echo "<p><strong>Total: $" . $total . "</strong></p>";
  } else {
    echo "<p>Your cart is empty.</p>";
  }
  ?>
