

<?php

// Start the session (if it doesn't exist)
session_start();

// Initialize the cart array if it doesn't exist
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

// Function to add an item to the cart
function addToCart($product_id, $product_name, $price, $quantity = 1) {
    // Check if the item is already in the cart
    if (isset($_SESSION['cart'][$product_id])) {
        // Item exists, increase the quantity
        $_SESSION['cart'][$product_id]['quantity'] += $quantity;
    } else {
        // Item doesn't exist, add it to the cart
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
function updateQuantity($product_id, $new_quantity) {
    if (isset($_SESSION['cart'][$product_id])) {
        $_SESSION['cart'][$product_id]['quantity'] = $new_quantity;
    }
}

// Example Usage:

// Add a product to the cart
addToCart(1, "T-Shirt", 20.00, 2); // Product ID 1, T-Shirt, price $20.00, quantity 2
addToCart(2, "Jeans", 50.00, 1);   // Product ID 2, Jeans, price $50.00, quantity 1

// Display the cart contents
echo "<h2>Shopping Cart</h2>";
if (empty($_SESSION['cart'])) {
    echo "<p>Your cart is empty.</p>";
} else {
    echo "<ul>";
    foreach ($_SESSION['cart'] as $product_id => $product_details) {
        echo "<li>";
        echo "<strong>Product:</strong> " . $product_details['name'] . "<br>";
        echo "<strong>Price:</strong> $" . $product_details['price'] . "<br>";
        echo "<strong>Quantity:</strong> " . $product_details['quantity'] . "<br>";
        echo "<strong>Total for Product:</strong> $" . ($product_details['price'] * $product_details['quantity']) . "<br>";
        echo "</li>";
    }
    echo "</ul>";
}

// Example to remove an item from the cart
// removeFromCart(1);

// Example to update the quantity of an item
// updateQuantity(1, 3);

// After updating, display the cart contents again:
echo "<hr>";
echo "<h2>Shopping Cart (After Update)</h2>";
if (empty($_SESSION['cart'])) {
    echo "<p>Your cart is empty.</p>";
} else {
    echo "<ul>";
    foreach ($_SESSION['cart'] as $product_id => $product_details) {
        echo "<li>";
        echo "<strong>Product:</strong> " . $product_details['name'] . "<br>";
        echo "<strong>Price:</strong> $" . $product_details['price'] . "<br>";
        echo "<strong>Quantity:</strong> " . $product_details['quantity'] . "<br>";
        echo "<strong>Total for Product:</strong> $" . ($product_details['price'] * $product_details['quantity']) . "<br>";
        echo "</li>";
    }
    echo "</ul>";
}

?>
