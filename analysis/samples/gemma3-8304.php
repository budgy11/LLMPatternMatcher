
</body>
</html>


<?php
session_start();

// Database connection (replace with your actual details)
$db_host = "localhost";
$db_user = "your_username";
$db_password = "your_password";
$db_name = "your_database_name";

// Function to connect to the database
function connectToDatabase() {
  $conn = new mysqli($db_host, $db_user, $db_password, $db_name);
  if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
  }
  return $conn;
}

// Function to fetch products from the database
function fetchProducts($conn) {
  $sql = "SELECT * FROM products";
  $result = $conn->query($sql);
  $products = array();
  if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
      $products[] = $row;
    }
  }
  return $products;
}

// --- Cart Management Functions ---

// Initialize the cart if it doesn't exist
if (!isset($_SESSION['cart'])) {
  $_SESSION['cart'] = array();
}

// Add to cart function
function addToCart($conn, $product_id, $quantity) {
  global $db_host, $db_user, $db_password, $db_name;

  // Check if the product exists
  $sql = "SELECT * FROM products WHERE id = ?";
  $stmt = $conn->prepare($sql);
  $stmt->bind_param("i", $product_id);
  $stmt->execute();
  $result = $stmt->get_result();
  $product = $result->fetch_assoc();

  if (!$product) {
    echo "Product with ID " . $product_id . " not found.";
    return false;
  }

  // Check if the product is already in the cart
  foreach($_SESSION['cart'] as $index => $item) {
    if ($item['id'] == $product_id) {
      // Update the quantity
      $_SESSION['cart'][$index]['quantity'] += $quantity;
      return true;
    }
  }

  // Add the product to the cart
  $_SESSION['cart'][] = $product;
  return true;
}

// Remove from cart function
function removeFromCart($product_id) {
  // Iterate through the cart and remove the item with the matching product_id
  foreach($_SESSION['cart'] as $key => $item) {
    if ($item['id'] == $product_id) {
      unset($_SESSION['cart'][$key]);
      // Re-index the cart array
      $_SESSION['cart'] = array_values($_SESSION['cart']);
      return true;
    }
  }
  return false;
}

// Get cart items
function getCartItems() {
  return $_SESSION['cart'];
}

// Calculate cart total
function calculateCartTotal($conn) {
  $total = 0;
  $cartItems = getCartItems();

  foreach ($cartItems as $item) {
    $sql = "SELECT price FROM products WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $item['id']);
    $stmt->execute();
    $result = $stmt->get_result();
    $product = $result->fetch_assoc();
    $total += $product['price'] * $item['quantity'];
  }
  return $total;
}

// ---  Display the Cart ---

// Fetch products
$conn = connectToDatabase();
$products = fetchProducts($conn);


// --- Handle form submission ---
if (isset($_POST['action']) && $_POST['action'] == 'add_to_cart') {
  $product_id = $_POST['product_id'];
  $quantity = $_POST['quantity'];
  addToCart($conn, $product_id, $quantity);
}

if (isset($_POST['action']) && $_POST['action'] == 'remove_from_cart') {
  $product_id = $_POST['product_id'];
  removeFromCart($product_id);
}

// Get Cart items for display
$cartItems = getCartItems();

// Calculate total
$cartTotal = calculateCartTotal($conn);

// --- Output the HTML ---
?>
