

<?php
session_start();

// This code demonstrates a basic shopping cart session.
// In a real application, you'd likely use a database for persistent storage.

// ----------------- Cart Functions -----------------

/**
 * Adds an item to the cart.
 *
 * @param int $productId The ID of the product to add.
 * @param int $quantity The quantity of the product to add.
 * @return void
 */
function addToCart($productId, $quantity = 1) {
  if (isset($_SESSION['cart'])) {
    $_SESSION['cart'][$productId] = $_SESSION['cart'][$productId] ?? 0; // Use ?? to default to 0 if not set
    $_SESSION['cart'][$productId] += $quantity;
  } else {
    $_SESSION['cart'][$productId] = $quantity;
  }
}

/**
 * Removes a product from the cart.
 *
 * @param int $productId The ID of the product to remove.
 * @return void
 */
function removeFromCart($productId) {
  if (isset($_SESSION['cart'][$productId])) {
    unset($_SESSION['cart'][$productId]);
  }
}

/**
 * Updates the quantity of a product in the cart.
 *
 * @param int $productId The ID of the product to update.
 * @param int $quantity The new quantity of the product.
 * @return void
 */
function updateQuantity($productId, $quantity) {
  if (isset($_SESSION['cart'][$productId])) {
    $_SESSION['cart'][$productId] = $quantity;
  }
}

/**
 * Gets the contents of the cart.
 *
 * @return array An array representing the cart items.
 */
function getCart() {
  return $_SESSION['cart'] ?? []; // Return an empty array if 'cart' isn't set
}


// ----------------- Example Usage (For Demonstration) -----------------

// Simulate a product ID and its details
$productId = 123;
$productName = "Awesome T-Shirt";
$productPrice = 25.00;


// ----------------- Adding Items to the Cart -----------------
if (isset($_POST['add_to_cart'])) {
  // Assuming the form has an input field named "quantity"
  $quantity = intval($_POST['quantity']); // Convert to integer
  addToCart($productId, $quantity);
}

// ----------------- Displaying the Cart -----------------
$cartItems = getCart();

echo "<h2>Your Shopping Cart</h2>";

if (empty($cartItems)) {
  echo "<p>Your cart is empty.</p>";
} else {
  echo "<ul>";
  foreach ($cartItems as $id => $qty) {
    echo "<li>" . $productName . " - Quantity: " . $qty . " - Price: $" . $productPrice . "</li>";
  }
  echo "</ul>";
}

// ----------------- Example Remove Item -----------------
if (isset($_GET['remove_item'])) {
  removeFromCart($productId);
}

// ----------------- Example Update Quantity -----------------
if (isset($_POST['update_quantity'])) {
    $newQuantity = intval($_POST['quantity']);
    updateQuantity($productId, $newQuantity);
}



?>
