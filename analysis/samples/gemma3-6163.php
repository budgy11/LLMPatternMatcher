

<?php
session_start();

// Initialize the cart if it doesn't exist
if (!isset($_SESSION['cart'])) {
  $_SESSION['cart'] = [];
}

// Function to add an item to the cart
function addToCart($product_id, $product_name, $price, $quantity = 1) {
  if (!isset($_SESSION['cart'][$product_id])) {
    $_SESSION['cart'][$product_id] = ['name' => $product_name, 'price' => $price, 'quantity' => $quantity];
  } else {
    $_SESSION['cart'][$product_id]['quantity'] += $quantity;
  }
}

// Function to remove an item from the cart
function removeFromCart($product_id) {
  if (isset($_SESSION['cart'][$product_id])) {
    unset($_SESSION['cart'][$product_id]);
  }
}

// Function to update the quantity of an item in the cart
function updateQuantity($product_id, $new_quantity) {
  if (isset($_SESSION['cart'][$product_id])) {
    $_SESSION['cart'][$product_id]['quantity'] = $new_quantity;
  }
}

// Function to get all items in the cart
function getCartItems() {
  return $_SESSION['cart'];
}

// Function to clear the cart
function clearCart() {
  unset($_SESSION['cart']);
}

// --- Example Usage (Simulated - Replace with actual product/database calls) ---

// Add some items to the cart
addToCart(1, "Laptop", 1200, 1);
addToCart(2, "Mouse", 25, 2);
addToCart(1, "Laptop", 1200, 1); // Adding another laptop

// Display the cart contents
$cartItems = getCartItems();
echo "<h2>Your Shopping Cart</h2>";

if (empty($cartItems)) {
  echo "<p>Your cart is empty.</p>";
} else {
  echo "<ul>";
  foreach ($cartItems as $product_id => $item) {
    echo "<li>" . $item['name'] . " - $" . $item['price'] . " x " . $item['quantity'] . " = $" . ($item['price'] * $item['quantity']) . "</li>";
  }
  echo "</ul>";
}

// Example: Remove an item
// removeFromCart(2);

// Example: Update quantity
// updateQuantity(1, 3);

// Example: Clear the cart
// clearCart();

?>
