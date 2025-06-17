
<!DOCTYPE html>
<html>
<head>
  <title>Purchase Functionality</title>
</head>
<body>

  <h1>Shopping Cart</h1>

  <?php
  // Display cart items (for demonstration)
  if (isset($_SESSION['cart'])) {
    echo "<h2>Items in Cart:</h2>";
    echo "<ul>";
    foreach ($_SESSION['cart'] as $item) {
      echo "<li>Product ID: " . $item['product_id'] . ", Quantity: " . $item['quantity'] . "</li>";
    }
    echo "</ul>";
  }
  ?>
