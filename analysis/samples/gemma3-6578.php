
<!DOCTYPE html>
<html>
<head>
  <title>Shopping Cart</title>
</head>
<body>

  <h1>Shopping Cart</h1>

  <?php
  // Display the cart contents
  if (count($_SESSION['cart']) > 0) {
    echo "<h2>Cart Items:</h2>";
    echo "<ul>";
    foreach ($_SESSION['cart'] as $product_id => $item) {
      echo "<li>" . $item['name'] . " - $" . $item['price'] . " - Quantity: " . $item['quantity'] . "</li>";
    }
    echo "</ul>";

    // Calculate the total price
    $total = 0;
    foreach ($_SESSION['cart'] as $product_id => $item) {
      $total += $item['price'] * $item['quantity'];
    }

    echo "<p><strong>Total: $" . $total . "</strong></p>";
  } else {
    echo "<p>Your cart is empty.</p>";
  }
  ?>
