
<!DOCTYPE html>
<html>
<head>
    <title>PHP Cart Session Example</title>
</head>
<body>

<h1>PHP Cart Session Example</h1>

<p>This example demonstrates a simple cart session implementation in PHP.</p>

</body>
</html>


<?php

session_start();

// Function to add an item to the cart
function addToCart($product_id, $product_name, $price, $quantity = 1) {
  // Check if the cart already exists
  if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
  }

  // Create an associative array for the item
  $item = [
    'id' => $product_id,
    'name' => $product_name,
    'price' => $price,
    'quantity' => $quantity
  ];

  // Add the item to the cart
  $_SESSION['cart'][] = $item;
}

// Function to update the quantity of an item in the cart
function updateCartQuantity($product_id, $quantity) {
    if (isset($_SESSION['cart']) && isset($_SESSION['cart'][$product_id])) {
        $_SESSION['cart'][$product_id]['quantity'] = $quantity;
    }
}


// Function to remove an item from the cart
function removeCartItem($product_id) {
  if (isset($_SESSION['cart']) && isset($_SESSION['cart'][$product_id])) {
    unset($_SESSION['cart'][$product_id]);
  }
}

// Function to get the cart contents
function getCartContents() {
  if (isset($_SESSION['cart'])) {
    return $_SESSION['cart'];
  }
  return [];
}

// Example Usage:

// Add an item to the cart
addToCart(1, "T-Shirt", 20.00, 2);
addToCart(2, "Jeans", 50.00);

// Update the quantity of the T-Shirt
updateCartQuantity(1, 5); // Increase quantity of product 1 to 5

// Get the cart contents
$cart = getCartContents();
echo "<h2>Cart Contents:</h2>";
if (empty($cart)) {
  echo "<p>Your cart is empty.</p>";
} else {
  echo "<ul>";
  foreach ($cart as $item) {
    echo "<li>" . $item['name'] . " - $" . $item['price'] . " x " . $item['quantity'] . " = $" . $item['price'] * $item['quantity'] . "</li>";
  }
  echo "</ul>";
}


// Example: Removing an item
//removeCartItem(1);
// $cart = getCartContents();
// echo "<h2>Cart Contents after removing T-Shirt:</h2>";
// echo "<ul>";
// foreach ($cart as $item) {
//     echo "<li>" . $item['name'] . " - $" . $item['price'] . " x " . $item['quantity'] . " = $" . $item['price'] * $item['quantity'] . "</li>";
// }
// echo "</ul>";
?>
