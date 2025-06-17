

<?php
session_start();

// This script handles adding items to the cart

// Function to add an item to the cart
function addItemToCart($productId, $productName, $price, $quantity) {
    if (empty($_SESSION['cart'])) {
        // Cart is empty, initialize it with the first item
        $_SESSION['cart'] = array();
    }

    // Create an associative array for the item
    $_SESSION['cart'][$productId] = array(
        'product_name' => $productName,
        'price' => $price,
        'quantity' => $quantity
    );
}

// --- Example Usage (Simulating User Actions) ---

// Simulate a user adding a product to the cart
addItemToCart(1, "Laptop", 1200, 1); // Product ID 1, Laptop, Price $1200, Quantity 1
addItemToCart(2, "Mouse", 25, 2);   // Product ID 2, Mouse, Price $25, Quantity 2

// --- Cart Display (For demonstration) ---

// Check if the cart is empty
if (empty($_SESSION['cart'])) {
    echo "<p>Your cart is empty.</p>";
} else {
    echo "<h2>Your Cart:</h2>";
    echo "<ul>";

    foreach ($_SESSION['cart'] as $productId => $item) {
        echo "<li>";
        echo "Product: " . $item['product_name'] . "<br>";
        echo "Price: $" . $item['price'] . "<br>";
        echo "Quantity: " . $item['quantity'] . "<br>";
        echo "Subtotal: $" . ($item['price'] * $item['quantity']) . "<br>";
        echo "</li>";
    }

    echo "</ul>";

    // Calculate the total cart value
    $total = 0;
    foreach ($_SESSION['cart'] as $productId => $item) {
        $total += ($item['price'] * $item['quantity']);
    }

    echo "<p><strong>Total Cart Value: $" . $total . "</strong></p>";
}
?>
