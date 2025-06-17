

<?php

// Initialize the session if it doesn't exist
session_start();

// --- Cart Functions ---

/**
 * Adds an item to the cart.
 *
 * @param string $product_id  The ID of the product to add.
 * @param string $product_name The name of the product (for display in the cart).
 * @param int $quantity     The quantity of the product to add.
 * @param float $price       The price of the product.
 * @return void
 */
function addToCart(string $product_id, string $product_name, int $quantity, float $price) {
    if (empty($_SESSION['cart'])) {
        // Cart is empty, create an empty cart array
        $_SESSION['cart'] = [];
    }

    // Check if the product already exists in the cart
    if (isset($_SESSION['cart'][$product_id])) {
        // Product exists, update the quantity
        $_SESSION['cart'][$product_id]['quantity'] += $quantity;
    } else {
        // Product doesn't exist, add it to the cart
        $_SESSION['cart'][$product_id] = [
            'name' => $product_name,
            'quantity' => $quantity,
            'price' => $price,
            'id' => $product_id //  Keep the product ID for future operations
        ];
    }
}


/**
 * Updates the quantity of an item in the cart.
 *
 * @param string $product_id  The ID of the product to update.
 * @param int $quantity     The new quantity of the product.
 * @return void
 */
function updateCartQuantity(string $product_id, int $quantity) {
    if (isset($_SESSION['cart'][$product_id])) {
        $_SESSION['cart'][$product_id]['quantity'] = $quantity;
    }
    //  Consider handling the case where the product is not found.  You might want
    //  to throw an error, log it, or just return without doing anything.
}


/**
 * Removes an item from the cart.
 *
 * @param string $product_id The ID of the product to remove.
 * @return void
 */
function removeCartItem(string $product_id) {
    if (isset($_SESSION['cart'][$product_id])) {
        unset($_SESSION['cart'][$product_id]);
    }
}



/**
 * Calculates the total cart value.
 *
 * @return float The total price of all items in the cart.
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


/**
 * Retrieves the contents of the cart.
 *
 * @return array The cart array.
 */
function getCartContents() {
    return $_SESSION['cart'] ?? [];  // Use null coalescing operator for empty cart
}


// --- Example Usage (Demonstration) ---

// 1. Add some items to the cart
addToCart('product1', 'T-Shirt', 2, 20.00);
addToCart('product2', 'Jeans', 1, 50.00);

// 2. Update the quantity of a product
updateCartQuantity('product1', 5);  // Increase the quantity of T-Shirt to 5

// 3. Remove an item from the cart
removeCartItem('product2');

// 4. Display the cart contents
$cartItems = getCartContents();
$total = calculateCartTotal();

echo "<h2>Your Cart:</h2>";

if (empty($cartItems)) {
    echo "<p>Your cart is empty.</p>";
} else {
    echo "<ul>";
    foreach ($cartItems as $product_id => $item) {
        echo "<li>" . $item['name'] . " - Quantity: " . $item['quantity'] . " - Price: $" . $item['price'] . "</li>";
    }
    echo "</ul>";
    echo "<p>Total: $" . $total . "</p>";
}

?>
