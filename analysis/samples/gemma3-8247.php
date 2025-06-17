
<!DOCTYPE html>
<html>
<head>
  <title>Shopping Cart</title>
  <style>
    .cart-item {
      margin-bottom: 10px;
    }
  </style>
</head>
<body>

  <h1>Shopping Cart</h1>

  <?php
  // Display Cart Items
  $cart_contents = $cart->getCartContents();
  if (count($cart_contents)) {
    echo "<h2>Cart Items:</h2>";
    foreach ($cart_contents as $item_id => $product) {
      echo '<div class="cart-item">';
      echo 'Product: ' . $product['name'] . '<br>';
      echo 'Price: $' . number_format($product['price'], 2) . '<br>';
      echo 'Quantity: ' . $product['quantity'] . '<br>';
      // Optional: Add a remove button to the cart
      echo '<a href="cart.php?remove=' . $item_id . '" style="color: red; text-decoration: underline;">Remove</a><br>';
      echo '--------------------<br>';
    }

    // Calculate and display total
    $total = $cart->getTotal($conn);
    echo '<h2>Total: $' . number_format($total, 2) . '</h2>';

  } else {
    echo "<p>Your cart is empty.</p>";
  }
  ?>
