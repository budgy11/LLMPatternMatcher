

<?php
session_start();

// Database Connection (Replace with your actual credentials)
$db_host = "localhost";
$db_name = "shopping_cart";
$db_user = "root";
$db_password = "";

// Database connection
$conn = new mysqli($db_host, $db_user, $db_password, $db_name);

if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

// Initialize cart if not already
if (!isset($_SESSION['cart'])) {
  $_SESSION['cart'] = array();
}

// --------------------- Functions ---------------------

// Function to add item to cart
function add_to_cart($conn, $product_id, $quantity) {
  global $db_host, $db_name;

  // Check if product exists (basic validation - can be improved)
  $product_query = "SELECT id, name, price FROM products WHERE id = ?";
  $stmt = $conn->prepare($product_query);
  $stmt->bind_param("i", $product_id);
  $stmt->execute();
  $result = $stmt->get_result();

  if ($result->num_rows == 0) {
    return false; // Product not found
  }
  $product = $result->fetch_assoc();

  // Add to cart
  if (isset($_SESSION['cart'][$product_id])) {
    $_SESSION['cart'][$product_id]['quantity'] += $quantity;
  } else {
    $_SESSION['cart'][$product_id] = array(
      'quantity' => $quantity,
      'name' => $product['name'],
      'price' => $product['price']
    );
  }
  return true;
}

// Function to remove item from cart
function remove_from_cart($conn, $product_id) {
  if (isset($_SESSION['cart'][$product_id])) {
    unset($_SESSION['cart'][$product_id]);
  }
  return true;
}

// Function to update quantity of item in cart
function update_cart_quantity($conn, $product_id, $quantity) {
  if (isset($_SESSION['cart'][$product_id])) {
    $_SESSION['cart'][$product_id]['quantity'] = $quantity;
  }
  return true;
}


// Function to get cart total
function calculate_cart_total($conn) {
    $total = 0;
    foreach ($_SESSION['cart'] as $item) {
        $total += $item['price'] * $item['quantity'];
    }
    return $total;
}

// ---------------------  Handler Functions  ---------------------

// Handle adding to cart
if (isset($_POST['add_to_cart'])) {
  $product_id = $_POST['product_id'];
  $quantity = $_POST['quantity'];
  add_to_cart($conn, $product_id, $quantity);
}

// Handle removing item from cart
if (isset($_GET['remove_from_cart'])) {
  remove_from_cart($conn, $_GET['remove_from_cart']);
}

// Handle updating quantity
if (isset($_POST['update_quantity'])) {
    $product_id = $_POST['product_id'];
    $new_quantity = $_POST['quantity'];
    update_cart_quantity($conn, $product_id, $new_quantity);
}


// ---------------------  Display Cart  ---------------------

// Display cart items
echo "<h2>Shopping Cart</h2>";
if (empty($_SESSION['cart'])) {
  echo "<p>Your cart is empty.</p>";
} else {
  echo "<ul>";
  foreach ($_SESSION['cart'] as $item) {
    echo "<li>";
    echo "<strong>" . $item['name'] . "</strong> - $" . number_format($item['price'], 2) . " ";
    echo "Quantity: " . $item['quantity'];
    echo "<form method='post' action=''>";
    echo "<input type='hidden' name='product_id' value='" . $item['id'] . "'>";
    echo "<input type='hidden' name='quantity' value='" . $item['quantity'] . "'>";
    echo "<input type='submit' value='Remove'>";
    echo "</form>";
    echo "</li>";
  }
  echo "</ul>";
  echo "<p><strong>Total: $" . number_format(calculate_cart_total($conn), 2) . "</strong></p>";
}


// ---------------------  End of Script  ---------------------

// Close database connection
$conn->close();
?>
