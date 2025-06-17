

<?php
session_start();

// Function to add items to the cart
function addToCart($product_id, $product_name, $price, $quantity = 1) {
  // Check if the cart exists in the session.  If not, initialize it.
  if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = array();
  }

  // Add the item to the cart array
  $_SESSION['cart'][] = array(
    'product_id' => $product_id,
    'product_name' => $product_name,
    'price' => $price,
    'quantity' => $quantity
  );
}

// Function to update the quantity of an item in the cart
function updateQuantity($product_id, $new_quantity) {
  if (isset($_SESSION['cart'])) {
    foreach ($_SESSION['cart'] as &$item) { // Use & to modify the original array
      if ($item['product_id'] == $product_id) {
        $item['quantity'] = $new_quantity;
        break;
      }
    }
  }
}

// Function to remove an item from the cart
function removeFromCart($product_id) {
  if (isset($_SESSION['cart'])) {
    $new_cart = array();
    foreach ($_SESSION['cart'] as $item) {
      if ($item['product_id'] != $product_id) {
        $new_cart[] = $item;
      }
    }
    $_SESSION['cart'] = $new_cart;
  }
}

// Function to display the cart contents
function displayCart() {
  if (empty($_SESSION['cart'])) {
    echo "<p>Your cart is empty.</p>";
  } else {
    echo "<h2>Your Cart</h2>";
    echo "<ul>";
    foreach ($_SESSION['cart'] as $item) {
      echo "<li>" . $item['product_name'] . " - $" . $item['price'] . " x " . $item['quantity'] . " = $" . ($item['price'] * $item['quantity']) . "</li>";
    }
    echo "</ul>";
  }
}

// Example Usage - this would typically be triggered by a button click or form submission
// For demonstration purposes, let's add a few items:
addToCart(1, "Laptop", 1200, 1);
addToCart(2, "Mouse", 25, 2);
addToCart(3, "Keyboard", 75, 1);

// Example of updating the quantity
updateQuantity(2, 3);  // Change the quantity of the mouse to 3

// Example of removing an item
// removeFromCart(3);  // Remove the keyboard

// Display the cart
displayCart();

// To actually render this on a webpage, you would need to wrap this code
// within an HTML structure.  For example:

// <html>
// <head>
//   <title>Shopping Cart</title>
// </head>
// <body>
//   <h1>Shopping Cart</h1>
//   <?php displayCart(); ?>
