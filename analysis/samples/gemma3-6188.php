

<?php
session_start();

// --- Cart Functions ---

/**
 * Adds an item to the cart.
 *
 * @param int $product_id The ID of the product to add.
 * @param int $quantity The quantity of the product to add.  Defaults to 1.
 * @return void
 */
function addToCart($product_id, $quantity = 1) {
  // Check if the cart already exists.  If not, create it.
  if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = array();
  }

  // Check if the product is already in the cart.
  if (isset($_SESSION['cart'][$product_id])) {
    // Increment the quantity if the product exists.
    $_SESSION['cart'][$product_id]['quantity'] += $quantity;
  } else {
    // Add the product to the cart with a quantity of 1.
    $_SESSION['cart'][$product_id] = array('quantity' => $quantity);
  }
}

/**
 * Updates the quantity of an item in the cart.
 *
 * @param int $product_id The ID of the product to update.
 * @param int $quantity The new quantity of the product.
 * @return void
 */
function updateCartItem($product_id, $quantity) {
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
function removeItemFromCart($product_id) {
  if (isset($_SESSION['cart'][$product_id])) {
    unset($_SESSION['cart'][$product_id]);
  }
}

/**
 * Gets the current cart contents.
 *
 * @return array The contents of the cart.
 */
function getCartContents() {
  return $_SESSION['cart'];
}

/**
 * Calculates the total price of the cart.
 *
 * @param array $cart The cart contents.
 * @param array $product_prices An array of product IDs and their prices.  Example: ['product1' => 10, 'product2' => 20].
 * @return float The total price.
 */
function calculateTotal($cart, $product_prices) {
    $total = 0;
    foreach ($cart as $product_id => $item) {
        if (isset($product_prices[$product_id])) {
            $total += $product_prices[$product_id] * $item['quantity'];
        }
    }
    return $total;
}



// --- Example Usage (Simulation) ---

// Simulate some product prices
$product_prices = [
    1 => 10,  // Product ID 1: $10
    2 => 20,  // Product ID 2: $20
    3 => 5,   // Product ID 3: $5
];

// 1. Add some items to the cart
addToCart(1, 2); // Add 2 of Product 1
addToCart(2);     // Add 1 of Product 2
addToCart(3, 3);  // Add 3 of Product 3

// 2. Display the cart contents
echo "<h2>Cart Contents:</h2>";
echo "<ul>";
$cart = getCartContents();
if (empty($cart)) {
    echo "<li>Cart is empty.</li>";
} else {
    foreach ($cart as $product_id => $item) {
        echo "<li>Product ID: " . $product_id . ", Quantity: " . $item['quantity'] . "</li>";
    }
}
echo "</ul>";

// 3. Update the quantity of an item
updateCartItem(1, 3); // Change quantity of Product 1 to 3

// 4. Display the updated cart contents
echo "<br>";
echo "<h2>Updated Cart Contents:</h2>";
echo "<ul>";
$cart = getCartContents();
if (empty($cart)) {
    echo "<li>Cart is empty.</li>";
} else {
    foreach ($cart as $product_id => $item) {
        echo "<li>Product ID: " . $product_id . ", Quantity: " . $item['quantity'] . "</li>";
    }
}
echo "</ul>";

// 5. Calculate the total
$total = calculateTotal($cart, $product_prices);
echo "<br><h2>Total Price: $" . $total . "</h2>";

// 6. Remove an item
removeItemFromCart(2);
echo "<br><h2>Cart Contents after removing Product 2:</h2>";
$cart = getCartContents();
if (empty($cart)) {
    echo "<li>Cart is empty.</li>";
} else {
    foreach ($cart as $product_id => $item) {
        echo "<li>Product ID: " . $product_id . ", Quantity: " . $item['quantity'] . "</li>";
    }
}
?>
