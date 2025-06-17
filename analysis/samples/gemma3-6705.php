

<?php
session_start();

// Initialize the cart if it doesn't exist
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = array();
}

// Function to add an item to the cart
function add_to_cart($product_id, $product_name, $price, $quantity = 1) {
    if (!isset($_SESSION['cart'][$product_id])) {
        $_SESSION['cart'][$product_id] = array(
            'name' => $product_name,
            'price' => $price,
            'quantity' => $quantity
        );
    } else {
        $_SESSION['cart'][$product_id]['quantity'] += $quantity;
    }
}

// Function to remove an item from the cart
function remove_from_cart($product_id) {
    if (isset($_SESSION['cart'][$product_id])) {
        unset($_SESSION['cart'][$product_id]);
    }
}

// Function to update the quantity of an item in the cart
function update_quantity($product_id, $new_quantity) {
    if (isset($_SESSION['cart'][$product_id])) {
        $_SESSION['cart'][$product_id]['quantity'] = $new_quantity;
    }
}

// Example Usage (Simulated Product Data - Replace with your actual data)
$products = array(
    1 => array('name' => 'Laptop', 'price' => 1200),
    2 => array('name' => 'Mouse', 'price' => 25),
    3 => array('name' => 'Keyboard', 'price' => 75)
);

// Example: Adding products to the cart
add_to_cart(1, $products[1]['name'], $products[1]['price'], 1);
add_to_cart(2, $products[2]['name'], $products[2]['price'], 2);
add_to_cart(1, $products[1]['name'], $products[1]['price'], 2); // Adding more of the laptop

// Example: Removing an item
//remove_from_cart(2);

// Example: Updating the quantity
//update_quantity(1, 3);

// Display the Cart Contents
echo "<h2>Shopping Cart</h2>";

if (empty($_SESSION['cart'])) {
    echo "<p>Your cart is empty.</p>";
} else {
    echo "<ul>";
    foreach ($_SESSION['cart'] as $product_id => $product_details) {
        echo "<li>";
        echo "<strong>" . $product_details['name'] . "</strong> - $" . $product_details['price'] . " x " . $product_details['quantity'];
        echo "</li>";
    }
    echo "</ul>";

    // Calculate the total price
    $total_price = 0;
    foreach ($_SESSION['cart'] as $product_id => $product_details) {
        $total_price += $product_details['price'] * $product_details['quantity'];
    }
    echo "<p><strong>Total: $" . $total_price . "</strong></p>";
}

?>
