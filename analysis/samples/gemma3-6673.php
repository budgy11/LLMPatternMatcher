

<?php

session_start();

// Check if the cart is empty
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = []; // Initialize the cart as an empty array
}

// Function to add an item to the cart
function addToCart($product_id, $product_name, $price, $quantity = 1) {
    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = [];
    }

    // Check if the item already exists in the cart
    if (isset($_SESSION['cart'][$product_id])) {
        // Item exists, increment the quantity
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
function removeItemFromCart($product_id) {
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


// Example Usage:  (You would typically get this data from a form or database)
// Add an item to the cart
addToCart(1, "Laptop", 1200, 1);
addToCart(2, "Mouse", 25, 2);


// Remove an item from the cart
//removeItemFromCart(2);

// Update the quantity of an item
//updateQuantity(1, 3);

// Display the cart contents
echo "<h2>Cart</h2>";
if (empty($_SESSION['cart'])) {
    echo "<p>Your cart is empty.</p>";
} else {
    echo "<ul>";
    foreach ($_SESSION['cart'] as $item_id => $item_data) {
        echo "<li>";
        echo "<strong>" . $item_data['name'] . "</strong> - $" . $item_data['price'] . " x " . $item_data['quantity'] . " = $" . ($item_data['price'] * $item_data['quantity']) . "</li>";
    }
    echo "</ul>";
}


?>
