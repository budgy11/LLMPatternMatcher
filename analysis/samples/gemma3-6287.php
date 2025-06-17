

<?php
session_start();

// ------------------------------------
//  Session Management Functions
// ------------------------------------

/**
 * Adds an item to the cart.
 *
 * @param int $product_id The ID of the product to add.
 * @param int $quantity The quantity of the product to add.
 * @return void
 */
function addToCart(int $product_id, int $quantity) {
    if (isset($_SESSION['cart'][$product_id])) {
        // Product already in cart - increment quantity
        $_SESSION['cart'][$product_id]['quantity'] += $quantity;
    } else {
        // Product not in cart - add it with quantity 1
        $_SESSION['cart'][$product_id] = [
            'quantity' => $quantity,
            'price' => getProductPrice($product_id) // Assuming a function to get the price
        ];
    }
}

/**
 * Retrieves the total cart value.
 *
 * @return float The total cart value.
 */
function getCartTotal() {
    $total = 0;
    if (isset($_SESSION['cart'])) {
        foreach ($_SESSION['cart'] as $item) {
            $totalItem = $item['quantity'] * getProductPrice($item['id']); //Use getProductPrice here to get the price
            $total += $totalItem;
        }
    }
    return $total;
}

/**
 * Retrieves the price of a product given its ID.
 *  This is a placeholder - you'll need to implement this function
 *  to fetch product prices from your database or other data source.
 *
 * @param int $product_id The ID of the product.
 * @return float The price of the product.
 */
function getProductPrice(int $product_id) {
    // This is a placeholder - replace with your actual logic
    // For example, fetch from a database or an array of products.
    switch ($product_id) {
        case 1:
            return 10.00;
        case 2:
            return 25.50;
        case 3:
            return 5.75;
        default:
            return 0.00; // Handle unknown product IDs
    }
}


// ------------------------------------
//  Example Usage (Simulated Cart Interactions)
// ------------------------------------

// 1. Add items to the cart
addToCart(1, 2); // Add 2 of product ID 1
addToCart(2, 1); // Add 1 of product ID 2
addToCart(1, 1); // Add 1 of product ID 1

// 2. Display the cart content
if (isset($_SESSION['cart'])) {
    echo "<h2>Your Cart</h2>";
    echo "<ul>";
    foreach ($_SESSION['cart'] as $product_id => $item) {
        echo "<li>Product ID: " . $product_id . ", Quantity: " . $item['quantity'] . ", Price: $" . $item['price'] . "</li>";
    }
    echo "</ul>";
    echo "<p>Total Cart Value: $" . number_format(getCartTotal(), 2) . "</p>";
} else {
    echo "<p>Your cart is empty.</p>";
}

// ------------------------------------
//  Cleanup Session (for demonstration purposes)
// ------------------------------------

// To clear the cart, you can use this (recommended for production, use a more robust solution)
// unset($_SESSION['cart']);
// session_destroy(); // Important to completely clear the session data.
?>
