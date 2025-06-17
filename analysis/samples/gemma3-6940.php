

  <form method="post" action="">
    <label for="product_id">Product ID:</label>
    <input type="number" id="product_id" name="product_id" required>
    <br><br>

    <label for="quantity">Quantity:</label>
    <input type="number" id="quantity" name="quantity" required>
    <br><br>

    <label for="customer_id">Customer ID:</label>
    <input type="number" id="customer_id" name="customer_id" required>
    <br><br>

    <button type="submit">Place Order</button>
  </form>

</body>
</html>


<?php

// Initialize the purchase data
$purchase = array();

// Get purchase details from form input
$productName = $_POST['productName'] ?? ''; // Use null coalescing operator for safety
$quantity = (int)$_POST['quantity'] ?? 0; // Cast to integer
$price = (float)$_POST['price'] ?? 0.00; //Cast to float

// Calculate the total
$total = $quantity * $price;

// Add purchase data to the array
$purchase['productName'] = $productName;
$purchase['quantity'] = $quantity;
$purchase['price'] = $price;
$purchase['total'] = $total;

// Display the purchase details
echo "<h2>Purchase Confirmation</h2>";
echo "<p><strong>Product Name:</strong> " . htmlspecialchars($productName) . "</p>";
echo "<p><strong>Quantity:</strong> " . $quantity . "</p>";
echo "<p><strong>Price per Unit:</strong> $" . number_format($price, 2) . "</p>";
echo "<p><strong>Total:</strong> $" . number_format($total, 2) . "</p>";

// Confirmation message
echo "<p>Thank you for your purchase! Your order has been received.</p>";

// You could also store this purchase data in a database here.
?>
