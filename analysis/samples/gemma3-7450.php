

<?php
session_start();

// Configuration
$cart_file = 'cart.php'; // File to store the cart data
$item_name_key = 'item_name';
$item_price_key = 'item_price';
$quantity_key = 'quantity';

// Helper Functions

/**
 * Adds an item to the cart.
 *
 * @param string $itemName The name of the item.
 * @param float $itemPrice The price of the item.
 * @param int $quantity The quantity to add.
 */
function addToCart($itemName, $itemPrice, $quantity) {
    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = [];
    }

    $_SESSION['cart'][] = [
        $item_name_key => $itemName,
        $quantity_key => $quantity
    ];
    
    // Update existing item
    foreach ($_SESSION['cart'] as &$item) {
        if ($item[$item_name_key] === $itemName) {
            $item[$quantity_key] += $quantity;
            break;
        }
    }
}

/**
 * Removes an item from the cart by name.
 *
 * @param string $itemName The name of the item to remove.
 */
function removeFromCart($itemName) {
    if (isset($_SESSION['cart'])) {
        foreach ($_SESSION['cart'] as $key => $item) {
            if ($item[$item_name_key] === $itemName) {
                unset($_SESSION['cart'][$key]);
                // Re-index the cart array after removal
                $_SESSION['cart'] = array_values($_SESSION['cart']);
                return;
            }
        }
    }
}

/**
 * Gets the cart contents.
 *
 * @return array An array containing the cart items.
 */
function getCart() {
    if (isset($_SESSION['cart'])) {
        return $_SESSION['cart'];
    }
    return [];
}

/**
 * Calculates the total cart value.
 *
 * @return float The total cart value.
 */
function calculateTotal() {
    $total = 0;
    $cart = getCart();
    foreach ($cart as $item) {
        $total += $item[$item_price_key] * $item[$quantity_key];
    }
    return $total;
}

// ---  Shopping Cart Logic  ---

// Example Usage (Illustrative - Replace with your actual product data)

// Add some items to the cart
addToCart('Laptop', 1200.00, 1);
addToCart('Mouse', 25.00, 2);
addToCart('Keyboard', 75.00, 1);



// Display the cart contents
$cart = getCart();

if (empty($cart)) {
    echo "<h1>Your Cart is Empty</h1>";
} else {
    echo "<h2>Shopping Cart</h2>";
    echo "<ul>";
    foreach ($cart as $item) {
        echo "<li>" . $item[$item_name_key] . " - $" . $item[$item_price_key] . " x " . $item[$quantity_key] . " = $" . ($item[$item_price_key] * $item[$quantity_key]) . "</li>";
    }
    echo "</ul>";

    echo "<p><strong>Total: $" . calculateTotal() . "</strong></p>";

    // Example: Remove an item
    // removeFromCart('Mouse');

}


// ---  Save Cart to File (Optional - for persistence) ---
// This example saves the cart data to a file named 'cart.php'
// This file will be loaded on subsequent page loads.
//  Important:  Make sure this file is not directly accessible via web browser
//  to prevent security vulnerabilities.  Proper file permissions should be set.
//
// if (empty($_SESSION['cart'])) {
//     // Clear the cart if it's empty to avoid saving an empty cart
//     unset($_SESSION['cart']);
// }
// else {
//     file_put_contents($cart_file, json_encode($_SESSION['cart']));
// }
?>
