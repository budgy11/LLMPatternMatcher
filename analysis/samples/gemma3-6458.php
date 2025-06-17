

<?php

session_start();

// Initialize the cart if it doesn't exist
if (!isset($_SESSION['cart'])) {
  $_SESSION['cart'] = [];
}

// Function to add an item to the cart
function addToCart($product_id, $product_name, $price, $quantity = 1) {
  if (!isset($_SESSION['cart'][$product_id])) {
    $_SESSION['cart'][$product_id] = [
      'name' => $product_name,
      'price' => $price,
      'quantity' => $quantity
    ];
  } else {
    $_SESSION['cart'][$product_id]['quantity'] += $quantity;
  }
}

// Function to update the quantity of an item in the cart
function updateQuantity($product_id, $quantity) {
    if (isset($_SESSION['cart'][$product_id])) {
      $_SESSION['cart'][$product_id]['quantity'] = $quantity;
    } else {
        // If the item isn't in the cart, you might want to handle this.
        // For example, you could create a new entry or just ignore it.
        // In this simple example, we'll ignore it.
        echo "Item with ID $product_id not found in cart.";
    }
}

// Function to remove an item from the cart
function removeCartItem($product_id) {
  if (isset($_SESSION['cart'][$product_id])) {
    unset($_SESSION['cart'][$product_id]);
  }
}

// Function to get the cart contents
function getCartContents() {
  return $_SESSION['cart'];
}


// --- Example Usage (Simulating User Interaction) ---

// Add some items to the cart
addToCart(1, "Shirt", 20.00, 2);
addToCart(2, "Pants", 30.00, 1);
addToCart(1, "Shirt", 20.00, 1); // Add another shirt

// Display the cart contents
$cart = getCartContents();
echo "<h2>Your Cart</h2>";
if (empty($cart)) {
  echo "<p>Your cart is empty.</p>";
} else {
  echo "<ul>";
  foreach ($cart as $product_id => $product_details) {
    echo "<li>" . $product_details['name'] . " - $" . $product_details['price'] . " x " . $product_details['quantity'] . " = $" . ($product_details['price'] * $product_details['quantity']) . "</li>";
  }
  echo "</ul>";
}

// Update the quantity of an item
updateQuantity(1, 3); // Update quantity of "Shirt" to 3

// Remove an item
removeCartItem(2); // Remove "Pants"

// Display the updated cart contents
$cart = getCartContents();
echo "<h2>Your Cart (Updated)</h2>";
if (empty($cart)) {
  echo "<p>Your cart is empty.</p>";
} else {
  echo "<ul>";
  foreach ($cart as $product_id => $product_details) {
    echo "<li>" . $product_details['name'] . " - $" . $product_details['price'] . " x " . $product_details['quantity'] . " = $" . ($product_details['price'] * $product_details['quantity']) . "</li>";
  }
  echo "</ul>";
}

?>
