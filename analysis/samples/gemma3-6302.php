

<?php

// Start a session if it doesn't exist
session_start();

// Initialize the cart as an empty array if it doesn't exist
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

// Function to add an item to the cart
function addToCart($product_id, $product_name, $price, $quantity = 1) {
    // Check if the item already exists in the cart
    if (isset($_SESSION['cart'][$product_id])) {
        // If it exists, increment the quantity
        $_SESSION['cart'][$product_id]['quantity'] += $quantity;
    } else {
        // If it doesn't exist, add the item to the cart
        $_SESSION['cart'][$product_id] = [
            'name' => $product_name,
            'price' => $price,
            'quantity' => $quantity
        ];
    }
}

// Function to remove an item from the cart
function removeFromCart($product_id) {
    if (isset($_SESSION['cart'][$product_id])) {
        unset($_SESSION['cart'][$product_id]);
    }
}

// Function to update the quantity of an item in the cart
function updateQuantity($product_id, $quantity) {
    if (isset($_SESSION['cart'][$product_id])) {
        $_SESSION['cart'][$product_id]['quantity'] = $quantity;
    }
}

// Example usage:
// Add some items to the cart
addToCart(1, 'T-Shirt', 20, 2);
addToCart(2, 'Jeans', 50, 1);

// Update the quantity of an item
updateQuantity(1, 3);

// Remove an item from the cart
// removeFromCart(2);

// Display the cart contents
echo "<h2>Your Cart</h2>";
if (empty($_SESSION['cart'])) {
    echo "<p>Your cart is empty.</p>";
} else {
    echo "<ul>";
    foreach ($_SESSION['cart'] as $product_id => $product_details) {
        echo "<li>" . $product_details['name'] . " - $" . $product_details['price'] . " x " . $product_details['quantity'] . " = $" . ($product_details['price'] * $product_details['quantity']) . "</li>";
    }
    echo "</ul>";
}

?>
