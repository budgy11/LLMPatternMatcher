

<?php

// Data storage (replace with a database for production)
$cart = []; // Array to hold items in the cart
$products = [
    1 => ['name' => 'T-Shirt', 'price' => 20],
    2 => ['name' => 'Jeans', 'price' => 50],
    3 => ['name' => 'Hat', 'price' => 15],
];

// Function to add an item to the cart
function add_to_cart($product_id, $quantity = 1) {
    if (isset($products[$product_id])) {
        $product = $products[$product_id];
        if (isset($cart[$product_id])) {
            $cart[$product_id]['quantity'] += $quantity;
        } else {
            $cart[$product_id] = ['name' => $product['name'], 'price' => $product['price'], 'quantity' => $quantity];
        }
    } else {
        echo "Product ID " . $product_id . " not found.";
    }
}

// Function to display the cart
function display_cart() {
    echo "<h2>Your Shopping Cart</h2>";
    if (empty($cart)) {
        echo "<p>Your cart is empty.</p>";
        return;
    }

    echo "<ul>";
    foreach ($cart as $item_id => $item_details) {
        $product = $products[$item_id];
        $total_price = $product['price'] * $item_details['quantity'];
        echo "<li>" . $item_details['name'] . " - $" . $product['price'] . " x " . $item_details['quantity'] . " = $" . $total_price . "</li>";
    }
    echo "</ul>";
}

// Function to calculate the total cart value
function calculate_total() {
    $total = 0;
    foreach ($cart as $item_id => $item_details) {
        $product = $products[$item_id];
        $total_price = $product['price'] * $item_details['quantity'];
        $total += $total_price;
    }
    return $total;
}


// --- Handling Purchase Logic (simplified) ---
// In a real application, you'd integrate with payment gateways,
// manage shipping addresses, order confirmations, etc.

function confirm_purchase() {
    if (empty($cart)) {
        echo "<p>Your cart is empty.  Nothing to purchase.</p>";
        return;
    }

    $total = calculate_total();
    echo "<h2>Order Confirmation</h2>";
    echo "<p>Thank you for your purchase!</p>";
    echo "<p>Total Amount: $" . $total . "</p>";

    // Simulate order processing (replace with actual processing)
    // e.g., save order to database, send email confirmation, etc.
    echo "<p>Your order has been placed and is being processed.</p>";

    // Clear the cart after purchase
    $cart = [];
}


// --- Example Usage (handling user input) ---

// Check if the purchase form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // If 'purchase' button was clicked
    if (isset($_POST['purchase'])) {
        confirm_purchase();
    }
}

?>
