

<?php

// Sample product data (replace with database connection)
$products = [
    1 => ['id' => 1, 'name' => 'T-Shirt', 'price' => 20.00],
    2 => ['id' => 2, 'name' => 'Jeans', 'price' => 50.00],
    3 => ['id' => 3, 'name' => 'Hat', 'price' => 15.00],
];

// Session for storing the cart
session_start();

// Initialize the cart in session if it doesn't exist
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

// Function to add an item to the cart
function add_to_cart($product_id, $_SESSION['cart']) {
    // Check if the product exists
    if (array_key_exists($product_id, $products)) {
        $product = $products[$product_id];
        if (!in_array($product['id'], $_SESSION['cart'])) { // Prevent duplicate adds
            $_SESSION['cart'][] = $product['id'];
        }
    }
}

// Function to remove an item from the cart
function remove_from_cart($product_id, $_SESSION['cart']) {
    if (in_array($product_id, $_SESSION['cart'])) {
        $_SESSION['cart'] = array_diff($_SESSION['cart'], $product_id);
    }
}

// Function to view the cart
function view_cart() {
    $cart_items = [];
    foreach ($_SESSION['cart'] as $item_id) {
        $cart_items[] = $products[$item_id] ;
    }

    if (empty($cart_items)) {
        echo "<p>Your cart is empty.</p>";
    } else {
        echo "<h2>Shopping Cart</h2>";
        echo "<ul>";
        foreach ($cart_items as $item) {
            echo "<li>" . $item['name'] . " - $" . $item['price'] . "</li>";
        }
        echo "</ul>";

        // Calculate total price
        $total_price = 0;
        foreach ($cart_items as $item) {
            $total_price += $item['price'];
        }
        echo "<p><strong>Total: $" . $total_price . "</strong></p>";
    }
}

// Handle add to cart request
if (isset($_POST['add_to_cart'])) {
    $product_id = $_POST['product_id'];
    add_to_cart($product_id, $_SESSION['cart']);
}

// Handle remove from cart request
if (isset($_POST['remove_from_cart'])) {
    $product_id = $_POST['product_id'];
    remove_from_cart($product_id, $_SESSION['cart']);
}

// Start the page
?>
