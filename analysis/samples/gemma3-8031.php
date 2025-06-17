

<!DOCTYPE html>
<html>
<head>
  <title>Online Store - Purchase</title>
</head>
<body>

  <h1>Online Store - Purchase</h1>

  <form method="post" action="">
    <label for="customer_name">Customer Name:</label>
    <input type="text" id="customer_name" name="customer_name" required><br><br>

    <label for="customer_email">Email:</label>
    <input type="email" id="customer_email" name="customer_email" required><br><br>

    <label for="address">Address:</label>
    <textarea id="address" name="address" rows="4" cols="50" required></textarea><br><br>

    <label for="payment_amount">Payment Amount:</label>
    <input type="number" id="payment_amount" name="payment_amount" step="0.01" required><br><br>

    <input type="submit" value="Place Order">
  </form>

  <?php
    // Display Cart Contents (for debugging and showing the user what they're buying)
    if (isset($_SESSION['cart'])) {
        echo "<h2>Cart Contents:</h2>";
        echo "<ul>";
        foreach ($_SESSION['cart'] as $item) {
          echo "<li>Product ID: " . $item['product_id'] . ", Quantity: " . $item['quantity'] . "</li>";
        }
        echo "</ul>";
    }
  ?>
