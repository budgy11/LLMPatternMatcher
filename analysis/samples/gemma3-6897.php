
<!DOCTYPE html>
<html>
<head>
    <title>Simple Shopping Cart</title>
</head>
<body>

<h1>Shopping Cart</h1>

<h2>Add Items</h2>
<form method="post" action="">
    <label for="item">Item:</label>
    <select name="item" id="item">
        <option value="apple">Apple</option>
        <option value="banana">Banana</option>
        <option value="orange">Orange</option>
        <option value="grapefruit">Grapefruit</option>
    </select>

    <label for="quantity">Quantity:</label>
    <input type="number" name="quantity" id="quantity" value="1" min="1">

    <input type="submit" name="action" value="add">
</form>

<br>

<h2>Purchase</h2>
<form method="post" action="">
    <input type="hidden" name="action" value="purchase">
    <input type="submit" name="submit" value="Purchase">
</form>

</body>
</html>


<?php
session_start();

// Database connection details
$servername = "localhost";
$username = "your_db_username";
$password = "your_db_password";
$dbname = "your_db_name";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

// --- Product Data (For demonstration - Replace with database query) ---
$products = [
  ["id" => 1, "name" => "T-Shirt", "price" => 20],
  ["id" => 2, "name" => "Jeans", "price" => 50],
  ["id" => 3, "name" => "Hat", "price" => 15],
];

// --- Cart Logic ---

// Initialize cart if not already set
if (!isset($_SESSION['cart'])) {
  $_SESSION['cart'] = [];
}

// Add to Cart Function
function addToCart($product_id, $_SESSION) {
  $product_id = (int)$product_id;  // Ensure product_id is an integer

  // Find the product in the product array
  $product = null;
  foreach ($products as $p) {
    if ($p['id'] == $product_id) {
      $product = $p;
      break;
    }
  }

  if ($product) {
    if (!isset($_SESSION['cart'][$product['id']])) {
      $_SESSION['cart'][$product['id']] = 1; // Add to cart
    } else {
      $_SESSION['cart'][$product['id']]++; // Increment quantity
    }
  }
}

// Remove from Cart Function
function removeFromCart($product_id, $_SESSION) {
  if (isset($_SESSION['cart'][$product_id])) {
    unset($_SESSION['cart'][$product_id]);
  }
}

// Get Cart Contents
function getCartContents($_SESSION) {
  $cart_contents = [];
  foreach ($_SESSION['cart'] as $product_id => $quantity) {
    $product = null;
    foreach ($products as $p) {
      if ($p['id'] == $product_id) {
        $product = $p;
        break;
      }
    }
    if ($product) {
      $cart_contents[] = [
        'id' => $product['id'],
        'name' => $product['name'],
        'price' => $product['price'],
        'quantity' => $quantity,
        'total' => $product['price'] * $quantity
      ];
    }
  }
  return $cart_contents;
}

// ---  Handle Actions (e.g., Add to Cart) ---

if (isset($_POST['action']) && $_POST['action'] == 'add_to_cart') {
  $product_id = $_POST['product_id'];
  addToCart($product_id, $_SESSION);
}

if (isset($_POST['action']) && $_POST['action'] == 'remove_from_cart') {
    $product_id = $_POST['product_id'];
    removeFromCart($product_id, $_SESSION);
}



// --- Display the Cart ---

$cart = getCartContents($_SESSION);

?>
