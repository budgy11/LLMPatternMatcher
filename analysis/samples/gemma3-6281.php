

<?php

session_start(); // Start the session

// Function to add an item to the cart
function addToCart($product_id, $quantity, $product_name, $product_price) {
  if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
  }

  // Check if the product already exists in the cart
  if (isset($_SESSION['cart'][$product_id])) {
    $_SESSION['cart'][$product_id]['quantity'] += $quantity;
  } else {
    $_SESSION['cart'][$product_id] = [
      'quantity' => $quantity,
      'name' => $product_name,
      'price' => $product_price,
    ];
  }
}

// Function to remove an item from the cart
function removeFromCart($product_id) {
  if (isset($_SESSION['cart'][$product_id])) {
    unset($_SESSION['cart'][$product_id]);
  }
}

// Function to update the quantity of an item in the cart
function updateQuantity($product_id, $quantity) {
  if (isset($_SESSION['cart'][$product_id])) {
    $_SESSION['cart'][$product_id]['quantity'] = $quantity;
  }
}

// Example Usage (Simulating a product being added to the cart)
// Assuming a product ID of 1, quantity of 2, product name "Laptop", and price of $1000
addToCart(1, 2, "Laptop", 1000);

// Example Usage (Simulating a product being removed from the cart)
// Assuming a product ID of 1
//removeFromCart(1);

// Example Usage (Simulating updating the quantity)
// Assuming a product ID of 1 and a new quantity of 3
//updateQuantity(1, 3);

// Displaying the Cart Contents
if (isset($_SESSION['cart'])) {
  echo "<h2>Shopping Cart</h2>";
  echo "<ul>";
  foreach ($_SESSION['cart'] as $product_id => $product_details) {
    echo "<li>";
    echo "<strong>Product:</strong> " . $product_details['name'] . "<br>";
    echo "<strong>Quantity:</strong> " . $product_details['quantity'] . "<br>";
    echo "<strong>Price per item:</strong> $" . $product_details['price'] . "<br>";
    echo "<strong>Total for this item:</strong> $" . $product_details['quantity'] * $product_details['price'] . "<br>";
    echo "</li>";
  }
  echo "</ul>";
} else {
  echo "<p>Your cart is empty.</p>";
}

//  Optional:  Implement session expiration (important for security)
//  This is a basic example.  In a real application, you'd use a more robust mechanism.
//  This is just to illustrate the concept of session expiration.
//  You'd likely use a timer and a mechanism to destroy the session if it's inactive.
//  For example:
//  setcookie("session_id", session_id(), time() + 3600); // Set cookie for 1 hour (adjust as needed)
//  You'd then need code to check if the session has been idle for a specific time.

?>
