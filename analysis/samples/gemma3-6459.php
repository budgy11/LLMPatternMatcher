

<?php
session_start(); // Start the session

// Cart array (could be database-backed in a real application)
$cart = array();

// Function to add an item to the cart
function addToCart($item_id, $item_name, $price, $quantity = 1) {
    if (!isset($cart[$item_id])) {
        $cart[$item_id] = array(
            'name' => $item_name,
            'price' => $price,
            'quantity' => $quantity,
            'total' => $price * $quantity
        );
    } else {
        $cart[$item_id]['quantity'] += $quantity;
        $cart[$item_id]['total'] = $cart[$item_id]['price'] * $cart[$item_id]['quantity'];
    }
}

// Function to remove an item from the cart
function removeFromCart($item_id) {
    if (isset($cart[$item_id])) {
        unset($cart[$item_id]);
    }
}

// Function to update the quantity of an item in the cart
function updateQuantity($item_id, $new_quantity) {
    if (isset($cart[$item_id])) {
        $cart[$item_id]['quantity'] = $new_quantity;
        $cart[$item_id]['total'] = $cart[$item_id]['price'] * $cart[$item_id]['quantity'];
    }
}

// ----- Example Usage (simulating user interaction) -----

// 1. Add some items to the cart
addToCart(1, "T-Shirt", 20, 2);  // Item ID 1, T-Shirt, price 20, quantity 2
addToCart(2, "Jeans", 50, 1);    // Item ID 2, Jeans, price 50, quantity 1
addToCart(1, "Hat", 15, 1);      // Add another T-Shirt

// 2. Display the cart contents
echo "<h2>Your Cart</h2>";
if (empty($cart)) {
    echo "<p>Your cart is empty.</p>";
} else {
    echo "<ul>";
    foreach ($cart as $item_id => $item_data) {
        echo "<li>";
        echo "<strong>Item:</strong> " . $item_data['name'] . "<br>";
        echo "<strong>Price:</strong> $" . $item_data['price'] . "<br>";
        echo "<strong>Quantity:</strong> " . $item_data['quantity'] . "<br>";
        echo "<strong>Total:</strong> $" . $item_data['total'] . "<br>";
        echo "</li>";
    }
    echo "</ul>";
}

// 3. Simulate user removing an item
// remove an item (e.g., remove the first T-Shirt)
//removeFromCart(1);

// 4.  Simulate user updating the quantity of an item
//updateQuantity(2, 3); // Change the quantity of Jeans to 3

//  ---- End of Example Usage -----

?>
