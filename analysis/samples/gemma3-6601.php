

<?php

// Start the session if it's not already started
session_start();

// Check if the cart is initialized
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = array();
}

// --- Function to add an item to the cart ---
function add_to_cart($product_id, $product_name, $price, $quantity = 1) {
    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = array();
    }

    // Check if the product is already in the cart
    if (isset($_SESSION['cart'][$product_id])) {
        // If it is, increase the quantity
        $_SESSION['cart'][$product_id]['quantity'] += $quantity;
    } else {
        // If it's not, add the product to the cart
        $_SESSION['cart'][$product_id] = array(
            'name' => $product_name,
            'price' => $price,
            'quantity' => $quantity
        );
    }
}

// --- Example Usage (You'd typically get this data from a database or form) ---

// Add some items to the cart
add_to_cart(1, 'Laptop', 1200, 1); // Product ID 1, Laptop, $1200, Quantity 1
add_to_cart(2, 'Mouse', 25, 2);   // Product ID 2, Mouse, $25, Quantity 2
add_to_cart(1, 'Laptop', 1200, 1); // Adding another laptop

// --- Displaying the Cart Contents ---
echo "<h2>Shopping Cart</h2>";

if (empty($_SESSION['cart'])) {
    echo "<p>Your cart is empty.</p>";
} else {
    echo "<ul>";
    foreach ($_SESSION['cart'] as $product_id => $product_details) {
        echo "<li>";
        echo "<strong>Product:</strong> " . $product_details['name'] . "<br>";
        echo "<strong>Price:</strong> $" . number_format($product_details['price'], 2) . "<br>";
        echo "<strong>Quantity:</strong> " . $product_details['quantity'] . "<br>";
        echo "<strong>Total for this item:</strong> $" . number_format($product_details['price'] * $product_details['quantity'], 2) . "<br>";
        echo "</li>";
    }
    echo "</ul>";
}

?>
