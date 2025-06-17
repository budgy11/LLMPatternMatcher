

<?php
session_start();

// --- Cart Functions ---

/**
 * Adds an item to the cart.
 *
 * @param string $product_id The ID of the product to add.
 * @param int $quantity The quantity of the product to add. Defaults to 1.
 * @return void
 */
function addToCart($product_id, $quantity = 1) {
  if (isset($_SESSION['cart'])) {
    $_SESSION['cart'][$product_id] = $_SESSION['cart'][$product_id] ?? 0; // Initialize if not already present
    $_SESSION['cart'][$product_id] = $_SESSION['cart'][$product_id] + $quantity;
  } else {
    $_SESSION['cart'][$product_id] = $quantity;
  }
}

/**
 * Removes a product from the cart.
 *
 * @param string $product_id The ID of the product to remove.
 * @return void
 */
function removeFromCart($product_id) {
  if (isset($_SESSION['cart'][$product_id])) {
    unset($_SESSION['cart'][$product_id]);
  }
}

/**
 * Updates the quantity of a product in the cart.
 *
 * @param string $product_id The ID of the product to update.
 * @param int $quantity The new quantity.
 * @return void
 */
function updateQuantity($product_id, $quantity) {
    if (isset($_SESSION['cart'][$product_id])) {
        $_SESSION['cart'][$product_id] = $quantity;
    }
}

/**
 * Gets the cart contents.
 *
 * @return array The cart contents.
 */
function getCartContents() {
  return isset($_SESSION['cart']) ? $_SESSION['cart'] : [];
}


/**
 * Clears the cart
 *
 * @return void
 */
function clearCart() {
    unset($_SESSION['cart']);
}

// --- Example Usage (Simulating adding to cart) ---

// Let's assume we have these product IDs: "product1", "product2", "product3"

// Add product1 to the cart with a quantity of 2
addToCart("product1", 2);

// Add product2 to the cart with a quantity of 1
addToCart("product2", 1);

// Remove product1 from the cart
removeFromCart("product1");

// Update the quantity of product2 to 3
updateQuantity("product2", 3);


// --- Displaying the Cart Contents (for demonstration) ---

echo "<h2>Your Shopping Cart</h2>";

if (empty(getCartContents())) {
  echo "<p>Your cart is empty.</p>";
} else {
  echo "<ul>";
  foreach (getCartContents() as $product_id => $quantity) {
    //  Ideally, you would fetch product details based on $product_id here
    //  This is just a placeholder.  Replace with your actual product retrieval logic.
    $product_name = "Product " . $product_id;

    echo "<li>$product_name (Quantity: $quantity)</li>";
  }
  echo "</ul>";
}


// --- Session Management (Important) ---
// In a real application, you would typically handle session management through
// a server-side technology like a web server with session support.  This example
// uses PHP's built-in session management.

?>
