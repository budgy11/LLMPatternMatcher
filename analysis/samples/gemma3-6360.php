

<?php
session_start();

// Function to add an item to the cart
function addToCart($product_id, $quantity, $product_name = 'Unknown Product') {
  if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
  }

  // Check if the product is already in the cart
  if (isset($_SESSION['cart'][$product_id])) {
    // Increment the quantity if it exists
    $_SESSION['cart'][$product_id]['quantity'] += $quantity;
  } else {
    // Add the product to the cart
    $_SESSION['cart'][$product_id] = [
      'quantity' => $quantity,
      'name' => $product_name // Optional: Add product name for display
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
function updateCartQuantity($product_id, $new_quantity) {
  if (isset($_SESSION['cart'][$product_id])) {
    $_SESSION['cart'][$product_id]['quantity'] = $new_quantity;
  }
}


// --- Example Usage (Illustrative) ---

// Add a product to the cart
addToCart(123, 2, 'Awesome T-Shirt'); // product_id = 123, quantity = 2, product_name = 'Awesome T-Shirt'
addToCart(456, 1, 'Cool Mug');       // Add another product

// Display the contents of the cart
echo "<h2>Your Cart:</h2>";
if (empty($_SESSION['cart'])) {
  echo "<p>Your cart is empty.</p>";
} else {
  echo "<ul>";
  foreach ($_SESSION['cart'] as $product_id => $product_details) {
    echo "<li>";
    echo "Product: " . $product_details['name'] . ", Quantity: " . $product_details['quantity'];
    echo "<button onclick='removeFromCart(" . $product_id . ")'>Remove</button>"; // Link to remove
    echo "</li>";
  }
  echo "</ul>";
}

// Update the quantity of an item
updateCartQuantity(123, 5);

// Remove an item
// removeFromCart(456);

// Display the cart again after the update
echo "<h2>Your Cart (Updated):</h2>";
if (empty($_SESSION['cart'])) {
    echo "<p>Your cart is empty.</p>";
} else {
  echo "<ul>";
  foreach ($_SESSION['cart'] as $product_id => $product_details) {
    echo "<li>";
    echo "Product: " . $product_details['name'] . ", Quantity: " . $product_details['quantity'];
    echo "<button onclick='removeFromCart(" . $product_id . ")'>Remove</button>"; // Link to remove
    echo "</li>";
  }
  echo "</ul>";
}


?>
