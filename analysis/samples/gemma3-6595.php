

<?php

// Start a session if it doesn't exist
session_start();

// Initialize the cart as an empty array if it doesn't exist
if (!isset($_SESSION['cart'])) {
  $_SESSION['cart'] = array();
}

// Function to add an item to the cart
function addToCart($product_id, $product_name, $price, $quantity = 1) {
  // Check if the product is already in the cart
  if (isset($_SESSION['cart'][$product_id])) {
    // If it is, increment the quantity
    $_SESSION['cart'][$product_id]['quantity'] += $quantity;
  } else {
    // If it's not, add the product to the cart with an initial quantity of 1
    $_SESSION['cart'][$product_id] = array(
      'name' => $product_name,
      'price' => $price,
      'quantity' => $quantity
    );
  }
}


// Example usage: Add a product to the cart
// Assume product IDs are 1, 2, 3...
addToCart(1, "T-Shirt", 20.00, 2);
addToCart(2, "Jeans", 50.00, 1);


// Function to remove an item from the cart
function removeFromCart($product_id) {
  if (isset($_SESSION['cart'][$product_id])) {
    unset($_SESSION['cart'][$product_id]);
  }
}


// Example of removing a product
//removeFromCart(1);  // Remove the T-Shirt

// Function to update the quantity of an item in the cart
function updateQuantity($product_id, $new_quantity) {
  if (isset($_SESSION['cart'][$product_id])) {
    $_SESSION['cart'][$product_id]['quantity'] = $new_quantity;
  }
}


// Example of updating the quantity
//updateQuantity(2, 3); // Change the Jeans quantity to 3

// Function to display the cart contents
function displayCart() {
  echo "<h2>Shopping Cart</h2>";
  echo "<table border='1'>";
  echo "<tr><th>Product Name</th><th>Price</th><th>Quantity</th><th>Total</th></tr>";

  $total = 0;
  foreach ($_SESSION['cart'] as $product_id => $product_details) {
    $product_name = $product_details['name'];
    $price = $product_details['price'];
    $quantity = $product_details['quantity'];
    $total_item = $price * $quantity;
    $total += $total_item;

    echo "<tr>";
    echo "<td>" . $product_name . "</td>";
    echo "<td>$" . number_format($price, 2) . "</td>";
    echo "<td>" . $quantity . "</td>";
    echo "<td>$" . number_format($total_item, 2) . "</td>";
    echo "</tr>";
  }

  echo "</table><br>";
  echo "<strong>Total: $" . number_format($total, 2) . "</strong>";
}

// Call the function to display the cart
displayCart();

?>
