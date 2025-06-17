

<?php

session_start(); // Start the session

// --- Functions for Cart Management ---

/**
 * Add item to cart
 *
 * @param int $productId  The ID of the product to add
 * @param int $quantity   The quantity of the product to add
 * @return void
 */
function add_to_cart(int $productId, int $quantity = 1) {
  if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
  }

  // Check if the product is already in the cart
  if (isset($_SESSION['cart'][$productId])) {
    $_SESSION['cart'][$productId]['quantity'] += $quantity;
  } else {
    // Product not in cart, add it with default quantity of 1
    $_SESSION['cart'][$productId] = ['quantity' => $quantity];
  }
}

/**
 * Update quantity of item in cart
 *
 * @param int $productId  The ID of the product to update
 * @param int $quantity   The new quantity of the product
 * @return void
 */
function update_cart_quantity(int $productId, int $quantity) {
    if (isset($_SESSION['cart'][$productId])) {
        $_SESSION['cart'][$productId]['quantity'] = $quantity;
    }
}


/**
 * Remove item from cart
 *
 * @param int $productId The ID of the product to remove
 * @return void
 */
function remove_from_cart(int $productId) {
  if (isset($_SESSION['cart'][$productId])) {
    unset($_SESSION['cart'][$productId]);
  }
}

/**
 * Get cart contents
 *
 * @return array  An array representing the cart contents
 */
function get_cart_contents() {
  return $_SESSION['cart'] ?? []; // Return empty array if cart is not set
}


// --- Example Usage (Illustrative) ---

// Example:  Adding an item to the cart
if (isset($_POST['add_to_cart'])) {
    $productId = intval($_POST['product_id']);
    $quantity = intval($_POST['quantity']) ?? 1; // Default quantity is 1
    add_to_cart($productId, $quantity);
}

// Example: Updating the quantity of an item
if (isset($_POST['update_cart'])) {
    $productId = intval($_POST['product_id']);
    $newQuantity = intval($_POST['quantity']) ?? 1;
    update_cart_quantity($productId, $newQuantity);
}

// Example: Removing an item from the cart
if (isset($_GET['remove_from_cart'])) {
    $productId = intval($_GET['product_id']);
    remove_from_cart($productId);
}



// Display the Cart Contents (for demonstration)
$cart_items = get_cart_contents();

echo "<h2>Your Cart</h2>";

if (empty($cart_items)) {
  echo "<p>Your cart is empty.</p>";
} else {
  echo "<ul>";
  foreach ($cart_items as $productId => $item) {
    echo "<li>Product ID: " . $productId . ", Quantity: " . $item['quantity'] . "</li>";
  }
  echo "</ul>";
}


?>
