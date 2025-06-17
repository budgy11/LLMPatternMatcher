

<?php
session_start();

// --- Cart Session Variables ---
// Define session variables to store cart data.
// These keys are just examples; you can adjust them as needed.
define('CART_KEY', 'shopping_cart');

// Function to add an item to the cart
function addToCart($product_id, $quantity, $product_name = null) {
    if (!isset($_SESSION[CART_KEY])) {
        $_SESSION[CART_KEY] = []; // Initialize the cart array
    }

    // Check if the product already exists in the cart
    if (isset($_SESSION[CART_KEY][$product_id])) {
        // If it exists, increase the quantity
        $_SESSION[CART_KEY][$product_id]['quantity'] += $quantity;
    } else {
        // If it doesn't exist, add it to the cart
        $_SESSION[CART_KEY][$product_id] = [
            'quantity' => $quantity,
            'name' => $product_name ?? $product_id // Using product_name if available, otherwise product_id
        ];
    }
}

// Function to get the cart contents
function getCartContents() {
    if (isset($_SESSION[CART_KEY])) {
        return $_SESSION[CART_KEY];
    } else {
        return []; // Return an empty array if the cart is empty
    }
}

// Function to update the quantity of an item in the cart
function updateCartItemQuantity($product_id, $new_quantity) {
    if (isset($_SESSION[CART_KEY][$product_id])) {
        $_SESSION[CART_KEY][$product_id]['quantity'] = $new_quantity;
    }
}

// Function to remove an item from the cart
function removeItemFromCart($product_id) {
    if (isset($_SESSION[CART_KEY][$product_id])) {
        unset($_SESSION[CART_KEY][$product_id]);
    }
}

// Function to clear the entire cart
function clearCart() {
    unset($_SESSION[CART_KEY]);
}

// --- Example Usage (Demonstration) ---
// Add some items to the cart
addToCart(1, 2); // Product ID 1, Quantity 2
addToCart(2, 1); // Product ID 2, Quantity 1
addToCart(1, 3); // Product ID 1, Quantity 3

// Display the cart contents
$cart = getCartContents();
echo "<h2>Cart Contents:</h2>";
if (empty($cart)) {
    echo "<p>Your cart is empty.</p>";
} else {
    echo "<ul>";
    foreach ($cart as $product_id => $item) {
        echo "<li>Product ID: " . $product_id . ", Quantity: " . $item['quantity'] . ", Name: " . $item['name'] . "</li>";
    }
    echo "</ul>";
}


// Example: Update the quantity of product 1 to 5
updateCartItemQuantity(1, 5);

echo "<hr>";
echo "<h2>Cart Contents After Update:</h2>";
$cart = getCartContents();
foreach ($cart as $product_id => $item) {
    echo "<li>Product ID: " . $product_id . ", Quantity: " . $item['quantity'] . ", Name: " . $item['name'] . "</li>";
}



// Example: Remove product 2 from the cart
removeItemFromCart(2);

// Example: Clear the entire cart
// clearCart();


?>
