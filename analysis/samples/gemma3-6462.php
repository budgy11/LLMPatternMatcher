

<?php
session_start();

// Initialize the cart session if it doesn't exist
if (!isset($_SESSION['cart'])) {
  $_SESSION['cart'] = array();
}

// Function to add an item to the cart
function addToCart($product_id, $product_name, $price, $quantity = 1) {
  if (!isset($_SESSION['cart'][$product_id])) {
    $_SESSION['cart'][$product_id] = array(
      'name' => $product_name,
      'price' => $price,
      'quantity' => $quantity
    );
  } else {
    $_SESSION['cart'][$product_id]['quantity'] += $quantity;
  }
}

// Function to update the quantity of an item in the cart
function updateQuantity($product_id, $new_quantity) {
  if (isset($_SESSION['cart'][$product_id])) {
    $_SESSION['cart'][$product_id]['quantity'] = $new_quantity;
  } else {
    // Handle the case where the product isn't in the cart.  You might want
    // to add it with the default quantity of 1, or simply ignore it.
    // In this example, we'll ignore it.
    // echo "Product ID {$product_id} not found in cart. Ignoring update.";
  }
}

// Function to remove an item from the cart
function removeFromCart($product_id) {
  if (isset($_SESSION['cart'][$product_id])) {
    unset($_SESSION['cart'][$product_id]);
  }
}

// Function to get the cart contents
function getCartContents() {
  return $_SESSION['cart'];
}

// Function to clear the entire cart
function clearCart() {
  $_SESSION['cart'] = array();
}


// -----------------------------------  Example Usage (Illustrative) -----------------------------------

// Add an item to the cart
addToCart(101, "Awesome T-Shirt", 25.00, 2); // Product ID 101, name, price, quantity

// Add another item
addToCart(102, "Cool Hat", 15.00); // Add a hat

// Get the cart contents
$cart = getCartContents();
print_r($cart);

// Update the quantity of a product
updateQuantity(101, 5); // Increase the quantity of the T-Shirt to 5
print_r($cart);

// Remove an item
removeFromCart(102);
print_r($cart);


// Clear the cart
// clearCart();
// print_r($cart); // Empty array
?>
