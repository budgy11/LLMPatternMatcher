

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
      'quantity' => $quantity,
    ];
  } else {
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
function updateQuantity($product_id, $new_quantity) {
  if (isset($_SESSION['cart'][$product_id])) {
    $_SESSION['cart'][$product_id]['quantity'] = $new_quantity;
  }
}


// Example Usage:
// Add an item to the cart
addToCart(1, "Laptop", 1200, 1); // Product ID 1, Laptop, Price $1200, Quantity 1
addToCart(2, "Mouse", 25, 2);    // Product ID 2, Mouse, Price $25, Quantity 2

// Display the cart contents
echo "<h2>Your Shopping Cart</h2>";
if (empty($_SESSION['cart'])) {
  echo "<p>Your cart is empty.</p>";
} else {
  echo "<ul>";
  foreach ($_SESSION['cart'] as $product_id => $product_data) {
    echo "<li>";
    echo "<strong>Product:</strong> " . $product_data['name'] . "<br>";
    echo "<strong>Price:</strong> $" . $product_data['price'] . "<br>";
    echo "<strong>Quantity:</strong> " . $product_data['quantity'] . "<br>";
    echo "<strong>Total:</strong> $" . ($product_data['price'] * $product_data['quantity']) . "<br>";
    echo "</li>";
  }
  echo "</ul>";
}

// Example of removing an item
// removeCartItem(2); // Remove the Mouse

// Example of updating quantity
// updateQuantity(1, 3); // Update the quantity of the Laptop to 3

// You would typically have a method for calculating the total cart price here.
?>
