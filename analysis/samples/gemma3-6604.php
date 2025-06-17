

<?php
session_start();

// --- Functions for Cart Management ---

/**
 * Adds an item to the cart.
 *
 * @param int $product_id The ID of the product to add.
 * @param int $quantity The quantity of the product to add.
 * @return void
 */
function addToCart($product_id, $quantity) {
  if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
  }

  $_SESSION['cart'][$product_id] = [
    'quantity' => $quantity,
    'name' => "Product " . $product_id, //  Replace with actual product name
    'price' => 10.00, // Replace with actual product price
  ];
}

/**
 * Retrieves the contents of the cart.
 *
 * @return array The cart contents.
 */
function getCart() {
  return $_SESSION['cart'] ?? []; // Use null coalesce operator for safety
}


/**
 * Updates the quantity of an item in the cart.
 *
 * @param int $product_id The ID of the product to update.
 * @param int $quantity The new quantity of the product.
 * @return void
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
 * @return void
 */
function removeCartItem($product_id) {
    if (isset($_SESSION['cart'][$product_id])) {
        unset($_SESSION['cart'][$product_id]);
    }
}

/**
 * Calculates the total cart value.
 *
 * @return float The total cart value.
 */
function calculateCartTotal() {
    $total = 0.00;
    if (isset($_SESSION['cart'])) {
        foreach ($_SESSION['cart'] as $item) {
            $total += $item['quantity'] * $item['price'];
        }
    }
    return $total;
}



// --- Example Usage (Simulating a Cart Interaction) ---

// 1. Add an item to the cart
addToCart(123, 2);  // Add 2 units of product with ID 123
addToCart(456, 1);  // Add 1 unit of product with ID 456


// 2. Update the quantity of an item
updateCartQuantity(123, 5); // Increase the quantity of product 123 to 5

// 3.  Retrieve the cart contents
$cart = getCart();
echo "<h2>Cart Contents:</h2>";
if (empty($cart)) {
  echo "<p>Your cart is empty.</p>";
} else {
  echo "<ul>";
  foreach ($cart as $product_id => $item) {
    echo "<li>Product ID: " . $product_id . ", Quantity: " . $item['quantity'] . ", Price: " . $item['price'] . "</li>";
  }
  echo "</ul>";
}

// 4. Calculate and display the total
$total = calculateCartTotal();
echo "<p><strong>Total Cart Value: $" . number_format($total, 2) . "</strong></p>";

// 5. Remove an item from the cart
removeCartItem(456);

// Display cart contents after removing an item
$cart = getCart();
echo "<p><strong>Cart Contents after Removing Product 456:</strong></p>";
if (empty($cart)) {
    echo "<p>Your cart is empty.</p>";
} else {
    echo "<ul>";
    foreach ($cart as $product_id => $item) {
        echo "<li>Product ID: " . $product_id . ", Quantity: " . $item['quantity'] . ", Price: " . $item['price'] . "</li>";
    }
    echo "</ul>";
}
?>
