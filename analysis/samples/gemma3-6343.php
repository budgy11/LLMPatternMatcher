
<!DOCTYPE html>
<html>
<head>
  <title>Shopping Cart</title>
</head>
<body>

  <h1>Shopping Cart</h1>

  <?php if (empty($cart_items)) {
    echo "<p>Your cart is empty.</p>";
  } else {
    echo "<h2>Cart Items:</h2>";
    echo "<ul>";
    foreach ($cart_items as $product_id => $item) {
      echo "<li>" . $item['name'] . " - Price: $" . $item['price'] . " - Quantity: " . $item['quantity'] . "</li>";
    }
    echo "</ul>";
    echo "<p>Total: $" . $total . "</p>";
  }
?>
