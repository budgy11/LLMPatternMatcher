

<?php
session_start();

// --- Cart Management Functions ---

/**
 * Adds an item to the cart.
 *
 * @param int $product_id  The ID of the product to add.
 * @param int $quantity    The quantity of the product to add. Defaults to 1.
 */
function addToCart($product_id, $quantity = 1) {
  // Check if the cart exists, initialize if not
  if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
  }

  // Check if the product is already in the cart
  if (isset($_SESSION['cart'][$product_id])) {
    // Product exists - increase quantity
    $_SESSION['cart'][$product_id]['quantity'] += $quantity;
  } else {
    // Product doesn't exist - add it to the cart
    $_SESSION['cart'][$product_id] = [
      'quantity' => $quantity,
      'name'     => 'Product Name (Replace with actual name)', // Replace with product name
      'price'    => 10.00,  // Replace with product price
    ];
  }
}

/**
 * Updates the quantity of an item in the cart.
 *
 * @param int $product_id  The ID of the product to update.
 * @param int $quantity   The new quantity of the product.
 */
function updateCartQuantity($product_id, $quantity) {
  if (isset($_SESSION['cart'][$product_id])) {
    $_SESSION['cart'][$product_id]['quantity'] = $quantity;
  }
}

/**
 * Removes an item from the cart.
 *
 * @param int $product_id The ID of the product to remove.
 */
function removeFromCart($product_id) {
  if (isset($_SESSION['cart'][$product_id])) {
    unset($_SESSION['cart'][$product_id]);
  }
}


// --- Example Usage (for demonstration -  replace with your product details and logic) ---

// Example: Add a product to the cart
addToCart(123);  // Add product with ID 123 to the cart

addToCart(456, 2); // Add product with ID 456 to the cart with quantity 2

// Example: Update the quantity of a product in the cart
updateCartQuantity(123, 5); // Update the quantity of product with ID 123 to 5

// Example: Remove a product from the cart
removeFromCart(456);

// --- Displaying the Cart Contents (for demonstration) ---

// Check if the cart is empty
if (empty($_SESSION['cart'])) {
  echo "<p>Your cart is empty.</p>";
} else {
  echo "<h2>Your Cart:</h2>";
  echo "<ul>";
  foreach ($_SESSION['cart'] as $product_id => $product_details) {
    echo "<li>";
    echo "Product ID: " . $product_id . "<br>";
    echo "Quantity: " . $product_details['quantity'] . "<br>";
    echo "Price: $" . $product_details['price'] . "<br>";
    echo "</li>";
  }
  echo "</ul>";
}

?>
