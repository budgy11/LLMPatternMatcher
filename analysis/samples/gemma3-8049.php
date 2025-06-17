</ul>

</body>
</html>


<?php
session_start();

// Database connection details (replace with your actual details)
$db_host = "localhost";
$db_user = "your_username";
$db_password = "your_password";
$db_name = "your_database_name";

// Establish database connection
$conn = new mysqli($db_host, $db_user, $db_password, $db_name);

// Check connection
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

// Helper function to sanitize input (VERY IMPORTANT)
function sanitizeInput($data) {
  $data = trim($data);
  $data = stripslashes($data);
  $data = htmlspecialchars($data); // Sanitize for HTML output
  return $data;
}

// --- Cart Functions ---

// Add item to cart
function addToCart($conn, $product_id, $quantity) {
  $product_id = sanitizeInput($product_id);
  $quantity = (int)$quantity; // Convert quantity to integer

  if ($quantity <= 0) {
    return false; // Invalid quantity
  }

  $user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;

  // Check if user is logged in
  if (!$user_id) {
    return false; // User not logged in
  }

  // Check if item already exists in cart
  $query = "SELECT * FROM cart WHERE user_id = '$user_id' AND product_id = '$product_id'";
  $result = $conn->query($query);

  if ($result->num_rows > 0) {
    // Item exists, update quantity
    $row = $result->fetch_assoc();
    $quantity_in_cart = $row['quantity'] + $quantity;
    $conn->query("UPDATE cart SET quantity = '$quantity_in_cart' WHERE user_id = '$user_id' AND product_id = '$product_id'");
    return true;
  } else {
    // Item doesn't exist, add it to cart
    $conn->query("INSERT INTO cart (user_id, product_id, quantity) VALUES ('$user_id', '$product_id', '$quantity')");
    return true;
  }
}

// Get cart items
function getCartItems($conn, $user_id) {
  $query = "SELECT p.product_name, p.price, c.quantity FROM cart c JOIN products p ON c.product_id = p.product_id WHERE c.user_id = '$user_id'";
  $result = $conn->query($query);

  if ($result->num_rows > 0) {
    $cart_items = array();
    while($row = $result->fetch_assoc()) {
      $cart_items[] = $row;
    }
    return $cart_items;
  } else {
    return []; // Empty cart
  }
}

// Remove item from cart
function removeFromCart($conn, $product_id, $user_id) {
  $product_id = sanitizeInput($product_id);

  $query = "DELETE FROM cart WHERE user_id = '$user_id' AND product_id = '$product_id'";
  return $conn->query($query);
}

// Update quantity in cart
function updateQuantity($conn, $product_id, $quantity, $user_id) {
  $product_id = sanitizeInput($product_id);
  $quantity = (int)$quantity;

  if ($quantity <= 0) {
    return false; // Invalid quantity
  }

  $query = "UPDATE cart SET quantity = '$quantity' WHERE user_id = '$user_id' AND product_id = '$product_id'";
  return $conn->query($query);
}

// --- Display Cart Page ---

// Get cart items for the logged-in user
$cart_items = getCartItems($conn, $_SESSION['user_id']);

// Calculate total price
$total_price = 0;
foreach ($cart_items as $item) {
  $total_price += $item['price'] * $item['quantity'];
}


?>
