

<?php

// This script handles the purchase process.
// It simulates adding items to a cart, processing the order,
// and providing a confirmation message.

// Assume we have a database connection setup (replace with your actual connection)
// For demonstration, we'll use a simple array for the cart.
$cart = [];
$order_total = 0.0;

// --- Helper Functions ---

/**
 * Adds an item to the cart.
 *
 * @param string $item_name
 * @param float $price
 * @return void
 */
function addItemToCart(string $item_name, float $price) {
    global $cart;
    $cart[] = ['name' => $item_name, 'price' => $price];
    $order_total += $price;
}

/**
 * Calculates the total order amount.
 *
 * @return float
 */
function calculateOrderTotal() {
    global $order_total;
    return $order_total;
}

/**
 * Displays the cart contents.
 *
 * @return void
 */
function displayCart() {
    echo "<h2>Cart Items</h2>";
    echo "<ul>";
    foreach ($cart as $item) {
        echo "<li>$item['name'] - $" . number_format($item['price'], 2) . "</li>";
    }
    echo "</ul>";
    echo "<p><strong>Total: $" . number_format($order_total, 2) . "</strong></p>";
}

// ---  Processing the Purchase (Simulated) ---

// 1. Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // 2. Validate the data (basic example - improve for production)
    if (empty($_POST['item_name']) || empty($_POST['item_price'])) {
        $error_message = "Please fill in all fields.";
    } elseif (!is_numeric($_POST['item_price']) || $_POST['item_price'] <= 0) {
        $error_message = "Invalid item price. Please enter a positive number.";
    } else {
        // 3. Add the item to the cart
        addItemToCart($_POST['item_name'], (float)$_POST['item_price']);

        // 4. Display the cart
        displayCart();

        // 5.  Confirmation message
        echo "<p><strong>Thank you for your order!</strong></p>";
        echo "<p>Your order has been placed.</p>";

        // Reset the cart (for subsequent purchases)
        $cart = [];
        $order_total = 0.0;
    }
}
?>
