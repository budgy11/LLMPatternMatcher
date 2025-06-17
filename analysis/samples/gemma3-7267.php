

<?php

// This is a very basic example.  A production-ready implementation 
// would require a database, user authentication, error handling,
// validation, and more.

// --- Configuration (Replace with your actual values) ---
$products = [
    1 => ['name' => 'T-Shirt', 'price' => 20.00],
    2 => ['name' => 'Mug', 'price' => 10.00],
    3 => ['name' => 'Notebook', 'price' => 5.00],
];

$cart = []; // Initialize an empty cart
// --------------------------------------------------


// Function to add an item to the cart
function addToCart($productId, $quantity = 1, $cart = null) {
    if ($cart === null) {
        $cart = [];  // Initialize the cart if it's null
    }

    if (isset($products[$productId])) {
        $product = $products[$productId];
        $cart[$productId] = $product['price'] * $quantity;
        return $cart;
    } else {
        return $cart; // Product not found
    }
}

// Function to display the cart
function displayCart($cart) {
    echo "<h2>Your Cart</h2>";
    if (empty($cart)) {
        echo "<p>Your cart is empty.</p>";
    } else {
        echo "<ul>";
        foreach ($cart as $productId => $totalPrice) {
            echo "<li>" . $products[$productId]['name'] . ": $" . number_format($totalPrice, 2) . "</li>";
        }
        echo "</ul>";
        echo "<p><strong>Total: $" . number_format(array_sum($cart), 2) . "</p>";
    }
}

// ---  Example Usage (Simplified - simulates a purchase process) ---

// 1.  Simulate user adding items to the cart
addToCart(1, 2); // Add 2 T-Shirts
addToCart(2, 3); // Add 3 Mugs
addToCart(3, 1); // Add 1 Notebook


// 2. Display the cart
displayCart($cart);


// 3. Simulate a purchase confirmation (replace with actual payment processing)
echo "<p><strong>Order Confirmation:</strong></p>";
echo "<p>You have purchased: " . count($cart) . " items.</p>";
echo "<p>Thank you for your order!</p>";
?>
