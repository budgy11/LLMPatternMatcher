
<!DOCTYPE html>
<html>
<head>
  <title>Shopping Cart</title>
</head>
<body>

  <h1>Shopping Cart</h1>

  <?php
  $cart_items = get_cart();

  if (empty($cart_items)) {
    echo "<p>Your cart is empty.</p>";
  } else {
    echo "<ul>";
    foreach ($cart_items as $item_id => $item) {
      echo "<li>";
      echo "Product: " . $item['name'] . "<br>";
      echo "Quantity: " . $item['quantity'] . "<br>";
      echo "Price: $" . $item['price'] . "<br>";
      // Add an 'Remove' button or link here.  For simplicity, we'll just print a link.
      echo "<a href='cart.php?action=remove_item&product_id=" . $item_id . "'>Remove</a>";
      echo "<br>";
      echo "</li>";
    }
    echo "</ul>";
  }

  // Example: Display total
  $total = 0;
  foreach ($cart_items as $item) {
    $total += $item['price'] * $item['quantity'];
  }
  echo "<p>Total: $" . $total . "</p>";
  ?>
