

<?php

// Database Connection (Replace with your actual database credentials)
$host = "localhost";
$username = "your_username";
$password = "your_password";
$database = "your_database";

// Connect to the database
$conn = new mysqli($host, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

// --- Products Data (Example - Replace with your product information) ---
$products = [
    0 => ['id' => 1, 'name' => 'T-Shirt', 'price' => 20.00],
    1 => ['id' => 2, 'name' => 'Jeans', 'price' => 50.00],
    2 => ['id' => 3, 'name' => 'Hat', 'price' => 15.00],
];

// --- Cart (Simple implementation - could be stored in a session) ---
$cart = [];

// --- Functions ---

/**
 * Adds a product to the cart.
 *
 * @param int $product_id The ID of the product to add.
 * @param int $quantity  The quantity to add.  Defaults to 1.
 */
function addToCart(int $product_id, int $quantity = 1) {
  global $cart;

  // Check if the product exists
  if (!isset($products[$product_id])) {
    echo "<p>Product ID $product_id not found.</p>";
    return;
  }

  // Check if product is already in the cart
  if (isset($cart[$product_id])) {
    $cart[$product_id]['quantity'] += $quantity;
  } else {
    $cart[$product_id] = ['quantity' => $quantity, 'price' => $products[$product_id]['price']];
  }

  // You might want to log this action for tracking
  // logCartUpdate($product_id, $quantity);
}

/**
 * Calculates the total cart value.
 *
 * @return float The total cart value.
 */
function calculateTotal() {
  global $cart;
  $total = 0;
  foreach ($cart as $item_id => $item) {
    $total = $total + ($item['price'] * $item['quantity']);
  }
  return round($total, 2); // Round to 2 decimal places for currency
}

/**
 *  Clears the cart.
 */
function clearCart() {
    global $cart;
    $cart = [];
}


// --- Handle Form Submission (Example) ---

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['add_to_cart'])) {
        $product_id = (int)$_POST['product_id']; // Cast to integer
        addToCart($product_id);
    }
    if (isset($_POST['clear_cart'])) {
        clearCart();
    }
}


// --- Display Cart (Example) ---

echo "<h1>Shopping Cart</h1>";

if (empty($cart)) {
  echo "<p>Your cart is empty.</p>";
} else {
  echo "<ul>";
  foreach ($cart as $item_id => $item) {
    echo "<li>" . $products[$item_id]['name'] . " - $" . $item['price'] . " x " . $item['quantity'] . " = $" . $item['price'] * $item['quantity'] . "</li>";
  }
  echo "</ul>";
  echo "<p><strong>Total: $" . calculateTotal() . "</strong></p>";
  echo "<form method='post'>
            <button type='submit' name='clear_cart'>Clear Cart</button>
          </form>";
}

?>
