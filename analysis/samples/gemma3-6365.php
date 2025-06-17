

<?php
session_start();

// Initialize the cart if it doesn't exist
if (!isset($_SESSION['cart'])) {
  $_SESSION['cart'] = array();
}

// Function to add an item to the cart
function addToCart($product_id, $product_name, $price, $quantity = 1) {
  // Check if the product is already in the cart
  if (isset($_SESSION['cart'][$product_id])) {
    // Product exists, increase quantity
    $_SESSION['cart'][$product_id]['quantity'] += $quantity;
  } else {
    // Product doesn't exist, add it to the cart
    $_SESSION['cart'][$product_id] = array(
      'name' => $product_name,
      'price' => $price,
      'quantity' => $quantity
    );
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

// --- Example Usage (Replace with your actual data) ---

// Add some items to the cart
addToCart(1, "T-Shirt", 20.00, 2);
addToCart(2, "Jeans", 50.00, 1);
addToCart(1, "T-Shirt", 20.00, 3); // Add another T-Shirt

// Display the cart contents
echo "<h2>Your Cart</h2>";
if (empty($_SESSION['cart'])) {
  echo "<p>Your cart is empty.</p>";
} else {
  echo "<ul>";
  foreach ($_SESSION['cart'] as $product_id => $product_data) {
    $product_name = $product_data['name'];
    $price = $product_data['price'];
    $quantity = $product_data['quantity'];
    echo "<li>" . $product_name . " - $" . $price . " x " . $quantity . " = $" . ($price * $quantity) . "</li>";
  }
  echo "</ul>";
}

// Remove an item from the cart (e.g., remove the first T-Shirt)
// removeFromCart(1);

// Update the quantity of an item (e.g., update the quantity of Jeans to 2)
// updateQuantity(2, 3);

// --- End Example Usage ---
?>
