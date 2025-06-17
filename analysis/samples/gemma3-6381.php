

<?php

// Start a session (if not already started)
session_start();

// ----------------------------------------------------------------------
// Cart Management Functions
// ----------------------------------------------------------------------

/**
 * Adds an item to the cart.
 *
 * @param int $product_id The ID of the product to add.
 * @param int $quantity The quantity of the product to add.
 * @return void
 */
function addToCart($product_id, $quantity = 1)
{
    // Check if the cart already exists in session
    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = [];
    }

    // Check if the product is already in the cart
    if (isset($_SESSION['cart'][$product_id])) {
        // Product already in cart, update quantity
        $_SESSION['cart'][$product_id] += $quantity;
    } else {
        // Product not in cart, add it
        $_SESSION['cart'][$product_id] = $quantity;
    }
}

/**
 * Updates the quantity of a product in the cart.
 *
 * @param int $product_id The ID of the product to update.
 * @param int $quantity The new quantity of the product.
 * @return void
 */
function updateCartQuantity($product_id, $quantity)
{
    if (isset($_SESSION['cart'][$product_id])) {
        $_SESSION['cart'][$product_id] = $quantity;
    } else {
        // Handle the case where the product doesn't exist in the cart
        // This might involve displaying an error message or doing nothing
        // depending on your application's requirements.
        error_log("Product {$product_id} not found in cart."); // Log the error
    }
}

/**
 * Removes a product from the cart.
 *
 * @param int $product_id The ID of the product to remove.
 * @return void
 */
function removeFromCart($product_id)
{
    if (isset($_SESSION['cart'][$product_id])) {
        unset($_SESSION['cart'][$product_id]);
    }
}

/**
 * Clears the entire cart.
 *
 * @return void
 */
function clearCart()
{
    unset($_SESSION['cart']);
}

/**
 * Gets the cart contents.
 *
 * @return array  An array representing the cart contents.
 */
function getCart()
{
    return $_SESSION['cart'] ?? []; // Use null coalesce operator for default empty array.
}


// ----------------------------------------------------------------------
// Example Usage (Illustrative)
// ----------------------------------------------------------------------

// Add some items to the cart
addToCart(101, 2); // Add 2 of product ID 101
addToCart(102, 1); // Add 1 of product ID 102
addToCart(101, 3); // Add 3 more of product ID 101

// Display the cart contents
echo "<h2>Your Shopping Cart</h2>";
echo "<ul>";
$cart_items = getCart();
foreach ($cart_items as $product_id => $quantity) {
    echo "<li>Product ID: " . $product_id . ", Quantity: " . $quantity . "</li>";
}
echo "</ul>";


// Update quantity (example)
updateCartQuantity(101, 5);
echo "<br>After updating quantity of product 101 to 5: ";
$cart_items = getCart();
echo "<ul>";
foreach ($cart_items as $product_id => $quantity) {
    echo "<li>Product ID: " . $product_id . ", Quantity: " . $quantity . "</li>";
}
echo "</ul>";



// Remove a product
removeFromCart(102);
echo "<br>After removing product 102: ";
$cart_items = getCart();
echo "<ul>";
foreach ($cart_items as $product_id => $quantity) {
    echo "<li>Product ID: " . $product_id . ", Quantity: " . $quantity . "</li>";
}
echo "</ul>";

// Clear the cart
//clearCart();
//echo "<br>After clearing the cart: ";
//$cart_items = getCart();
//echo "<ul>";
//foreach ($cart_items as $product_id => $quantity) {
//    echo "<li>Product ID: " . $product_id . ", Quantity: " . $quantity . "</li>";
//}
//echo "</ul>";


?>
