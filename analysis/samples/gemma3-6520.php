

<?php
session_start(); // Start the session

// --- Cart Variables ---
$cart = [];  // Array to store items in the cart
$item_id = 1; // Unique ID for each item (for tracking)
$item_name = "Product A";
$item_price = 10.00;
$item_quantity = 1;

// --- Add to Cart Function ---
function add_to_cart($item_id, $item_name, $item_price, $item_quantity) {
    global $cart;

    // Check if the item is already in the cart
    if (isset($cart[$item_id])) {
        $cart[$item_id]['quantity'] += $item_quantity;
    } else {
        // Item not in cart, add it
        $cart[$item_id] = [
            'id' => $item_id,
            'name' => $item_name,
            'price' => $item_price,
            'quantity' => $item_quantity
        ];
    }
}


// --- Example Usage (Add items to the cart) ---
add_to_cart($item_id, $item_name, $item_price, 2);
add_to_cart($item_id, $item_name, $item_price, 1);
add_to_cart($item_id, "Another Item", 25.00, 1);


// --- Display the Cart ---
echo "<h2>Your Shopping Cart</h2>";

if (empty($cart)) {
    echo "<p>Your cart is empty.</p>";
} else {
    echo "<ul>";
    foreach ($cart as $id => $item) {
        echo "<li>";
        echo "<strong>" . $item['name'] . "</strong> - $" . $item['price'] . " x " . $item['quantity'] . " = $" . ($item['price'] * $item['quantity']) . "</li>";
    }
    echo "</ul>";
}

// --- Calculate Total Cart Value ---
$total = 0;
foreach ($cart as $item) {
    $total += $item['price'] * $item['quantity'];
}
echo "<p><strong>Total Cart Value: $" . $total . "</strong></p>";

?>
