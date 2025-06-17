

<?php
session_start();

// Check if the cart is already initialized
if (!isset($_SESSION['cart'])) {
  $_SESSION['cart'] = array();
}

// Function to add an item to the cart
function addToCart($product_id, $quantity) {
  global $cart;

  // Check if the product is already in the cart
  if (isset($cart[$product_id])) {
    $cart[$product_id]['quantity'] += $quantity;
  } else {
    $cart[$product_id] = array('quantity' => $quantity);
  }
}

// Function to remove an item from the cart
function removeFromCart($product_id) {
  unset($cart[$product_id]);
}

// Function to update the quantity of an item in the cart
function updateQuantity($product_id, $new_quantity) {
    if (isset($cart[$product_id])) {
        $cart[$product_id]['quantity'] = $new_quantity;
    }
}

// --- Example Usage (Simulating a user interaction) ---

// 1. Adding an item to the cart
addToCart(1, 2); // Add 2 of product ID 1
addToCart(2, 1); // Add 1 of product ID 2
addToCart(1, 3); // Add 3 more of product ID 1

// 2. Displaying the cart contents
echo "<h2>Your Cart</h2>";
if (empty($cart)) {
  echo "<p>Your cart is empty.</p>";
} else {
  echo "<ul>";
  foreach ($cart as $product_id => $item) {
    echo "<li>Product ID: " . $product_id . ", Quantity: " . $item['quantity'] . "</li>";
  }
  echo "</ul>";
}


// 3. Removing an item from the cart
// removeFromCart(2);

// 4.  Updating a quantity
// updateQuantity(1, 5);

// Display the cart contents after updating.
// echo "<h2>Your Cart (Updated)</h2>";
// if (empty($cart)) {
//    echo "<p>Your cart is empty.</p>";
// } else {
//   echo "<ul>";
//   foreach ($cart as $product_id => $item) {
//     echo "<li>Product ID: " . $product_id . ", Quantity: " . $item['quantity'] . "</li>";
//   }
//   echo "</ul>";
// }


// Example HTML for adding items to the cart (simulating a form)
?>
