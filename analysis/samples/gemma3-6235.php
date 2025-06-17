

<?php

session_start();

// Session variables for the cart
$cart = array();

// Function to add an item to the cart
function addToCart($product_id, $product_name, $price, $quantity = 1) {
  global $cart;

  if (!isset($cart[$product_id])) {
    $cart[$product_id] = array(
      'name' => $product_name,
      'price' => $price,
      'quantity' => $quantity
    );
  } else {
    $cart[$product_id]['quantity'] += $quantity;
  }
}

// Function to update the quantity of an item in the cart
function updateCartQuantity($product_id, $quantity) {
  global $cart;

  if (isset($cart[$product_id])) {
    $cart[$product_id]['quantity'] = $quantity;
  }
}

// Function to remove an item from the cart
function removeCartItem($product_id) {
  global $cart;

  if (isset($cart[$product_id])) {
    unset($cart[$product_id]);
  }
}

// Function to display the cart contents
function displayCart() {
  global $cart;

  if (empty($cart)) {
    echo "<p>Your cart is empty.</p>";
    return;
  }

  echo "<h2>Your Shopping Cart</h2>";
  echo "<ul>";
  foreach ($cart as $product_id => $product_details) {
    $product_name = $product_details['name'];
    $price = $product_details['price'];
    $quantity = $product_details['quantity'];

    echo "<li>" . $product_name . " - $" . $price . " x " . $quantity . "  <button onclick='updateQuantity(" . $product_id . ", 1)'>+</button> <button onclick='updateQuantity(" . $product_id . ", -1)'>-</button> <button onclick='removeCartItem(" . $product_id . ")'>Remove</button></li>";
  }
  echo "</ul>";

  // Calculate the total price
  $total = 0;
  foreach ($cart as $product_id => $product_details) {
    $total += $product_details['price'] * $product_details['quantity'];
  }

  echo "<p><strong>Total: $" . $total . "</strong></p>";
}


// Example usage (simulating user actions)
// Add some items to the cart
addToCart(1, "Laptop", 1200, 1);
addToCart(2, "Mouse", 25, 2);
addToCart(1, "Laptop", 1200, 2); //Add another laptop


// Update the quantity of a product
updateCartQuantity(2, 3); // Increase Mouse quantity to 3


// Display the cart contents
displayCart();


// --- Session Management ---
// Save the cart data to the session
session_start();
$_SESSION['cart'] = $cart;

// To retrieve the cart data later, you can use:
// $cart = $_SESSION['cart'];
// displayCart();


?>
