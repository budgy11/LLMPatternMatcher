

<?php

session_start(); // Start the session

// Initialize the cart if it doesn't exist
if (!isset($_SESSION['cart'])) {
  $_SESSION['cart'] = array();
}

// Function to add an item to the cart
function addToCart($product_id, $product_name, $price, $quantity = 1) {
  // Check if the product is already in the cart
  if (isset($_SESSION['cart'][$product_id])) {
    // Increment the quantity
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

// Example Usage (to demonstrate adding items)

// Add a product to the cart
addToCart(1, "Awesome T-Shirt", 20.00, 2);

// Add another product
addToCart(2, "Cool Mug", 10.00, 1);

//  Check the cart contents (for demonstration purposes)
echo "<h2>Cart Contents:</h2>";
if (empty($_SESSION['cart'])) {
  echo "<p>Your cart is empty.</p>";
} else {
  echo "<ul>";
  foreach ($_SESSION['cart'] as $product_id => $product_details) {
    echo "<li>" . $product_details['name'] . " - $" . $product_details['price'] . " x " . $product_details['quantity'] . " = $" . ($product_details['price'] * $product_details['quantity']) . "</li>";
  }
  echo "</ul>";
}

//  Example:  Removing an item (demonstration only - you'd typically handle this via a UI)
//  This is a very basic demonstration.  In a real application, you'd want
//  to handle the UI and potentially error checking more robustly.
if (isset($_GET['remove']) && is_numeric($_GET['remove'])) {
    $remove_id = (int)$_GET['remove'];
    if (isset($_SESSION['cart'][$remove_id])) {
        unset($_SESSION['cart'][$remove_id]);
    }
}


// You would typically render your product listing or shopping cart HTML here
//  and have links that would trigger the addToCart function.


?>
