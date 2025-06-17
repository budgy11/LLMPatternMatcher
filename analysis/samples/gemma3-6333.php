

<?php
session_start(); // Start the session

// Function to add an item to the cart
function addToCart($product_id, $product_name, $price, $quantity = 1) {
  // Check if the cart exists in the session
  if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = array();
  }

  // Add the item to the cart
  $_SESSION['cart'][$product_id] = array(
    'name' => $product_name,
    'price' => $price,
    'quantity' => $quantity
  );
}

// Function to update the quantity of an item in the cart
function updateCartQuantity($product_id, $quantity) {
  if (isset($_SESSION['cart'][$product_id])) {
    $_SESSION['cart'][$product_id]['quantity'] = $quantity;
  }
}

// Function to remove an item from the cart
function removeFromCart($product_id) {
  if (isset($_SESSION['cart'][$product_id])) {
    unset($_SESSION['cart'][$product_id]);
  }
}

// Function to get the cart items
function getCartItems() {
  return $_SESSION['cart'];
}


// Example usage:

// 1. Add an item to the cart
addToCart(1, "Laptop", 1200, 1);
addToCart(2, "Mouse", 25, 2);

// 2. Update the quantity of a product
updateCartQuantity(2, 5); // Increase the quantity of product ID 2 to 5

// 3. Get the cart items
$cart = getCartItems();
print_r($cart);  // Display the cart contents for debugging

// 4. Remove an item from the cart
removeFromCart(1);


// Display the cart items again to show the changes
$cart = getCartItems();
print_r($cart);



?>
