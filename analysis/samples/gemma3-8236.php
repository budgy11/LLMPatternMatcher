

<?php
session_start();

// Database connection (Replace with your actual credentials)
$db_host = "localhost";
$db_user = "your_user";
$db_password = "your_password";
$db_name = "your_database";

// Create a database connection
$conn = new mysqli($db_host, $db_user, $db_password, $db_name);

// Check connection
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

// Cart data (stored in the session)
if (!isset($_SESSION['cart'])) {
  $_SESSION['cart'] = [];
}

// Function to add an item to the cart
function addToCart($conn, $product_id, $quantity) {
  global $db_host, $db_user, $db_password, $db_name;

  // Check if the item is already in the cart
  if (isset($_SESSION['cart'][$product_id])) {
    $_SESSION['cart'][$product_id]['quantity'] += $quantity;
  } else {
    // Item not in cart, add it with initial quantity
    $_SESSION['cart'][$product_id] = [
      'id' => $product_id,
      'quantity' => $quantity,
      'name' => 'Product Name (Replace with actual product name)',  //Important: Replace with the real product name.
      'price' => 0  // Replace with the real product price
    ];
  }
}

// Function to remove an item from the cart
function removeFromCart($conn, $product_id) {
  unset($_SESSION['cart'][$product_id]);
}

// Function to update the quantity of an item in the cart
function updateQuantity($conn, $product_id, $quantity) {
  if (isset($_SESSION['cart'][$product_id])) {
    $_SESSION['cart'][$product_id]['quantity'] = $quantity;
  }
}

// Function to display the cart
function displayCart() {
  echo "<h2>Shopping Cart</h2>";
  if (empty($_SESSION['cart'])) {
    echo "<p>Your cart is empty.</p>";
    return;
  }

  echo "<ul>";
  foreach ($_SESSION['cart'] as $product_id => $product_details) {
    echo "<li>";
    echo "<strong>Name:</strong> " . $product_details['name'] . "<br>";
    echo "<strong>Price:</strong> $" . $product_details['price'] . "<br>";
    echo "<strong>Quantity:</strong> " . $product_details['quantity'] . "<br>";
    echo "<strong>Subtotal:</strong> $" . ($product_details['price'] * $product_details['quantity']) . "<br>";
    echo "<form method='post' action='" . htmlspecialchars($_SERVER['PHP_SELF']) . "'>";
    echo "<input type='hidden' name='product_id' value='" . $product_id . "'>";
    echo "<input type='number' name='quantity' value='" . $product_details['quantity'] . "' min='1' max='100' style='width:50px;'>"; //Added min/max for quantity
    echo "<button type='submit'>Update</button>";
    echo "</form>";
    echo "</li>";
  }
  echo "</ul>";

  // Calculate total price
  $total = 0;
  foreach ($_SESSION['cart'] as $product_id => $product_details) {
    $total += ($product_details['price'] * $product_details['quantity']);
  }
  echo "<p><strong>Total:</strong> $" . $total . "</p>";

  // Checkout button (placeholder)
  echo "<form method='post' action='checkout.php'>";
  echo "<button type='submit'>Checkout</button>";
  echo "</form>";
}

// Handle form submission for adding items to cart
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  if (isset($_POST['product_id']) && isset($_POST['quantity'])) {
    $product_id = $_POST['product_id'];
    $quantity = intval($_POST['quantity']); // Ensure quantity is an integer

    addToCart($conn, $product_id, $quantity);
  }
}

// Handle form submission for removing items from cart
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['remove_product_id'])) {
    $product_id = $_POST['remove_product_id'];
    removeFromCart($conn, $product_id);
}


// Display the cart
displayCart();
?>
