

<?php

// Session handling is crucial for cart functionality.  This example demonstrates
// setting up a basic cart session.  It's a starting point; you'll likely want
// to add more robust features like database integration and more sophisticated
// quantity/price handling.

// 1. Start the Session (if not already started)
session_start();

// 2. Initialize the Cart (if it doesn't exist)
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = array();
}

// 3. Functions to Handle Cart Operations

// Add an item to the cart
function add_to_cart($product_id, $quantity = 1, $price = 0) {
    if (!isset($_SESSION['cart'][$product_id])) {
        $_SESSION['cart'][$product_id] = array(
            'quantity' => $quantity,
            'price'    => $price
        );
    } else {
        $_SESSION['cart'][$product_id]['quantity'] += $quantity;
    }
}


// Remove an item from the cart
function remove_from_cart($product_id) {
    if (isset($_SESSION['cart'][$product_id])) {
        unset($_SESSION['cart'][$product_id]);
    }
}


// Update the quantity of an item in the cart
function update_cart_quantity($product_id, $quantity) {
    if (isset($_SESSION['cart'][$product_id])) {
        $_SESSION['cart'][$product_id]['quantity'] = $quantity;
    }
}

// Get the contents of the cart
function get_cart_contents() {
    return $_SESSION['cart'];
}


// 4. Example Usage (Demonstration - Replace with your actual product information and UI)

//  Let's add some items to the cart.
add_to_cart(101, 2); // Product ID 101, quantity 2
add_to_cart(102, 1); // Product ID 102, quantity 1
add_to_cart(101, 1); // Product ID 101, quantity 1 (adding to an existing item)

// Display the cart contents
echo "<h2>Your Shopping Cart</h2>";
echo "<ul>";
$cart_items = get_cart_contents();
if (empty($cart_items)) {
    echo "<li>Cart is empty.</li>";
} else {
    foreach ($cart_items as $product_id => $item) {
        echo "<li>Product ID: " . $product_id . ", Quantity: " . $item['quantity'] . ", Price: $" . $item['price'] . "</li>";
    }
}
echo "</ul>";

// Example of Removing an Item
// remove_from_cart(102);

// Example of Updating the quantity of an item.
// update_cart_quantity(101, 3);



?>
