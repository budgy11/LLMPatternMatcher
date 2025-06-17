

<?php

session_start(); // Start the session

// Check if the cart is empty, if not, initialize it
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = array();
}

// Example functions for adding and removing items
function addToCart($product_id, $product_name, $price, $quantity = 1) {
    // Check if the product is already in the cart
    if (isset($_SESSION['cart'][$product_id])) {
        // Product is already in the cart - increment quantity
        $_SESSION['cart'][$product_id]['quantity'] += $quantity;
    } else {
        // Product not in cart - add it
        $_SESSION['cart'][$product_id] = array(
            'name' => $product_name,
            'price' => $price,
            'quantity' => $quantity
        );
    }
}

function removeFromCart($product_id) {
    if (isset($_SESSION['cart'][$product_id])) {
        unset($_SESSION['cart'][$product_id]);
    }
}

function getCartItems() {
    return $_SESSION['cart'];
}

function updateQuantity($product_id, $new_quantity) {
    if (isset($_SESSION['cart'][$product_id])) {
        $_SESSION['cart'][$product_id]['quantity'] = $new_quantity;
    }
}


// --- Example Usage (Simulating user interactions) ---

// Add some products to the cart
addToCart(1, "Laptop", 1200, 1);
addToCart(2, "Mouse", 25, 2);
addToCart(1, "Laptop", 1200, 3);  // Add more of the same product


// Display the cart contents
echo "<h2>Your Cart</h2>";
if (empty($_SESSION['cart'])) {
    echo "<p>Your cart is empty.</p>";
} else {
    echo "<ul>";
    foreach ($_SESSION['cart'] as $product_id => $product_details) {
        echo "<li>";
        echo "<strong>Product:</strong> " . $product_details['name'] . "<br>";
        echo "<strong>Price:</strong> $" . $product_details['price'] . "<br>";
        echo "<strong>Quantity:</strong> " . $product_details['quantity'] . "<br>";
        echo "<strong>Total:</strong> $" . $product_details['price'] * $product_details['quantity'] . "<br>";
        echo "</li>";
    }
    echo "</ul>";
}

// Remove an item from the cart (example)
//removeFromCart(2);

// Update quantity of an item
//updateQuantity(1, 5);  // Change quantity of Laptop to 5

// Display updated cart contents after update
//echo "<h2>Your Cart After Update</h2>";
//if (empty($_SESSION['cart'])) {
//    echo "<p>Your cart is empty.</p>";
//} else {
//    echo "<ul>";
//    foreach ($_SESSION['cart'] as $product_id => $product_details) {
//        echo "<li>";
//        echo "<strong>Product:</strong> " . $product_details['name'] . "<br>";
//        echo "<strong>Price:</strong> $" . $product_details['price'] . "<br>";
//        echo "<strong>Quantity:</strong> " . $product_details['quantity'] . "<br>";
//        echo "<strong>Total:</strong> $" . $product_details['price'] * $product_details['quantity'] . "<br>";
//        echo "</li>";
//    }
//    echo "</ul>";
//}


?>
