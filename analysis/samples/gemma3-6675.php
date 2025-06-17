

<?php
session_start();

// Initialize the cart session if it doesn't exist
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

// Function to add an item to the cart
function add_to_cart($product_id, $product_name, $price, $quantity = 1) {
    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = [];
    }

    // Check if the product is already in the cart
    if (isset($_SESSION['cart'][$product_id])) {
        // If it is, increase the quantity
        $_SESSION['cart'][$product_id]['quantity'] += $quantity;
    } else {
        // If it isn't, add the product to the cart
        $_SESSION['cart'][$product_id] = [
            'name' => $product_name,
            'price' => $price,
            'quantity' => $quantity
        ];
    }
}

// Function to remove an item from the cart
function remove_from_cart($product_id) {
    if (isset($_SESSION['cart'][$product_id])) {
        unset($_SESSION['cart'][$product_id]);
    }
}

// Function to update the quantity of an item in the cart
function update_cart_quantity($product_id, $quantity) {
    if (isset($_SESSION['cart'][$product_id])) {
        $_SESSION['cart'][$product_id]['quantity'] = $quantity;
    }
}

// Function to get the cart contents
function get_cart_contents() {
    return $_SESSION['cart'];
}

// Example usage:  Simulating a product ID and adding to the cart
// $product_id = 1;
// $product_name = "Awesome T-Shirt";
// $price = 25.00;
// add_to_cart($product_id, $product_name, $price);

// Example usage:  Adding multiple items
// add_to_cart(1, "Awesome T-Shirt", 25.00, 2);
// add_to_cart(2, "Cool Mug", 10.00, 3);

// Example usage: Removing an item
// remove_from_cart(2);

// Example usage:  Updating quantity
// update_cart_quantity(1, 5);


// Display the cart contents
$cart_contents = get_cart_contents();

echo "<h2>Shopping Cart</h2>";

if (empty($cart_contents)) {
    echo "<p>Your cart is empty.</p>";
} else {
    echo "<ul>";
    foreach ($cart_contents as $product_id => $product_details) {
        echo "<li>";
        echo "<strong>" . $product_details['name'] . "</strong> - $" . $product_details['price'] . " x " . $product_details['quantity'] . "<button onclick=\"removeItem('" . $product_id . "')\">Remove</button></li>";
    }
    echo "</ul>";
}


?>
