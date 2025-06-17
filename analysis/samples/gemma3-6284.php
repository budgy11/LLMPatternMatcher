

<?php
session_start();

// Define cart variables (these will be stored in the session)
$cart = array();
$total_items = 0;
$total_price = 0.00;

// Function to add an item to the cart
function add_to_cart($item_id, $item_name, $item_price, $quantity = 1) {
    global $cart;

    // Check if the item is already in the cart
    if (isset($cart[$item_id])) {
        $cart[$item_id]['quantity'] += $quantity;
    } else {
        $cart[$item_id] = array(
            'name' => $item_name,
            'price' => $item_price,
            'quantity' => $quantity
        );
    }

    // Update total items and price
    $total_items += $quantity;
    $total_price += $item_price * $quantity;
}

// --- Example Usage (Simulating user interactions) ---

// Add some items to the cart
add_to_cart(1, 'T-Shirt', 20.00, 2);
add_to_cart(2, 'Jeans', 50.00, 1);
add_to_cart(1, 'T-Shirt', 20.00, 1); // Add another T-Shirt

// Display the cart contents
echo "<h2>Your Shopping Cart</h2>";
if (empty($cart)) {
    echo "<p>Your cart is empty.</p>";
} else {
    echo "<ul>";
    foreach ($cart as $item_id => $item_details) {
        echo "<li>";
        echo "<strong>" . $item_details['name'] . "</strong> - $" . number_format($item_details['price'], 2) . "</li>";
        echo "Quantity: " . $item_details['quantity'] . "<br>";
    }
    echo "</ul>";

    // Calculate and display the total price
    echo "<p><strong>Total: $" . number_format($total_price, 2) . "</strong></p>";
}

// ---  Session Management (Example) ---

//  You would typically do this based on user actions (e.g., adding to cart, updating quantity, removing item)
//  This is just a simplified example

//  Let's simulate adding an item.  In a real app, this would come from user interaction.
// add_to_cart(3, 'Hat', 15.00, 1);

// To clear the cart, you'd reset the session variables:
// session_start();
// unset($cart);
// session_write_close(); // Important to release the session
?>
