

<?php
session_start();

// Initialize the cart (an associative array)
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
function updateCartQuantity($product_id, $new_quantity) {
  if (isset($_SESSION['cart'][$product_id])) {
    $_SESSION['cart'][$product_id]['quantity'] = $new_quantity;
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

// Example Usage (Simulating user actions)
// Add a product to the cart
addToCart(1, 'T-Shirt', 20, 2);
addToCart(2, 'Jeans', 50, 1);

// Update the quantity of a product
updateCartQuantity(1, 3);

// Display the cart contents
$cart = getCartContents();

// Print the cart data for demonstration
echo "<h2>Your Shopping Cart:</h2>";
if (empty($cart)) {
  echo "<p>Your cart is empty.</p>";
} else {
  echo "<ul>";
  foreach ($cart as $product_id => $product_data) {
    echo "<li>";
    echo "<strong>Product:</strong> " . $product_data['name'] . "<br>";
    echo "<strong>Price:</strong> $" . $product_data['price'] . "<br>";
    echo "<strong>Quantity:</strong> " . $product_data['quantity'] . "<br>";
    echo "<strong>Total for Product:</strong> $" . ($product_data['price'] * $product_data['quantity']) . "<br>";
    echo "</li>";
  }
  echo "</ul>";
}

?>
