

<?php
session_start();

// Initialize the cart if it doesn't exist
if (!isset($_SESSION['cart'])) {
  $_SESSION['cart'] = array();
}

// Example function to add an item to the cart
function addItemToCart($product_id, $product_name, $price, $quantity) {
  // Check if the product is already in the cart
  if (isset($_SESSION['cart'][$product_id])) {
    // Product exists, increase the quantity
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

// Example function to remove an item from the cart
function removeItemFromCart($product_id) {
  if (isset($_SESSION['cart'][$product_id])) {
    unset($_SESSION['cart'][$product_id]);
  }
}

// Example function to update the quantity of an item in the cart
function updateQuantity($product_id, $new_quantity) {
  if (isset($_SESSION['cart'][$product_id])) {
    $_SESSION['cart'][$product_id]['quantity'] = $new_quantity;
  }
}

// Example function to get the cart contents
function getCartContents() {
  return $_SESSION['cart'];
}

// --- Example Usage (Demonstration) ---

// Add a few items to the cart
addItemToCart(1, "Laptop", 1200, 1);
addItemToCart(2, "Mouse", 25, 2);
addItemToCart(1, "Laptop", 1200, 1); // Adding another laptop - quantity will be updated

// Display the cart contents
echo "<h2>Shopping Cart</h2>";
if (empty($_SESSION['cart'])) {
  echo "<p>Your cart is empty.</p>";
} else {
  echo "<ul>";
  foreach ($_SESSION['cart'] as $product_id => $product_details) {
    echo "<li>" . $product_details['name'] . " - $" . $product_details['price'] . " x " . $product_details['quantity'] . " = $" . ($product_details['price'] * $product_details['quantity']) . "</li>";
  }
  echo "</ul>";
}

// Remove an item
// removeItemFromCart(2);

// Update Quantity
// updateQuantity(1, 3); // Update the quantity of Laptop to 3

// Display updated cart contents
// echo "<h2>Updated Shopping Cart</h2>";
// if (empty($_SESSION['cart'])) {
//   echo "<p>Your cart is empty.</p>";
// } else {
//   echo "<ul>";
//   foreach ($_SESSION['cart'] as $product_id => $product_details) {
//     echo "<li>" . $product_details['name'] . " - $" . $product_details['price'] . " x " . $product_details['quantity'] . " = $" . ($product_details['price'] * $product_details['quantity']) . "</li>";
//   }
//   echo "</ul>";
// }


?>
