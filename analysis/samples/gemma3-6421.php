

<?php

// Start the session if it's not already running
session_start();

// --- Cart Functions ---

// Add an item to the cart
function addToCart($product_id, $product_name, $price, $quantity = 1) {
  // Check if the cart exists in session
  if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = array();
  }

  // Add the item to the cart
  $_SESSION['cart'][$product_id] = array(
    'name' => $product_name,
    'price' => $price,
    'quantity' => $quantity
  );

  // Optional:  You might want to update the quantity if the item already exists
  // In this simplified example, we just add a new entry.
}

// Remove an item from the cart
function removeFromCart($product_id) {
  if (isset($_SESSION['cart'][$product_id])) {
    unset($_SESSION['cart'][$product_id]);
  }
}

// Update the quantity of an item in the cart
function updateQuantity($product_id, $quantity) {
  if (isset($_SESSION['cart'][$product_id])) {
    $_SESSION['cart'][$product_id]['quantity'] = $quantity;
  }
}

// Get the cart contents
function getCartContents() {
  return $_SESSION['cart'];
}

// Calculate the total cart value
function calculateTotal() {
    $total = 0;
    if (isset($_SESSION['cart'])) {
        foreach($_SESSION['cart'] as $item) {
            $total = $total + ($item['price'] * $item['quantity']);
        }
    }
    return $total;
}

// --- Example Usage (Illustrative - Replace with your actual logic) ---

// Add some items to the cart
addToCart(1, 'Laptop', 1200, 1);
addToCart(2, 'Mouse', 25, 2);
addToCart(1, 'Laptop', 1200, 2); // Add another laptop

// Display the cart contents
echo "<h2>Your Shopping Cart</h2>";
if (empty($_SESSION['cart'])) {
  echo "<p>Your cart is empty.</p>";
} else {
  echo "<ul>";
  foreach ($_SESSION['cart'] as $product_id => $item) {
    echo "<li>" . $item['name'] . " - $" . $item['price'] . " x " . $item['quantity'] . " = $" . ($item['price'] * $item['quantity']) . "</li>";
  }
  echo "</ul>";
  echo "<p><strong>Total: $" . calculateTotal() . "</strong></p>";
}


// Example of removing an item
//removeFromCart(2);

// Update quantity
//updateQuantity(1, 3);


?>
