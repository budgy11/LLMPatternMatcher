

<?php

// Start a session (if it doesn't exist)
session_start();

// Function to add an item to the cart
function addToCart($product_id, $product_name, $price, $quantity) {
  // Check if the cart already exists in the session
  if (isset($_SESSION['cart']) === false) {
    // If not, initialize it as an empty array
    $_SESSION['cart'] = array();
  }

  // Add the item to the cart
  $_SESSION['cart'][] = array(
    'product_id' => $product_id,
    'product_name' => $product_name,
    'price' => $price,
    'quantity' => $quantity
  );
}

// Function to update the quantity of an item in the cart
function updateQuantity($product_id, $new_quantity) {
  if (isset($_SESSION['cart']) === false) {
    return false; // Cart doesn't exist
  }

  // Find the item in the cart
  foreach ($_SESSION['cart'] as $key => $item) {
    if ($item['product_id'] == $product_id) {
      $_SESSION['cart'][$key]['quantity'] = $new_quantity;
      return true;
    }
  }
  return false; // Item not found
}

// Function to remove an item from the cart
function removeFromCart($product_id) {
    if (isset($_SESSION['cart']) === false) {
        return false; // Cart doesn't exist
    }

    // Iterate through the cart and remove the item
    foreach ($_SESSION['cart'] as $key => $item) {
        if ($item['product_id'] == $product_id) {
            unset($_SESSION['cart'][$key]);
            return true;
        }
    }
    return false; // Item not found
}

// Function to get the cart contents
function getCartContents() {
    return $_SESSION['cart'];
}


// --- Example Usage (Simulating a product/request) ---

// Let's say we have a product with ID 1, name "Shirt", and price 20
addToCart(1, "Shirt", 20, 2);

// Let's say another product with ID 2, name "Pants", and price 30
addToCart(2, "Pants", 30, 1);

// Update the quantity of the Shirt to 3
updateQuantity(1, 3);

// Get the cart contents
$cart = getCartContents();

// Display the cart contents
echo "<h2>Your Shopping Cart</h2>";
if (empty($cart)) {
    echo "<p>Your cart is empty.</p>";
} else {
    echo "<ul>";
    foreach ($cart as $item) {
        echo "<li>" . $item['product_name'] . " - Price: $" . $item['price'] . " - Quantity: " . $item['quantity'] . "</li>";
    }
    echo "</ul>";
}

// Example: Remove a product
// removeFromCart(2);

// Get cart contents again to see the updated cart
$cart = getCartContents();

echo "<h2>Your Shopping Cart (Updated)</h2>";
if (empty($cart)) {
    echo "<p>Your cart is empty.</p>";
} else {
    echo "<ul>";
    foreach ($cart as $item) {
        echo "<li>" . $item['product_name'] . " - Price: $" . $item['price'] . " - Quantity: " . $item['quantity'] . "</li>";
    }
    echo "</ul>";
}
?>
