

<?php
session_start();

// Assuming you have a function to add items to the cart (e.g., addToCart())

// Example:
function addToCart($product_id, $quantity) {
    // Logic to add the product to the cart
    if (isset($_SESSION['cart'])) {
        // Check if the product is already in the cart
        if (isset($_SESSION['cart'][$product_id])) {
            $_SESSION['cart'][$product_id]['quantity'] += $quantity;
        } else {
            // Product not in cart, add it
            $_SESSION['cart'][$product_id] = ['quantity' => $quantity];
        }
    } else {
        // Cart is empty, create it and add the product
        $_SESSION['cart'] = [$product_id => ['quantity' => $quantity]];
    }
}

// Example usage:
// Let's say we want to add a product with ID 1 to the cart in quantity 2
addToCart(1, 2);

// Display the cart contents
echo "<h2>Your Cart</h2>";
if (isset($_SESSION['cart'])) {
    echo "<ul>";
    foreach ($_SESSION['cart'] as $product_id => $item) {
        echo "<li>Product ID: " . $product_id . ", Quantity: " . $item['quantity'] . "</li>";
    }
    echo "</ul>";
} else {
    echo "<p>Your cart is empty.</p>";
}

//  You would likely have a way to remove items, update quantities, and
//  calculate the total cost in a real application.
?>
