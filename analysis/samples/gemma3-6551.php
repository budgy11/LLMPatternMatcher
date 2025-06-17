

<?php
session_start();

// Initialize the cart if it doesn't exist
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
    // If the product is already in the cart, update the quantity
    $_SESSION['cart'][$product_id]['quantity'] += $quantity;
  }
}

// Function to remove an item from the cart
function removeCartItem($product_id) {
  if (isset($_SESSION['cart'][$product_id])) {
    unset($_SESSION['cart'][$product_id]);
  }
}

// Function to update the quantity of an item in the cart
function updateCartQuantity($product_id, $quantity) {
    if (isset($_SESSION['cart'][$product_id])) {
        $_SESSION['cart'][$product_id]['quantity'] = $quantity;
    }
}

// Function to get the cart contents
function getCartContents() {
  return $_SESSION['cart'];
}

// Example Usage (Simulated Product Information)
$products = array(
  1 => array('id' => 1, 'name' => 'Laptop', 'price' => 1200),
  2 => array('id' => 2, 'name' => 'Mouse', 'price' => 25),
  3 => array('id' => 3, 'name' => 'Keyboard', 'price' => 75)
);


// ---  Simulating User Interactions (Example) ---

// Add a Laptop to the cart
addToCart(1, $products[1]['name'], $products[1]['price']);

// Add two Mouses to the cart
addToCart(2, $products[2]['name'], $products[2]['price'], 2);

// Get the cart contents
$cart = getCartContents();
echo "<h2>Cart Contents:</h2>";
echo "<pre>";
print_r($cart);
echo "</pre>";

// Remove a Mouse from the cart
removeCartItem(2);

// Get the updated cart contents
$cart = getCartContents();
echo "<h2>Cart Contents (After Removal):</h2>";
echo "<pre>";
print_r($cart);
echo "</pre>";

// Update the quantity of the Laptop to 3
updateCartQuantity(1, 3);

// Get the updated cart contents
$cart = getCartContents();
echo "<h2>Cart Contents (After Quantity Update):</h2>";
echo "<pre>";
print_r($cart);
echo "</pre>";

?>
