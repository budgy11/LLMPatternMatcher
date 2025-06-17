

<?php
session_start();

// Function to add an item to the cart
function addToCart($item_id, $item_name, $item_price, $quantity = 1) {
    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = [];
    }

    // Check if the item already exists in the cart
    if (isset($_SESSION['cart'][$item_id])) {
        // If it exists, update the quantity
        $_SESSION['cart'][$item_id]['quantity'] += $quantity;
    } else {
        // If it doesn't exist, add it to the cart
        $_SESSION['cart'][$item_id] = [
            'name' => $item_name,
            'price' => $item_price,
            'quantity' => $quantity
        ];
    }
}

// Function to remove an item from the cart
function removeItemFromCart($item_id) {
    if (isset($_SESSION['cart'][$item_id])) {
        unset($_SESSION['cart'][$item_id]);
    }
}

// Function to update the quantity of an item in the cart
function updateQuantity($item_id, $quantity) {
    if (isset($_SESSION['cart'][$item_id])) {
        $_SESSION['cart'][$item_id]['quantity'] = $quantity;
    }
}


// Example usage:

// Add an item to the cart
addToCart(1, "T-Shirt", 20.00, 2);
addToCart(2, "Jeans", 50.00, 1);

// Display the cart contents
echo "<h2>Cart Items</h2>";
if (isset($_SESSION['cart']) && count($_SESSION['cart']) > 0) {
    echo "<ul>";
    foreach ($_SESSION['cart'] as $item_id => $item_details) {
        echo "<li>";
        echo "<strong>" . $item_details['name'] . "</strong> - $" . $item_details['price'] . " x " . $item_details['quantity'];
        echo "</li>";
    }
    echo "</ul>";

    // Calculate the total price
    $totalPrice = 0;
    foreach ($_SESSION['cart'] as $item_id => $item_details) {
        $totalPrice += $item_details['price'] * $item_details['quantity'];
    }
    echo "<p><strong>Total Price: $" . $totalPrice . "</strong></p>";

    // Remove an item (e.g., remove item with ID 2)
    // removeItemFromCart(2);

    // Update quantity of an item (e.g., update quantity of item with ID 1 to 3)
    // updateQuantity(1, 3);


} else {
    echo "<p>Your cart is empty.</p>";
}


// Session management:  Important for persistent cart data.  This is the basic session setup.
//  In a real application, you would likely handle session start within a route or controller.
//  This example demonstrates the initial session setup.
?>
