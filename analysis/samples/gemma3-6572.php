

<?php

// Initialize the cart session if it doesn't exist
session_start();

// Check if the cart exists, if not, create it
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = array();
}

// Function to add an item to the cart
function addToCart($product_id, $product_name, $price, $quantity = 1) {
    // Check if the product is already in the cart
    if (isset($_SESSION['cart'][$product_id])) {
        // If it exists, increment the quantity
        $_SESSION['cart'][$product_id]['quantity'] += $quantity;
    } else {
        // If it doesn't exist, add a new entry
        $_SESSION['cart'][$product_id] = array(
            'name' => $product_name,
            'price' => $price,
            'quantity' => $quantity
        );
    }
}

// Example Usage:  (Simulate user adding items)

// Add item 1
addToCart(1, "Laptop", 1200, 1);

// Add item 2
addToCart(2, "Mouse", 25, 2);

// Add item 1 again
addToCart(1, "Laptop", 1200, 1);


// Display the cart contents (for demonstration)
echo "<h2>Shopping Cart</h2>";
if (empty($_SESSION['cart'])) {
    echo "<p>Your cart is empty.</p>";
} else {
    echo "<ul>";
    foreach ($_SESSION['cart'] as $product_id => $item) {
        echo "<li>";
        echo "Product: " . $item['name'] . "<br>";
        echo "Price: $" . $item['price'] . "<br>";
        echo "Quantity: " . $item['quantity'] . "<br>";
        echo "Subtotal: $" . ($item['price'] * $item['quantity']) . "<br>";
        echo "</li>";
    }
    echo "</ul>";
}


// Function to remove an item from the cart (example)
function removeFromCart($product_id) {
    if (isset($_SESSION['cart'][$product_id])) {
        unset($_SESSION['cart'][$product_id]);
    }
}

// Example of removing an item (if needed)
// removeFromCart(2);
// echo "<h2>Shopping Cart After Removal</h2>";
// if (empty($_SESSION['cart'])) {
//     echo "<p>Your cart is empty.</p>";
// } else {
//     foreach ($_SESSION['cart'] as $product_id => $item) {
//         echo "<li>Product: " . $item['name'] . "<br>";
//         echo "Price: $" . $item['price'] . "<br>";
//         echo "Quantity: " . $item['quantity'] . "<br>";
//         echo "</li>";
//     }
// }



?>
