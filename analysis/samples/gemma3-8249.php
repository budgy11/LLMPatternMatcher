
</body>
</html>


<?php
session_start();

// Database connection details (replace with your actual details)
$db_host = "localhost";
$db_user = "your_username";
$db_password = "your_password";
$db_name = "your_database";

// Function to connect to the database
function connectToDatabase() {
  $conn = new mysqli($db_host, $db_user, $db_password, $db_name);
  if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
  }
  return $conn;
}

// Function to sanitize input (prevent SQL injection)
function sanitizeInput($data) {
  global $conn; // Access the database connection
  return mysqli_real_escape_string($conn, $data);
}

// ---------------------- Cart Functions ----------------------

// Add item to cart
function addToCart($product_id, $quantity) {
  global $conn;

  $product_id = sanitizeInput($product_id);
  $quantity = sanitizeInput($quantity);

  // Check if the product exists in the cart
  $cart_key = "cart_" . session_id();

  if (!isset($_SESSION[$cart_key])) {
    // If not, initialize the cart array
    $_SESSION[$cart_key] = [];
  }

  // Check if the product is already in the cart
  if (isset($_SESSION[$cart_key][$product_id])) {
    // Increment quantity
    $_SESSION[$cart_key][$product_id]['quantity'] += $quantity;
  } else {
    // Add new product to cart
    $_SESSION[$cart_key][$product_id] = [
      'quantity' => $quantity,
      'price' => getProductPrice($product_id) // Get product price
    ];
  }
}

// Get cart total
function getCartTotal($cart_key) {
  $total = 0;
  if (isset($_SESSION[$cart_key])) {
    foreach ($_SESSION[$cart_key] as $item) {
      $total += $item['price'] * $item['quantity'];
    }
  }
  return round($total, 2);
}

// Remove item from cart
function removeFromCart($product_id) {
    global $conn;
    $product_id = sanitizeInput($product_id);

    $cart_key = "cart_" . session_id();

    if (isset($_SESSION[$cart_key][$product_id])) {
        unset($_SESSION[$cart_key][$product_id]);
    }
}


// Get cart contents
function getCartContents($cart_key) {
  return $_SESSION[$cart_key] ?? []; // Return empty array if cart doesn't exist
}


// ----------------------  Product Data (For Demo) ----------------------
// Replace this with your actual database query to fetch products

$products = [
  1 => ['id' => 1, 'name' => 'Laptop', 'price' => 1200],
  2 => ['id' => 2, 'name' => 'Mouse', 'price' => 25],
  3 => ['id' => 3, 'name' => 'Keyboard', 'price' => 75],
];


// Helper function to get product price (for demonstration)
function getProductPrice($product_id) {
  global $products;
  return $products[$product_id]['price'];
}

// ----------------------  Shopping Cart Handling ----------------------

// Initialize the shopping cart session
if (!isset($_SESSION["cart_"])){
  session_start(); // start session
}

// Handle adding to cart (e.g., from a form submission)
if (isset($_POST['add_to_cart'])) {
  $product_id = $_POST['product_id'];
  $quantity = $_POST['quantity'];
  addToCart($product_id, $quantity);
}

// Handle removing from cart (e.g., from a form submission)
if (isset($_POST['remove_from_cart'])) {
    $product_id = $_POST['product_id'];
    removeFromCart($product_id);
}

// ---------------------- Displaying the Cart ----------------------

// Get cart contents
$cartContents = getCartContents("cart_" . session_id());

$cartTotal = getCartTotal("cart_" . session_id());


?>
