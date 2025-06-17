

<?php

session_start();

// Initialize the cart as an array if it doesn't exist
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = array();
}

// --- Helper Functions ---

/**
 * Adds an item to the cart.
 *
 * @param int $product_id The ID of the product to add.
 * @param string $name The name of the product.
 * @param int $price The price of the product.
 * @param int $quantity The quantity of the product to add.
 */
function add_to_cart($product_id, $name, $price, $quantity) {
    // Check if the product is already in the cart
    if (isset($_SESSION['cart'][$product_id])) {
        // Product exists, increase the quantity
        $_SESSION['cart'][$product_id]['quantity'] += $quantity;
    } else {
        // Product doesn't exist, add it to the cart
        $_SESSION['cart'][$product_id] = array(
            'name' => $name,
            'price' => $price,
            'quantity' => $quantity
        );
    }
}

/**
 * Updates the quantity of an item in the cart.
 *
 * @param int $product_id The ID of the product to update.
 * @param int $new_quantity The new quantity of the product.
 */
function update_cart_quantity($product_id, $new_quantity) {
    if (isset($_SESSION['cart'][$product_id])) {
        $_SESSION['cart'][$product_id]['quantity'] = $new_quantity;
    }
}

/**
 * Removes an item from the cart.
 *
 * @param int $product_id The ID of the product to remove.
 */
function remove_from_cart($product_id) {
    if (isset($_SESSION['cart'][$product_id])) {
        unset($_SESSION['cart'][$product_id]);
    }
}


// --- Example Usage (This would typically be part of a form submission or AJAX call) ---

// 1. Add a product to the cart
// Example: Add 2 of product ID 123
// add_to_cart(123, "T-Shirt", 20, 2);


// 2. Update the quantity of a product
// Example: Update the quantity of product ID 123 to 5
// update_cart_quantity(123, 5);


// 3. Remove a product from the cart
// Example: Remove product ID 123
// remove_from_cart(123);


// --- Display the Cart ---

// Display the cart contents
echo "<h2>Your Cart</h2>";
if (empty($_SESSION['cart'])) {
    echo "<p>Your cart is empty.</p>";
} else {
    echo "<ul>";
    foreach ($_SESSION['cart'] as $product_id => $product_data) {
        echo "<li>";
        echo "<strong>Product:</strong> " . $product_data['name'] . "<br>";
        echo "<strong>Price:</strong> $" . $product_data['price'] . "<br>";
        echo "<strong>Quantity:</strong> " . $product_data['quantity'] . "<br>";
        echo "<strong>Total:</strong> $" . ($product_data['price'] * $product_data['quantity']) . "<br>";
        echo "</li>";
    }
    echo "</ul>";
}
?>
