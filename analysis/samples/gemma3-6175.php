

<?php
session_start();

// Initialize the cart if it doesn't exist
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = array();
}

// --- Functions to manage the cart ---

// Add an item to the cart
function add_to_cart($product_id, $product_name, $price, $quantity = 1) {
    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = array();
    }

    // Check if the product is already in the cart
    if (isset($_SESSION['cart'][$product_id])) {
        // Increment quantity if already in cart
        $_SESSION['cart'][$product_id]['quantity'] += $quantity;
    } else {
        // Add the product to the cart
        $_SESSION['cart'][$product_id] = array(
            'name' => $product_name,
            'price' => $price,
            'quantity' => $quantity
        );
    }
}

// Update the quantity of an item in the cart
function update_cart_quantity($product_id, $quantity) {
    if (isset($_SESSION['cart'][$product_id])) {
        $_SESSION['cart'][$product_id]['quantity'] = $quantity;
    }
}

// Remove an item from the cart
function remove_from_cart($product_id) {
    if (isset($_SESSION['cart'][$product_id])) {
        unset($_SESSION['cart'][$product_id]);
    }
}

// Get the items in the cart
function get_cart_items() {
    return $_SESSION['cart'];
}

// Calculate the total price of the cart
function calculate_cart_total() {
    $total = 0;
    foreach ($_SESSION['cart'] as $item) {
        $total = $total + ($item['price'] * $item['quantity']);
    }
    return $total;
}

// --- Example Usage (Illustrative) ---

// Example: Adding an item to the cart
add_to_cart(1, "Laptop", 1200, 1); // product_id, product_name, price, quantity
add_to_cart(2, "Mouse", 25, 2);

// Example: Updating the quantity of a product
update_cart_quantity(1, 3); // Increase the quantity of product 1 to 3

// Example: Removing an item
// remove_from_cart(2);


// Example: Displaying the cart contents
echo "<h2>Your Cart</h2>";
if (empty($_SESSION['cart'])) {
    echo "<p>Your cart is empty.</p>";
} else {
    echo "<ul>";
    foreach ($_SESSION['cart'] as $product_id => $item) {
        echo "<li>" . $item['name'] . " - $" . $item['price'] . " x " . $item['quantity'] . " = $" . ($item['price'] * $item['quantity']) . "</li>";
    }
    echo "</ul>";
    echo "<p>Total: $" . calculate_cart_total() . "</p>";
}


?>
