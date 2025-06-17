

<?php

// Start a session to store cart data
session_start();

// Initialize the cart session if it doesn't exist
if (!isset($_SESSION['cart'])) {
  $_SESSION['cart'] = array();
}

// Function to add an item to the cart
function addToCart($product_id, $product_name, $price, $quantity = 1) {
  // Check if the product is already in the cart
  if (isset($_SESSION['cart'][$product_id])) {
    // Increment quantity if it exists
    $_SESSION['cart'][$product_id]['quantity'] += $quantity;
  } else {
    // Add the product to the cart
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


// Example usage:  These are just examples - you would typically get this data from
// a form submission or other user input.

// Add some items to the cart
addToCart(1, 'T-Shirt', 20, 2);
addToCart(2, 'Jeans', 50, 1);
addToCart(1, 'T-Shirt', 20, 3); // Add more of the existing item


// Display the cart contents
echo "<h2>Your Cart</h2>";
if (empty($_SESSION['cart'])) {
  echo "<p>Your cart is empty.</p>";
} else {
  echo "<ul>";
  foreach ($_SESSION['cart'] as $product_id => $product_data) {
    echo "<li>";
    echo "<strong>" . $product_data['name'] . "</strong> - $" . $product_data['price'] . " x " . $product_data['quantity'] . " = $" . ($product_data['price'] * $product_data['quantity']) . "</li>";
  }
  echo "</ul>";
}

// Example: Remove an item
//removeFromCart(2);

// Example: Update Quantity
// updateQuantity(1, 5); // Change the quantity of T-Shirt to 5
//echo "<h2>Your Cart (After Update)</h2>";
//displayCart();

?>
