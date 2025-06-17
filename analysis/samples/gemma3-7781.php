
<br>
<a href="cart.php">View Cart</a>

</body>
</html>


<?php
session_start();

// Database Connection (Replace with your actual credentials)
$dbHost = 'localhost';
$dbName = 'shopping_cart';
$dbUser = 'root';
$dbPass = '';

// Establish database connection
$conn = new mysqli($dbHost, $dbUser, $dbPass, $dbName);

if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

// Function to add item to cart
function addToCart($conn, $product_id, $quantity) {
  if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = array();
  }

  if (isset($_SESSION['cart'][$product_id])) {
    $_SESSION['cart'][$product_id]['quantity'] += $quantity;
  } else {
    $_SESSION['cart'][$product_id] = array('quantity' => $quantity);
  }
}

// Function to update quantity in cart
function updateCartQuantity($conn, $product_id, $quantity) {
  if (isset($_SESSION['cart'][$product_id])) {
    $_SESSION['cart'][$product_id]['quantity'] = $quantity;
  } else {
    // Item not in cart, handle it gracefully (e.g., log an error, display a message)
    // Example:
    error_log("Product ID $product_id not in cart.");
  }
}

// Function to remove item from cart
function removeFromCart($conn, $product_id) {
  if (isset($_SESSION['cart'][$product_id])) {
    unset($_SESSION['cart'][$product_id]);
  }
}

// Function to get cart items
function getCartItems($conn) {
  $cart_items = array();
  foreach ($_SESSION['cart'] as $product_id => $item) {
    $product_query = "SELECT * FROM products WHERE id = '$product_id'";
    $result = $conn->query($product_query);
    if ($result->num_rows > 0) {
      $product = $result->fetch_assoc();
      $cart_items[] = array(
        'id' => $product['id'],
        'name' => $product['name'],
        'price' => $product['price'],
        'quantity' => $item['quantity']
      );
    }
  }
  return $cart_items;
}


// --- Handle Cart Actions ---

// Add to Cart
if (isset($_POST['add_to_cart'])) {
  $product_id = $_POST['product_id'];
  $quantity = $_POST['quantity'];
  addToCart($conn, $product_id, $quantity);
  header("Location: cart.php"); // Redirect to cart page
  exit;
}

// Update Quantity
if (isset($_POST['update_quantity'])) {
  $product_id = $_POST['product_id'];
  $quantity = $_POST['quantity'];
  updateCartQuantity($conn, $product_id, $quantity);
  header("Location: cart.php");
  exit;
}

// Remove from Cart
if (isset($_GET['remove_from_cart'])) {
  $product_id = $_GET['remove_from_cart'];
  removeFromCart($conn, $product_id);
  header("Location: cart.php");
  exit;
}


// --- Display Cart ---

// Get cart items
$cart_items = getCartItems($conn);

// --- Product Data (Example - Replace with your actual product data)---
$products = array(
  1 => array('id' => 1, 'name' => 'T-Shirt', 'price' => 20.00),
  2 => array('id' => 2, 'name' => 'Jeans', 'price' => 50.00),
  3 => array('id' => 3, 'name' => 'Shoes', 'price' => 80.00)
);

?>
