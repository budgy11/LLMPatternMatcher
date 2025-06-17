

<?php

session_start(); // Start the session

// This is a basic example and doesn't handle persistence, database interaction, or validation.
// It's intended to demonstrate the core concepts of a shopping cart session.

// Function to add an item to the cart
function addToCart($product_id, $product_name, $price, $quantity = 1) {
  if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = array();
  }

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
function removeCartItem($product_id) {
  if (isset($_SESSION['cart'][$product_id])) {
    unset($_SESSION['cart'][$product_id]);
  }
}


// --- Example Usage (Simulating Product Actions) ---

// Add a product to the cart
addToCart(1, "Laptop", 1200, 1);
addToCart(2, "Mouse", 25, 2);

// Update the quantity of a product
updateCartQuantity(2, 5);


// --- Displaying the Cart Contents (for demonstration) ---

echo "<h2>Shopping Cart</h2>";

if (empty($_SESSION['cart'])) {
  echo "<p>Your cart is empty.</p>";
} else {
  echo "<ul>";
  foreach ($_SESSION['cart'] as $item_id => $item_details) {
    echo "<li>";
    echo "<strong>" . $item_details['name'] . "</strong> - $" . $item_details['price'] . " x " . $item_details['quantity'] . " = $" . ($item_details['price'] * $item_details['quantity']) . "</li>";
  }
  echo "</ul>";
}

// --- To remove an item from the cart (example) ---
// removeCartItem(2);

//  You would typically retrieve this information from a database and display it in your shopping cart page.
?>
