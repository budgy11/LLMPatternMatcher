

<?php

// This script demonstrates a basic user cart session in PHP.
// It's a simplified example and doesn't include security features like
// input validation or protection against CSRF attacks.  For production
// environments, you'll need to add robust security measures.

session_start();

// --- Cart Initialization ---

// If the cart session variable doesn't exist, initialize it as an empty array.
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

// --- Helper Functions ---

// Add a product to the cart
function addToCart($product_id, $product_name, $price, $quantity = 1) {
    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = []; // Ensure the cart exists
    }

    // Check if the product is already in the cart
    if (isset($_SESSION['cart'][$product_id])) {
        // Product exists, increment quantity
        $_SESSION['cart'][$product_id]['quantity'] += $quantity;
    } else {
        // Product doesn't exist, add it to the cart
        $_SESSION['cart'][$product_id] = [
            'name' => $product_name,
            'price' => $price,
            'quantity' => $quantity
        ];
    }
}

// Remove a product from the cart
function removeProduct($product_id) {
    if (isset($_SESSION['cart'][$product_id])) {
        unset($_SESSION['cart'][$product_id]);
    }
}

// Update the quantity of a product in the cart
function updateQuantity($product_id, $new_quantity) {
    if (isset($_SESSION['cart'][$product_id])) {
        $_SESSION['cart'][$product_id]['quantity'] = $new_quantity;
    }
}

// Get the cart contents
function getCart() {
    return $_SESSION['cart'];
}

// Calculate the total cart value
function calculateTotal() {
    $total = 0;
    if (isset($_SESSION['cart'])) {
        foreach($_SESSION['cart'] as $item) {
            $total += $item['price'] * $item['quantity'];
        }
    }
    return $total;
}


// --- Example Usage (Simulated User Actions) ---

//  Simulate a user adding a product to the cart:
addToCart(1, "Laptop", 1200, 1); // Product ID 1, Laptop, $1200, Quantity 1
addToCart(2, "Mouse", 25, 2);   // Product ID 2, Mouse, $25, Quantity 2
addToCart(1, "Laptop", 1200); // Add another laptop

// Display the cart contents:
echo "<h2>Your Shopping Cart</h2>";
if (empty($_SESSION['cart'])) {
    echo "<p>Your cart is empty.</p>";
} else {
    echo "<ul>";
    foreach ($_SESSION['cart'] as $product_id => $product) {
        echo "<li>";
        echo "<strong>Product:</strong> " . $product['name'] . "<br>";
        echo "<strong>Price:</strong> $" . $product['price'] . "<br>";
        echo "<strong>Quantity:</strong> " . $product['quantity'] . "<br>";
        echo "<strong>Total for this item:</strong> $" . $product['price'] * $product['quantity'] . "<br>";
        echo "</li>";
    }
    echo "</ul>";
    echo "<p><strong>Total Cart Value:</strong> $" . calculateTotal() . "</p>";
}


//  Simulate a user removing a product from the cart:
//removeProduct(2); // Remove the mouse

//Simulate updating the quantity of a product
//updateQuantity(1, 3);  // Increase laptop quantity to 3


?>
