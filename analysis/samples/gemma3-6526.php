

<?php
session_start();

// Initialize the cart if it doesn't exist
if (!isset($_SESSION['cart'])) {
  $_SESSION['cart'] = [];
}

// --- Helper Functions ---

/**
 * Adds an item to the cart.
 *
 * @param int $product_id The ID of the product to add.
 * @param int $quantity  The quantity of the product to add.
 * @return void
 */
function addToCart($product_id, $quantity = 1) {
  if (isset($_SESSION['cart'][$product_id])) {
    // Item already exists, update the quantity
    $_SESSION['cart'][$product_id]['quantity'] += $quantity;
  } else {
    // Item doesn't exist, add it to the cart
    $_SESSION['cart'][$product_id] = [
      'quantity' => $quantity,
      'name' => 'Product Name' // Replace with actual product name
    ];
  }
}

/**
 * Removes an item from the cart.
 *
 * @param int $product_id The ID of the product to remove.
 * @return void
 */
function removeFromCart($product_id) {
  if (isset($_SESSION['cart'][$product_id])) {
    unset($_SESSION['cart'][$product_id]);
  }
}

/**
 * Gets all items in the cart.
 *
 * @return array  An array representing the cart contents.
 */
function getCart() {
  return $_SESSION['cart'];
}


// --- Example Usage (Simulating a request) ---

// 1.  Adding items to the cart (simulated)
//   This is just for demonstration.  In a real application, this would be
//   triggered by a user clicking an "Add to Cart" button.

addToCart(101, 2); // Add 2 of product ID 101
addToCart(102, 1);  // Add 1 of product ID 102
addToCart(101, 1); // Add 1 of product ID 101 (updating quantity)


// 2.  Displaying the cart contents
echo "<h2>Your Shopping Cart</h2>";

if (empty($_SESSION['cart'])) {
  echo "<p>Your cart is empty.</p>";
} else {
  echo "<ul>";
  foreach ($_SESSION['cart'] as $product_id => $item) {
    echo "<li>";
    echo "Product ID: " . $product_id . "<br>";
    echo "Quantity: " . $item['quantity'] . "<br>";
    echo "Product Name: " . $item['name'] . "<br>"; // Replace with actual product name
    echo "</li>";
  }
  echo "</ul>";
}


// 3.  Removing an item from the cart (simulated)
//   Let's remove product 102
// removeFromCart(102);

// To test the removal, uncomment the following line:
// echo "<br><br>After removing product 102:<br>";
// print_r($_SESSION['cart']);
?>
