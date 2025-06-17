
<!DOCTYPE html>
<html>
<head>
  <title>Shopping Cart</title>
</head>
<body>

  <h1>Shopping Cart</h1>

  <?php
  $cart_contents = getCartContents();

  if (empty($cart_contents)) {
    echo "<p>Your cart is empty.</p>";
  } else {
    echo "<ul>";
    foreach ($cart_contents as $product_id => $product_data) {
      echo "<li>";
      echo "<strong>Product Name:</strong> " . $product_data['name'] . "<br>";
      echo "<strong>Price:</strong> $" . $product_data['price'] . "<br>";
      echo "<strong>Quantity:</strong> " . $product_data['quantity'] . "<br>";
      echo "</li>";
    }
    echo "</ul>";
  }
  ?>
