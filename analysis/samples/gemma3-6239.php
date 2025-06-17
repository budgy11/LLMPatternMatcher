

<?php
session_start(); // Start the PHP session

// --- Cart Management Functions ---

/**
 * Adds an item to the cart.
 *
 * @param int $product_id The ID of the product to add.
 * @param int $quantity  The quantity of the product to add.
 * @return void
 */
function addToCart($product_id, $quantity = 1) {
  if (isset($_SESSION['cart'])) {
    $_SESSION['cart'][] = $product_id; // Add to existing array
  } else {
    $_SESSION['cart'] = [$product_id => $quantity]; // Create a new array
  }
}

/**
 * Removes a product from the cart.
 *
 * @param int $product_id The ID of the product to remove.
 * @return void
 */
function removeFromCart($product_id) {
  if (isset($_SESSION['cart'])) {
    unset($_SESSION['cart'][$product_id]); // Remove from array
    // Optionally, clean up if the cart becomes empty.
    if (empty($_SESSION['cart'])) {
      unset($_SESSION['cart']);
    }
  }
}

/**
 * Gets all items in the cart.
 *
 * @return array An array of product IDs in the cart.
 */
function getCartItems() {
  if (isset($_SESSION['cart'])) {
    return $_SESSION['cart'];
  }
  return []; // Return an empty array if the cart is empty
}

/**
 * Updates the quantity of an item in the cart.
 *
 * @param int $product_id The ID of the product to update.
 * @param int $quantity The new quantity.
 * @return void
 */
function updateCartItemQuantity($product_id, $quantity) {
    if (isset($_SESSION['cart'][$product_id])) {
        $_SESSION['cart'][$product_id] = $quantity;
    }
}


// --- Example Usage (for demonstration) ---

// 1. Adding items to the cart:
$product1Id = 123; // Example product ID
$product2Id = 456;
addToCart($product1Id);
addToCart($product1Id, 2); // Add two of product 1
addToCart($product2Id);


// 2. Displaying the cart contents:
$cartItems = getCartItems();
echo "<h2>Your Shopping Cart:</h2>";
if (empty($cartItems)) {
    echo "<p>Your cart is empty.</p>";
} else {
    echo "<ul>";
    foreach ($cartItems as $product_id => $quantity) {
        // You would ideally fetch product details here based on $product_id
        // For example:
        // $product = getProductDetails($product_id);
        // echo "<li>Product ID: " . $product_id . ", Quantity: " . $quantity . "</li>";
        echo "<li>Product ID: " . $product_id . ", Quantity: " . $quantity . "</li>";
    }
    echo "</ul>";
}


// 3. Removing an item from the cart:
removeFromCart($product1Id);


// 4. Updating the quantity of an item:
updateCartItemQuantity($product2Id, 5);


//Display updated cart:
$cartItems = getCartItems();
echo "<h2>Your Shopping Cart:</h2>";
if (empty($cartItems)) {
    echo "<p>Your cart is empty.</p>";
} else {
    echo "<ul>";
    foreach ($cartItems as $product_id => $quantity) {
        // You would ideally fetch product details here based on $product_id
        // For example:
        // $product = getProductDetails($product_id);
        // echo "<li>Product ID: " . $product_id . ", Quantity: " . $quantity . "</li>";
        echo "<li>Product ID: " . $product_id . ", Quantity: " . $quantity . "</li>";
    }
    echo "</ul>";
}



?>
