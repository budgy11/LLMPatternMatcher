
</body>
</html>


<?php
session_start();

// --- Cart Functions ---

/**
 * Adds an item to the cart.
 *
 * @param int $product_id The ID of the product to add.
 * @param int $quantity The quantity of the product to add.
 * @return void
 */
function addToCart($product_id, $quantity = 1) {
  if (isset($_SESSION['cart'][$product_id])) {
    // Item already in cart, increase quantity
    $_SESSION['cart'][$product_id]['quantity'] += $quantity;
  } else {
    // Item not in cart, add it
    $_SESSION['cart'][$product_id] = [
      'quantity' => $quantity,
      'price' => getProductPrice($product_id) //Get product price.  Improve this if necessary.
    ];
  }
}


/**
 * Retrieves the quantity of a product in the cart.
 *
 * @param int $product_id The ID of the product.
 * @return int The quantity, or 0 if not found.
 */
function getCartQuantity($product_id) {
    if (isset($_SESSION['cart'][$product_id])) {
        return $_SESSION['cart'][$product_id]['quantity'];
    }
    return 0;
}


/**
 * Removes a product from the cart.
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
 * Clears the entire cart.
 *
 * @return void
 */
function clearCart() {
  unset($_SESSION['cart']);
}



/**
 * Gets the price of a product (Placeholder - Implement your product data retrieval)
 *
 * @param int $product_id The ID of the product.
 * @return float The product price.
 */
function getProductPrice($product_id) {
    // **IMPORTANT:** Replace this with your actual product data retrieval logic.
    // This is just a placeholder.
    // For example, you might fetch this from a database or a product array.

    // Example using a static product array (replace with your actual data)
    $products = [
        1 => ['name' => 'Laptop', 'price' => 1200.00],
        2 => ['name' => 'Mouse', 'price' => 25.00],
        3 => ['name' => 'Keyboard', 'price' => 75.00],
    ];

    if (isset($products[$product_id])) {
        return $products[$product_id]['price'];
    } else {
        return 0.00; // Or handle the error appropriately
    }
}



// --- Example Usage ---

// Add a product to the cart
addToCart(1); // Add 1 Laptop
addToCart(2, 2); // Add 2 Mice
addToCart(1, 3); // Add 3 laptops (updates the quantity of the existing product)

// Display the cart contents
echo "<h2>Shopping Cart</h2>";
echo "<ul>";

foreach ($_SESSION['cart'] as $product_id => $item) {
    $quantity = getCartQuantity($product_id);
    $product_name = "Product " . $product_id; // Replace with actual product name
    $price = getProductPrice($product_id);
    $total_price = $price * $quantity;

    echo "<li>" . $product_name . " - Quantity: " . $quantity . " - Price: $" . $price . " - Total: $" . $total_price . "</li>";
}

echo "</ul>";


// Remove a product from the cart
removeFromCart(2);

// Display the cart contents after removing a product
echo "<br>";
echo "<h2>Shopping Cart (After Removal)</h2>";
echo "<ul>";

foreach ($_SESSION['cart'] as $product_id => $item) {
    $quantity = getCartQuantity($product_id);
    $product_name = "Product " . $product_id; // Replace with actual product name
    $price = getProductPrice($product_id);
    $total_price = $price * $quantity;

    echo "<li>" . $product_name . " - Quantity: " . $quantity . " - Price: $" . $price . " - Total: $" . $total_price . "</li>";
}

echo "</ul>";


// Clear the cart
//clearCart();

// Display the cart contents after clearing the cart
//echo "<br>";
//echo "<h2>Shopping Cart (After Clearing)</h2>";
//echo "<ul>";
//foreach ($_SESSION['cart'] as $product_id => $item) {
//    $quantity = getCartQuantity($product_id);
//    $product_name = "Product " . $product_id; // Replace with actual product name
//    $price = getProductPrice($product_id);
//    $total_price = $price * $quantity;
//
//    echo "<li>" . $product_name . " - Quantity: " . $quantity . " - Price: $" . $price . " - Total: $" . $total_price . "</li>";
//}
//echo "</ul>";
?>
