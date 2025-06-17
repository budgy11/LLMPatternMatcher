
<!DOCTYPE html>
<html>
<head>
    <title>Online Store</title>
</head>
<body>

<h1>Welcome to Our Store</h1>

<h2>Products</h2>

<ul>
    <li>
        <label for="product1">Awesome T-Shirt (product1) - Price: $20</label><br>
        <input type="checkbox" id="product1" name="product_id" value="product1">
        <br>
        <input type="submit" name="add_to_cart" value="Add to Cart">
    </li>
    <li>
        <label for="product2">Cool Mug (product2) - Price: $10</label><br>
        <input type="checkbox" id="product2" name="product_id" value="product2">
        <br>
        <input type="submit" name="add_to_cart" value="Add to Cart">
    </li>
    <li>
        <label for="product3">Fancy Hat (product3) - Price: $30</label><br>
        <input type="checkbox" id="product3" name="product_id" value="product3">
        <br>
        <input type="submit" name="add_to_cart" value="Add to Cart">
    </li>
</ul>

<br>
<a href="?view_cart=true" >View Cart</a> | <a href="?checkout=true">Checkout</a>

</body>
</html>


<?php
session_start();

// Database connection details (Replace with your actual details)
$dbHost = "localhost";
$dbUser = "your_db_user";
$dbPass = "your_db_password";
$dbName = "your_db_name";

// Function to connect to the database
function connectToDatabase() {
  $conn = new mysqli($dbHost, $dbUser, $dbPass, $dbName);
  if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
  }
  return $conn;
}


// --------------------------------------------------
// Purchase Functionality
// --------------------------------------------------

/**
 * Processes a purchase request.
 *
 * @param array $cartData The contents of the shopping cart.
 * @return string  A message indicating the success or failure of the purchase.
 */
function processPurchase($cartData) {
  $conn = connectToDatabase();

  // Validate data - IMPORTANT!  Add more validation here
  if (empty($cartData)) {
    return "Cart is empty. Please add items to your cart.";
  }

  // Assuming you have a 'products' table with 'id', 'name', 'price'
  // Adjust table and column names as needed.

  $sql = "INSERT INTO orders (user_id, order_date) VALUES (NULL, NOW())";  // user_id can be NULL if you don't have user authentication.
  $result = $conn->query($sql);

  if (!$result) {
    return "Error inserting order data.  Error: " . $conn->error;
  }

  // Loop through the cart and insert items into the 'order_items' table
  foreach ($cartData as $item) {
    $product_id = $item['product_id'];
    $quantity = $item['quantity'];

    $sql_item = "INSERT INTO order_items (order_id, product_id, quantity) VALUES (NULL, $product_id, $quantity)";
    if (!$conn->query($sql_item)) {
      return "Error inserting order item.  Error: " . $conn->error;
    }
  }

  // Clear the cart after successful purchase (Implement your cart clearing logic here)
  // This is a placeholder - you need to update your cart storage.
  $_SESSION['cart'] = [];

  return "Purchase successful! Your order ID is: " . $conn->insert_id;
}



// --------------------------------------------------
// Handle Form Submission (Example)
// --------------------------------------------------

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  // Assuming you have a 'cart' session variable

  if (isset($_SESSION['cart'])) {
    $cartData = $_SESSION['cart'];
    $purchaseResult = processPurchase($cartData);
    echo "<p>" . $purchaseResult . "</p>";
  } else {
    echo "<p>Error: Cart is not set.</p>";
  }
}

// --------------------------------------------------
// Example Cart Data (for demonstration)
// --------------------------------------------------

// Example Cart
$_SESSION['cart'] = [
  ['product_id' => 1, 'quantity' => 2],
  ['product_id' => 2, 'quantity' => 1],
];

?>
