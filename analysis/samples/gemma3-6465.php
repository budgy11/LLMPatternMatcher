

<?php
session_start();

// --- Cart Functions ---

/**
 * Adds an item to the cart.
 *
 * @param string $product_id The ID of the product to add.
 * @param string $product_name The name of the product.
 * @param int $quantity The quantity of the product to add.  Defaults to 1.
 * @param float $price The price of a single unit of the product.
 *
 * @return bool True if the product was added, false otherwise (e.g., invalid quantity).
 */
function addToCart(string $product_id, string $product_name, int $quantity = 1, float $price) {
    // Validate quantity -  important to prevent issues
    if ($quantity <= 0) {
        return false; // or you could log an error
    }

    // If cart doesn't exist, initialize it.
    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = [];
    }

    // Check if the product is already in the cart
    if (isset($_SESSION['cart'][$product_id])) {
        // Product already exists, update quantity
        $_SESSION['cart'][$product_id]['quantity'] += $quantity;
    } else {
        // Product not in cart, add it.  Store other information too.
        $_SESSION['cart'][$product_id] = [
            'name' => $product_name,
            'quantity' => $quantity,
            'price' => $price
        ];
    }

    return true;
}

/**
 * Retrieves the items in the cart.
 *
 * @return array An array containing the items in the cart.  Empty if the cart is empty.
 */
function getCartItems() {
    return $_SESSION['cart'] ?? []; // Use null coalesce operator for empty cart check.
}

/**
 * Updates the quantity of a product in the cart.
 *
 * @param string $product_id The ID of the product to update.
 * @param int $new_quantity The new quantity for the product.
 * @return bool True if the quantity was updated, false otherwise (e.g., invalid quantity).
 */
function updateCartItem(string $product_id, int $new_quantity) {
    if ($new_quantity <= 0) {
        return false; // Or log an error
    }

    if (isset($_SESSION['cart'][$product_id])) {
        $_SESSION['cart'][$product_id]['quantity'] = $new_quantity;
        return true;
    } else {
        return false; // Product not in cart
    }
}

/**
 * Removes a product from the cart.
 *
 * @param string $product_id The ID of the product to remove.
 * @return bool True if the product was removed, false otherwise (e.g., product not in cart).
 */
function removeCartItem(string $product_id) {
    if (isset($_SESSION['cart'][$product_id])) {
        unset($_SESSION['cart'][$product_id]);
        return true;
    } else {
        return false; // Product not in cart
    }
}

/**
 * Calculates the total price of the cart.
 *
 * @return float The total price of the cart.
 */
function calculateCartTotal() {
    $total = 0.0;
    $cart_items = getCartItems();

    foreach ($cart_items as $item) {
        $total += $item['price'] * $item['quantity'];
    }

    return $total;
}

// --- Example Usage ---

// Add an item to the cart
addToCart('product1', 'Awesome T-Shirt', 2, 25.00);
addToCart('product2', 'Cool Mug', 1, 10.00);

// Display cart items
$cart_items = getCartItems();
echo "<h2>Cart Items:</h2>";
if (empty($cart_items)) {
    echo "<p>Your cart is empty.</p>";
} else {
    echo "<ul>";
    foreach ($cart_items as $item) {
        echo "<li>" . $item['name'] . " - Quantity: " . $item['quantity'] . " - Price: $" . $item['price'] . "</li>";
    }
    echo "</ul>";
}

// Calculate and display the total
$total = calculateCartTotal();
echo "<p><strong>Total: $" . $total . "</strong></p>";

// Update quantity of an item
updateCartItem('product1', 5);

// Display updated cart
$cart_items = getCartItems();
echo "<h2>Updated Cart Items:</h2>";
foreach ($cart_items as $item) {
    echo "<li>" . $item['name'] . " - Quantity: " . $item['quantity'] . " - Price: $" . $item['price'] . "</li>";
}

// Remove an item
removeCartItem('product2');

echo "<p>Cart after removing item.</p>";
$cart_items = getCartItems();
foreach ($cart_items as $item) {
    echo "<li>" . $item['name'] . " - Quantity: " . $item['quantity'] . " - Price: $" . $item['price'] . "</li>";
}
?>
