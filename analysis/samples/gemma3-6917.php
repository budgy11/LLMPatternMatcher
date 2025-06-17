
<!DOCTYPE html>
<html>
<head>
  <title>Shopping Cart</title>
</head>
<body>

  <h1>Shopping Cart</h1>

  <?php
  // Display cart items
  $cartItems = getCartItems();
  if (count($cartItems) > 0) {
    echo "<h3>Cart Items:</h3>";
    foreach ($cartItems as $itemId => $item) {
      echo "<strong>Product ID:</strong> " . $itemId . "<br>";
      echo "<strong>Quantity:</strong> " . $item['quantity'] . "<br>";

      // Get product name (replace with your database query)
      $productName = getProductName($itemId);
      echo "<strong>Product Name:</strong> " . $productName . "<br>";

      // You can add a button to update the quantity or remove the item
      echo "<form method='post' action=''>";
      echo "<label for='quantity_" . $itemId . "'>Quantity:</label>";
      echo "<input type='number' id='quantity_" . $itemId . "' value='" . $item['quantity'] . "' min='1' name='quantity_" . $itemId . "'>";
      echo "<input type='submit' value='Update'> ";
      echo "<input type='submit' value='Remove' name='remove_" . $itemId . "'>";
      echo "</form>";
    }
  } else {
    echo "<p>Your cart is empty.</p>";
  }
  ?>
