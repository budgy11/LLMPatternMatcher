

<?php
session_start();

// Initialize the cart session if it doesn't exist
if (!isset($_SESSION['cart'])) {
  $_SESSION['cart'] = array();
}

// Function to add an item to the cart
function addToCart($product_id, $product_name, $price, $quantity = 1) {
  // Check if the item is already in the cart
  if (isset($_SESSION['cart'][$product_id])) {
    // Item already exists, increase the quantity
    $_SESSION['cart'][$product_id]['quantity'] += $quantity;
  } else {
    // Item doesn't exist, add it to the cart
    $_SESSION['cart'][$product_id] = array(
      'name' => $product_name,
      'price' => $price,
      'quantity' => $quantity
    );
  }
}

// Function to update the quantity of an item in the cart
function updateQuantity($product_id, $new_quantity) {
  if (isset($_SESSION['cart'][$product_id])) {
    $_SESSION['cart'][$product_id]['quantity'] = $new_quantity;
  }
}

// Function to remove an item from the cart
function removeItem($product_id) {
  if (isset($_SESSION['cart'][$product_id])) {
    unset($_SESSION['cart'][$product_id]);
  }
}

// Function to get the cart contents
function getCartContents() {
  return $_SESSION['cart'];
}

// Example usage:

// Add an item to the cart
addToCart(1, "Awesome T-Shirt", 20, 2);
addToCart(2, "Cool Hat", 15, 1);

// Update the quantity of an item
updateQuantity(1, 3);

// Get the cart contents
$cart = getCartContents();
echo "<pre>";
print_r($cart);
echo "</pre>";


// Example:  Simulating a request to display the cart contents on a webpage
?>
