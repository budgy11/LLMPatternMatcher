

<?php
session_start();

// Initialize the cart array if it doesn't exist
if (!isset($_SESSION['cart'])) {
  $_SESSION['cart'] = array();
}

// Function to add an item to the cart
function addToCart($product_id, $product_name, $price, $quantity = 1) {
  // Check if the product is already in the cart
  if (isset($_SESSION['cart'][$product_id])) {
    // Product already exists, update the quantity
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

// Function to get the cart contents
function getCartContents() {
  return $_SESSION['cart'];
}

// Example Usage (Illustrative - Replace with your actual product data)

// Add some items to the cart
addToCart(1, "Shirt", 25.00, 2);
addToCart(2, "Pants", 50.00, 1);
addToCart(1, "Shirt", 25.00, 1); // Add another shirt

// Display the cart contents
echo "<h2>Cart Contents:</h2>";
echo "<ul>";
$cartContents = getCartContents();

if (empty($cartContents)) {
  echo "<li>Cart is empty.</li>";
} else {
  foreach ($cartContents as $product_id => $productData) {
    echo "<li>" . $productData['name'] . " - $" . $productData['price'] . " x " . $productData['quantity'] . " = $" . ($productData['price'] * $productData['quantity']) . "</li>";
  }
}
echo "</ul>";

// Example of removing an item
// removeFromCart(2);

// Example of updating quantity
// updateQuantity(1, 5);

// Display updated cart contents after update
// echo "<h2>Cart Contents After Update:</h2>";
// $cartContents = getCartContents();
// foreach ($cartContents as $product_id => $productData) {
//   echo "<li>" . $productData['name'] . " - $" . $productData['price'] . " x " . $productData['quantity'] . " = $" . ($productData['price'] * $productData['quantity']) . "</li>";
// }

?>
