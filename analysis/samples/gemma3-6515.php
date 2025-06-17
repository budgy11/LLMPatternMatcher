

<?php
session_start(); // Start the session

// Function to add an item to the cart
function addToCart($product_id, $product_name, $price, $quantity = 1) {
  // Check if the cart exists in the session
  if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
  }

  // Add the product to the cart array
  if (isset($_SESSION['cart'][$product_id])) {
    $_SESSION['cart'][$product_id]['quantity'] += $quantity;
  } else {
    $_SESSION['cart'][$product_id] = [
      'name' => $product_name,
      'price' => $price,
      'quantity' => $quantity
    ];
  }
}

// Function to update the quantity of an item in the cart
function updateQuantity($product_id, $quantity) {
  // Check if the cart exists in the session
  if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
  }

  // Check if the product is in the cart
  if (isset($_SESSION['cart'][$product_id])) {
    // Update the quantity
    $_SESSION['cart'][$product_id]['quantity'] = $quantity;
  } else {
    // Product not found, handle error or don't update
    // You could add a logging mechanism here for debugging.
    // For example: error_log("Product $product_id not found in cart.");
  }
}

// Function to remove an item from the cart
function removeCartItem($product_id) {
  // Check if the cart exists in the session
  if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
  }

  // Remove the item from the array
  unset($_SESSION['cart'][$product_id]);

  // Optionally, clear the cart if it's empty after removal
  if (empty($_SESSION['cart'])) {
    unset($_SESSION['cart']);
  }
}

// Function to get the cart contents
function getCartContents() {
  return $_SESSION['cart'] ?? []; // Returns cart or empty array if session is not initialized.  Use null coalescing operator.
}


// --- Example Usage ---

// Add a product to the cart
addToCart(1, "Awesome T-Shirt", 20.00, 2); // product_id, name, price, quantity
addToCart(2, "Cool Hat", 15.00, 1);

// Get the cart contents
$cart = getCartContents();

// Print the cart contents
echo "<h2>Your Cart:</h2>";
if (empty($cart)) {
  echo "<p>Your cart is empty.</p>";
} else {
  echo "<ul>";
  foreach ($cart as $product_id => $product_data) {
    echo "<li>" . $product_data['name'] . " - $" . $product_data['price'] . " x " . $product_data['quantity'] . " = $" . ($product_data['price'] * $product_data['quantity']) . "</li>";
  }
  echo "</ul>";
}


// Update the quantity of a product
updateQuantity(1, 3); // Increase quantity of Awesome T-Shirt to 3

// Get the cart contents again to see the updated quantity
$cart = getCartContents();
echo "<h2>Your Cart (Updated):</h2>";
if (empty($cart)) {
    echo "<p>Your cart is empty.</p>";
} else {
    echo "<ul>";
    foreach ($cart as $product_id => $product_data) {
        echo "<li>" . $product_data['name'] . " - $" . $product_data['price'] . " x " . $product_data['quantity'] . " = $" . ($product_data['price'] * $product_data['quantity']) . "</li>";
    }
    echo "</ul>";
}


// Remove an item from the cart
removeCartItem(2); // Remove the Cool Hat
echo "<br>";

// Get the cart contents again to see the updated quantity
$cart = getCartContents();
echo "<h2>Your Cart (After Removal):</h2>";
if (empty($cart)) {
    echo "<p>Your cart is empty.</p>";
} else {
    echo "<ul>";
    foreach ($cart as $product_id => $product_data) {
        echo "<li>" . $product_data['name'] . " - $" . $product_data['price'] . " x " . $product_data['quantity'] . " = $" . ($product_data['price'] * $product_data['quantity']) . "</li>";
    }
    echo "</ul>";
}
?>
