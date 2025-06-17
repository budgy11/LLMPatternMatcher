
<!DOCTYPE html>
<html>
<head>
  <title>Purchase Functionality</title>
  <style>
    .success {
      background-color: #4CAF50;
      color: white;
      padding: 20px;
      border-radius: 5px;
      text-align: center;
      margin-top: 20px;
    }
    .error {
      background-color: #f44336;
      color: white;
      padding: 20px;
      border-radius: 5px;
      text-align: center;
      margin-top: 20px;
    }
  </style>
</head>
<body>

  <h1>Purchase Product</h1>

  <form method="post">
    <label for="product_id">Product ID:</label>
    <input type="number" id="product_id" name="product_id" required>
    <br><br>

    <label for="quantity">Quantity:</label>
    <input type="number" id="quantity" name="quantity" value="1" min="1" required>
    <br><br>

    <button type="submit">Place Order</button>
  </form>

</body>
</html>


<?php

// Database connection (replace with your actual database details)
$host = "localhost";
$username = "your_db_username";
$password = "your_db_password";
$database = "your_db_name";

// Create connection
$conn = new mysqli($host, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Session handling (for adding to cart, etc.)
session_start();

// --- Product Information (Example -  Replace with your product data) ---
$products = [
    1 => ['id' => 1, 'name' => 'T-Shirt', 'price' => 20.00, 'stock' => 10],
    2 => ['id' => 2, 'name' => 'Jeans', 'price' => 50.00, 'stock' => 5],
    3 => ['id' => 3, 'name' => 'Sneakers', 'price' => 80.00, 'stock' => 3],
];

// --- Purchase Functionality ---

// 1. Add to Cart
function addToCart($productId, $quantity) {
    if (isset($_SESSION['cart'])) {
        $_SESSION['cart'][$productId] += $quantity;
    } else {
        $_SESSION['cart'][$productId] = $quantity;
    }
}

// 2.  Checkout (Simplified - no order processing, just calculates total)
function checkout() {
    $total = 0;
    if (isset($_SESSION['cart'])) {
        foreach ($_SESSION['cart'] as $productId => $quantity) {
            $product = $products[$productId];
            $total += $product['price'] * $quantity;
        }
    }

    // Clear the cart after purchase (or handle it differently)
    $_SESSION['cart'] = [];

    return $total;
}

// --- Handling Requests (Example using GET and POST) ---

// 1. Add to Cart (GET request)
if (isset($_GET['add_to_cart']) && isset($_GET['product_id'])) {
    $productId = (int)$_GET['product_id'];  // Ensure it's an integer
    $quantity = 1; // Default quantity is 1
    if (isset($_GET['quantity'])) {
        $quantity = (int)$_GET['quantity'];
    }

    if (isset($products[$productId])) {
        if ($products[$productId]['stock'] >= $quantity) {
            addToCart($productId, $quantity);
        } else {
            echo "<p style='color:red;'>Not enough stock for this product.</p>";
        }
    } else {
        echo "<p style='color:red;'>Product not found.</p>";
    }
}


// 2. Checkout (POST request - for demonstration)
if (isset($_POST['checkout'])) {
    $total = checkout();
    echo "<p>Total: $" . number_format($total, 2) . "</p>";
}


// --- Displaying Products (Example) ---

echo "<h2>Available Products</h2>";
foreach ($products as $id => $product) {
    echo "<p><strong>" . $product['name'] . "</strong> - $" . number_format($product['price'], 2) . " (Stock: " . $product['stock'] . ")</p>";
    echo "<form method='get' action=''>"; // The 'action' attribute is crucial
    echo "<input type='hidden' name='product_id' value='" . $id . "'>";
    if (isset($_SESSION['cart'][$id])) {
        echo "<input type='number' name='quantity' value='" . $_SESSION['cart'][$id] . "' min='1' style='width:50px;'>";
    } else {
        echo "<input type='number' name='quantity' value='1' min='1' style='width:50px;'>";
    }
    echo "<input type='submit' value='Add to Cart'>";
    echo "</form>";
}

?>
