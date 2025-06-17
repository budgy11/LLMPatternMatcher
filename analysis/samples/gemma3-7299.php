

<?php

// Database Connection (Replace with your database details)
$servername = "localhost";
$username = "your_username";
$password = "your_password";
$dbname = "your_database_name";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

// Initialize session variables
session_start();

// --- Functions ---

/**
 * Adds a product to the cart.
 *
 * @param int $product_id The ID of the product to add.
 * @return void
 */
function addToCart($product_id) {
  // Check if the cart exists in the session
  if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
  }

  // Check if the product is already in the cart
  if (isset($_SESSION['cart'][$product_id])) {
    // If it exists, increment the quantity
    $_SESSION['cart'][$product_id]['quantity']++;
  } else {
    // If it doesn't exist, add it to the cart with quantity 1
    $_SESSION['cart'][$product_id] = ['quantity' => 1];
  }
}

/**
 * Retrieves the cart contents.
 *
 * @return array  An array representing the cart contents.
 */
function getCart() {
  return $_SESSION['cart'] ?? []; // Use null coalesce operator for safety
}

/**
 * Updates the quantity of a product in the cart.
 *
 * @param int $product_id The ID of the product to update.
 * @param int $quantity The new quantity.
 * @return void
 */
function updateCartQuantity($product_id, $quantity) {
  if (isset($_SESSION['cart'][$product_id])) {
    $_SESSION['cart'][$product_id]['quantity'] = $quantity;
  }
}

/**
 * Removes a product from the cart.
 *
 * @param int $product_id The ID of the product to remove.
 * @return void
 */
function removeCartItem($product_id) {
  if (isset($_SESSION['cart'][$product_id])) {
    unset($_SESSION['cart'][$product_id]);
  }
}


/**
 * Calculates the total cart value.
 *
 * @return float The total value.
 */
function calculateCartTotal() {
  $total = 0;
  $cart = getCart();
  foreach ($cart as $product_id => $item) {
    $product_id = (int)$product_id;  // Ensure product_id is an integer

    // Fetch product price from a database (replace with your actual query)
    $product_price = $this->getProductPrice($product_id); // Assuming a getProductPrice() function
    $total += $product_price * $item['quantity'];
  }
  return round($total, 2); // Round to 2 decimal places for currency
}


/**
 * A helper function to fetch product price from the database.
 * This is a placeholder.  Replace with your actual database query.
 *
 * @param int $product_id
 * @return float
 */
function getProductPrice($product_id) {
    // Replace this with your actual database query to fetch the price
    // Example:
    // $query = "SELECT price FROM products WHERE id = ?";
    // $stmt = $conn->prepare($query);
    // $stmt->bind_param("i", $product_id);
    // $stmt->execute();
    // $result = $stmt->get_result();
    // $row = $result->fetch_assoc();
    // return $row['price'];

    // Placeholder - returns a hardcoded price for demonstration purposes
    return 50.00; //  Example product price
}



// --- Purchase Functionality ---

// Example: Handle adding to cart
if (isset($_POST['add_to_cart'])) {
  $product_id = (int)$_POST['product_id']; // Ensure product_id is an integer
  addToCart($product_id);
  $message = "Product added to cart.";
}

// Example: Handle updating cart quantity
if (isset($_POST['update_quantity'])) {
    $product_id = (int)$_POST['product_id'];
    $quantity = (int)$_POST['quantity'];
    updateCartQuantity($product_id, $quantity);
    $message = "Cart updated.";
}

// Example: Handle removing from cart
if (isset($_POST['remove_from_cart'])) {
  $product_id = (int)$_POST['product_id'];
  removeCartItem($product_id);
  $message = "Product removed from cart.";
}



// --- Display Cart (Example - can be improved with HTML/CSS) ---

$cart = getCart();
$total = calculateCartTotal();

echo "<h2>Your Cart</h2>";
if (empty($cart)) {
  echo "<p>Your cart is empty.</p>";
} else {
  echo "<ul>";
  foreach ($cart as $product_id => $item) {
    echo "<li>Product ID: " . $product_id . ", Quantity: " . $item['quantity'] . "</li>";
  }
  echo "</ul>";
  echo "<p>Total: $" . $total . "</p>";
}

?>
