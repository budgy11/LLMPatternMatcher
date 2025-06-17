
<!DOCTYPE html>
<html>
<head>
    <title>Online Purchase</title>
</head>
<body>

<h1>Online Purchase</h1>

<form method="post" action="">
    <label for="product_id">Product ID:</label>
    <select name="product_id" id="product_id">
        <option value="1">Laptop (ID: 1)</option>
        <option value="2">Mouse (ID: 2)</option>
        <option value="3">Keyboard (ID: 3)</option>
    </select><br><br>

    <label for="quantity">Quantity:</label>
    <input type="number" id="quantity" name="quantity" min="1"><br><br>

    <button type="submit">Place Order</button>
</form>

</body>
</html>


<?php

// Database connection details
$dbHost = 'localhost';
$dbName = 'shop_db';
$dbUser = 'your_username';
$dbPass = 'your_password';

// Configuration
$product_id = $_POST['product_id']; // Get product ID from form
$quantity = $_POST['quantity'];     // Get quantity from form
$customer_email = $_POST['customer_email']; // Get customer email
$order_date = date('Y-m-d H:i:s'); // Get current date and time

// Database connection
$conn = new mysqli($dbHost, $dbUser, $dbPass, $dbName);

// Check connection
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

// ---  Retrieve Product Information ---
$sql = "SELECT product_id, product_name, price, stock FROM products WHERE product_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $product_id); // "i" indicates integer
$stmt->execute();
$result = $stmt->get_result();

$product = $result->fetch_assoc();

if (!$product) {
  echo "Product not found.";
  $stmt->close();
  $conn->close();
  exit;
}

if ($product['stock'] < $quantity) {
    echo "Not enough stock available.";
    $stmt->close();
    $conn->close();
    exit;
}

// ---  Create Order in Database ---
$sql = "INSERT INTO orders (customer_email, product_id, quantity, order_date)
        VALUES (?, ?, ?, ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ssis", $customer_email, $product_id, $quantity, $order_date);

if ($stmt->execute()) {
  $order_id = $conn->insert_id; // Get the last inserted ID (order ID)

  // Update product stock
  $sql = "UPDATE products SET stock = stock - ? WHERE product_id = ?";
  $stmt->bind_param("is", $quantity, $product_id);
  $stmt->execute();

  echo "Order placed successfully! Order ID: " . $order_id;
} else {
  echo "Error placing order: " . $conn->error;
}

$stmt->close();
$conn->close();

?>
