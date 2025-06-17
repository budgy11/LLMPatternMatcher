

<?php

// Initialize session if not already started
if (!session_id()) {
    session_start();
}

// --- Cart Data ---
// This is where you'll store the items in the user's cart.
// In a real application, this would typically be stored in a database.
// For this example, we'll use an associative array.

// --- Helper Functions ---

/**
 * Adds an item to the cart.
 *
 * @param string $productId  The ID of the product to add.
 * @param string $name        The name of the product.
 * @param int    $quantity   The quantity of the product to add (default: 1).
 * @param float  $price      The price of the product.
 */
function addItemToCart(string $productId, string $name, int $quantity = 1, float $price) {
    if (!session_id()) {
        session_start();
    }

    // Check if the product is already in the cart
    if (isset($session['cart'][$productId])) {
        // Increment quantity if already present
        $session['cart'][$productId]['quantity'] += $quantity;
    } else {
        // Add new item to the cart
        $session['cart'][$productId] = [
            'name'     => $name,
            'quantity' => $quantity,
            'price'    => $price
        ];
    }
}

/**
 * Updates the quantity of an item in the cart.
 *
 * @param string $productId  The ID of the product to update.
 * @param int    $quantity  The new quantity.
 */
function updateCartItemQuantity(string $productId, int $quantity) {
    if (!session_id()) {
        session_start();
    }

    if (isset($session['cart'][$productId])) {
        $session['cart'][$productId]['quantity'] = $quantity;
    }
}

/**
 * Removes an item from the cart by product ID.
 *
 * @param string $productId  The ID of the product to remove.
 */
function removeItemFromCart(string $productId) {
    if (!session_id()) {
        session_start();
    }

    if (isset($session['cart'][$productId])) {
        unset($session['cart'][$productId]);
    }
}

/**
 * Returns the cart contents.
 *
 * @return array  An array representing the cart.
 */
function getCartContents() {
    if (!session_id()) {
        session_start();
    }
    return $session['cart'];
}

/**
 * Calculates the total cart value.
 *
 * @return float  The total cart value.
 */
function calculateCartTotal() {
    if (!session_id()) {
        session_start();
    }

    $total = 0.0;
    foreach ($session['cart'] as $item) {
        $total += $item['quantity'] * $item['price'];
    }
    return $total;
}


// --- Example Usage ---

// 1. Add some items to the cart
addItemToCart('product1', 'T-Shirt', 2, 20.00);
addItemToCart('product2', 'Jeans', 1, 50.00);

// 2. Update the quantity of an item
updateCartItemQuantity('product1', 5);

// 3. Display the cart contents
$cart = getCartContents();
echo "<h2>Cart Contents:</h2>";
echo "<pre>";
print_r($cart);
echo "</pre>";

// 4. Calculate and display the total
$total = calculateCartTotal();
echo "<p>Total Cart Value: $" . number_format($total, 2) . "</p>";

// 5. Remove an item
removeItemFromCart('product2');

// 6. Display the updated cart
$cart = getCartContents();
echo "<h2>Cart Contents After Removal:</h2>";
echo "<pre>";
print_r($cart);
echo "</pre>";
?>
