
<!DOCTYPE html>
<html>
<head>
    <title>E-commerce Purchase</title>
</head>
<body>

<h1>E-commerce Purchase</h1>

<h2>Add to Cart (add_to_cart.php)</h2>
<form method="post" action="add_to_cart.php">
    <label for="product_id">Product ID:</label>
    <input type="number" id="product_id" name="product_id" required><br><br>

    <label for="quantity">Quantity:</label>
    <input type="number" id="quantity" name="quantity" required><br><br>

    <button type="submit">Add to Cart</button>
</form>

<h2>Checkout (checkout.php)</h2>
<form method="post" action="checkout.php">
    <label for="product_ids">Product IDs (comma-separated):</label>
    <input type="text" id="product_ids" name="product_ids" required><br><br>

    <label for="quantities">Quantities (comma-separated):</label>
    <input type="text" id="quantities" name="quantities" required><br><br>

    <label for="customer_name">Customer Name:</label>
    <input type="text" id="customer_name" name="customer_name" required><br><br>

    <button type="submit">Checkout</button>
</form>

</body>
</html>


<?php

// Database connection details (replace with your actual credentials)
$dbHost = 'localhost';
$dbName = 'ecommerce_db';
$dbUser = 'your_username';
$dbPassword = 'your_password';

// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {

  // Validate the form data (important for security!)
  $firstName = isset($_POST['firstName']) ? trim($_POST['firstName']) : '';
  $lastName = isset($_POST['lastName']) ? trim($_POST['lastName']) : '';
  $email = isset($_POST['email']) ? trim($_POST['email']) : '';
  $address = isset($_POST['address']) ? trim($_POST['address']) : '';
  $city = isset($_POST['city']) ? trim($_POST['city']) : '';
  $state = isset($_POST['state']) ? trim($_POST['state']) : '';
  $zip = isset($_POST['zip']) ? trim($_POST['zip']) : '';
  $productName = isset($_POST['productName']) ? trim($_POST['productName']) : '';
  $quantity = isset($_POST['quantity']) ? intval($_POST['quantity']) : 1; // Ensure quantity is an integer
  // Add more validation here (e.g., email format, quantity > 0, etc.)


  // Check if the product exists in the database (example)
  $productQuery = "SELECT id, productName, price FROM products WHERE productName = '$productName'";
  $productResult = mysqli_query($db, $productQuery);

  if ($productResult && mysqli_num_rows($productResult) > 0) {
    $product = mysqli_fetch_assoc($productResult);
    $productId = $product['id'];
    $productPrice = $product['price'];

    // Calculate the total amount
    $totalAmount = $productPrice * $quantity;

    // Insert the order into the database
    $orderQuery = "INSERT INTO orders (customerFirstName, customerLastName, customerEmail, customerAddress, customerCity, customerState, customerZip, productId, quantity, totalAmount)
                   VALUES ('$firstName', '$lastName', '$email', '$address', '$city', '$state', '$zip', '$productId', '$quantity', '$totalAmount')";

    if (mysqli_query($db, $orderQuery)) {
      // Order created successfully
      echo "<p>Order placed successfully! Thank you for your purchase.</p>";
      echo "<p>Order ID: " . mysqli_insert_id($db) . "</p>";
    } else {
      echo "<p>Error placing order.</p>";
      echo "<p>Error: " . mysqli_error($db) . "</p>";
    }
  } else {
    echo "<p>Product '$productName' not found.</p>";
  }
}
?>
