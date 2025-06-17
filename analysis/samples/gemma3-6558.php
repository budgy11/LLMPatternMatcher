

<?php

// Start session management if it's not already
session_start();

// Check if the cart is initialized.  If not, initialize it as an array.
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

// -----------------------------------------------------------------
//  Example Functions (You'll likely need to adapt these to your needs)
// -----------------------------------------------------------------

/**
 * Adds an item to the cart.
 *
 * @param int $product_id The ID of the product being added.
 * @param int $quantity  The quantity of the product to add.
 * @return void
 */
function addToCart($product_id, $quantity = 1) {
    if (!isset($_SESSION['cart'][$product_id])) {
        // If the product isn't already in the cart, initialize its count to 1
        $_SESSION['cart'][$product_id] = $quantity;
    } else {
        // If the product is already in the cart, increment the quantity
        $_SESSION['cart'][$product_id] += $quantity;
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
 * Gets the contents of the cart.
 *
 * @return array  An array representing the cart contents (product_id => quantity).
 */
function getCart() {
    return $_SESSION['cart'];
}


// -----------------------------------------------------------------
//  Example Usage (Demonstration - You'll integrate this into your application)
// -----------------------------------------------------------------

// Example: Add a product to the cart
addToCart(123);  // Adds one of product ID 123 to the cart
addToCart(456, 3); // Adds 3 of product ID 456 to the cart
addToCart(123, 2); // Adds 2 of product ID 123 to the cart

// Display the contents of the cart
echo "<h2>Your Cart:</h2>";
echo "<ul>";
$cart_items = getCart();
foreach ($cart_items as $product_id => $quantity) {
    //  You'd likely fetch the product details here based on $product_id
    //  e.g., $product = getProductDetails($product_id);
    echo "<li>Product ID: " . $product_id . ", Quantity: " . $quantity . "</li>";
}
echo "</ul>";

// Example: Remove a product from the cart
// removeFromCart(456);

// Display the updated cart
// echo "<h2>Your Cart:</h2>";
// $cart_items = getCart();
// echo "<ul>";
// foreach ($cart_items as $product_id => $quantity) {
//     echo "<li>Product ID: " . $product_id . ", Quantity: " . $quantity . "</li>";
// }
// echo "</ul>";

?>
