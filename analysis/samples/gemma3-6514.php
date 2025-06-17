

<?php

// Include the session class (if not already included)
// You'll likely have this in your base PHP file.
session_start();

// --------------------------------------------------
//  Session Management Functions
// --------------------------------------------------

/**
 * Adds an item to the cart session.
 *
 * @param int $product_id The ID of the product to add.
 * @param int $quantity The quantity of the product to add.
 * @return void
 */
function addToCart($product_id, $quantity) {
  if (empty($_SESSION['cart'])) {
    $_SESSION['cart'] = []; // Initialize an empty cart array
  }

  if (isset($_SESSION['cart'][$product_id])) {
    // Item already in the cart, increase quantity
    $_SESSION['cart'][$product_id]['quantity'] += $quantity;
  } else {
    // Item not in the cart, add it with quantity 1
    $_SESSION['cart'][$product_id] = [
      'quantity' => $quantity,
      'name' => 'Product Name (This is just an example)' // Add product details here
    ];
  }
}

/**
 * Updates the quantity of an item in the cart.
 *
 * @param int $product_id The ID of the product to update.
 * @param int $new_quantity The new quantity for the item.
 * @return void
 */
function updateCartQuantity($product_id, $new_quantity) {
  if (isset($_SESSION['cart'][$product_id])) {
    $_SESSION['cart'][$product_id]['quantity'] = $new_quantity;
  } else {
    // Item not found, you could handle this differently (e.g., add it)
    // Or you could return an error if you want to prevent adding non-existent products.
    // For this example, we'll just do nothing.  Consider logging an error if you want.
    echo "Error: Product ID $product_id not found in cart.";  // Optional error handling
  }
}

/**
 * Removes an item from the cart.
 *
 * @param int $product_id The ID of the product to remove.
 * @return void
 */
function removeCartItem($product_id) {
  if (isset($_SESSION['cart'][$product_id])) {
    unset($_SESSION['cart'][$product_id]);
  }
}

/**
 * Clears the entire cart.
 *
 * @return void
 */
function clearCart() {
  unset($_SESSION['cart']);
}


// --------------------------------------------------
//  Example Usage -  Simulating user actions
// --------------------------------------------------

// Add some items to the cart
addToCart(1, 2);  // Add product with ID 1, quantity 2
addToCart(2, 1);  // Add product with ID 2, quantity 1
addToCart(1, 3); // Add more of product 1


// Update the quantity of product 1 to 5
updateCartQuantity(1, 5);

// Remove product 2 from the cart
removeCartItem(2);

// Display the contents of the cart
echo "<h2>Cart Contents:</h2>";
if (!empty($_SESSION['cart'])) {
  echo "<ul>";
  foreach ($_SESSION['cart'] as $product_id => $product_details) {
    echo "<li>Product ID: " . $product_id . ", Quantity: " . $product_details['quantity'] . "</li>";
  }
  echo "</ul>";
} else {
  echo "<p>Your cart is empty.</p>";
}


// Clear the cart
// clearCart();
// echo "Cart cleared.";
?>
