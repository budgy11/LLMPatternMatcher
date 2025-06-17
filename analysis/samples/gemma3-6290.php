

<?php

// Start a session (if not already started)
session_start();

// Initialize the cart as an empty array if it doesn't exist
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = array();
}

// ---  Functions for Cart Operations ---

/**
 * Adds an item to the cart.
 *
 * @param int $product_id The ID of the product to add.
 * @param string $product_name The name of the product.
 * @param int $quantity The quantity of the product to add.
 * @param float $price The price per unit of the product.
 */
function addToCart(int $product_id, string $product_name, int $quantity, float $price) {
    // Check if the product is already in the cart
    if (isset($_SESSION['cart'][$product_id])) {
        // Product already exists, update the quantity
        $_SESSION['cart'][$product_id]['quantity'] += $quantity;
    } else {
        // Product doesn't exist, add it to the cart
        $_SESSION['cart'][$product_id] = array(
            'name' => $product_name,
            'quantity' => $quantity,
            'price' => $price
        );
    }
}


/**
 * Updates the quantity of an item in the cart.
 *
 * @param int $product_id The ID of the product to update.
 * @param int $new_quantity The new quantity of the product.
 */
function updateQuantity(int $product_id, int $new_quantity) {
    if (isset($_SESSION['cart'][$product_id])) {
        $_SESSION['cart'][$product_id]['quantity'] = $new_quantity;
    }
}


/**
 * Removes an item from the cart.
 *
 * @param int $product_id The ID of the product to remove.
 */
function removeFromCart(int $product_id) {
    if (isset($_SESSION['cart'][$product_id])) {
        unset($_SESSION['cart'][$product_id]);
    }
}

/**
 * Gets the cart contents.
 *
 * @return array The cart contents.
 */
function getCartContents() {
    return $_SESSION['cart'];
}


// --- Example Usage  ---

// 1. Adding items to the cart
addToCart(1, "T-Shirt", 2, 20.00);
addToCart(2, "Jeans", 1, 50.00);

// 2. Updating the quantity of an item
updateQuantity(1, 5); // Increase the quantity of T-Shirt to 5

// 3. Removing an item from the cart
removeFromCart(2); // Remove Jeans

// 4. Getting the current cart contents
$cart = getCartContents();

// Print the cart contents to the browser
echo "<h2>Your Shopping Cart:</h2>";
if (empty($cart)) {
    echo "<p>Your cart is empty.</p>";
} else {
    echo "<ul>";
    foreach ($cart as $product_id => $product_data) {
        echo "<li>";
        echo "<strong>Product Name:</strong> " . $product_data['name'] . "<br>";
        echo "<strong>Quantity:</strong> " . $product_data['quantity'] . "<br>";
        echo "<strong>Price per unit:</strong> " . $product_data['price'] . "<br>";
        echo "<strong>Total Price for this item:</strong> " . $product_data['quantity'] * $product_data['price'] . "<br>";
        echo "</li>";
    }
    echo "</ul>";
}

?>
