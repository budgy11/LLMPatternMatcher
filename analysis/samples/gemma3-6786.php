

// Example data (replace with actual user/product IDs)
$userId = 123;
$productIds = [
    1 => 2, // Product ID 1, quantity 2
    2 => 5, // Product ID 2, quantity 5
];
$quantities = $productIds; //Same keys as productIds

// Add items to the cart
addCartItem($conn, $userId, 1, 3);
addCartItem($conn, $userId, 2, 1);

// Complete the purchase
if (completePurchase($conn, $userId, $productIds, $quantities)) {
    echo "Purchase completed successfully!";
} else {
    echo "Purchase failed.";
}

// Close the database connection (important!)
$conn->close();


<?php
session_start();

// Database connection (replace with your actual database credentials)
$db_host = "localhost";
$db_user = "your_username";
$db_password = "your_password";
$db_name = "your_database";

// Check if the cart is empty or not
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

// Function to add an item to the cart
function add_to_cart($item_id, $item_name, $price, $quantity = 1) {
    if (!isset($_SESSION['cart'][$item_id])) {
        $_SESSION['cart'][$item_id] = ['name' => $item_name, 'price' => $price, 'quantity' => $quantity];
    } else {
        $_SESSION['cart'][$item_id]['quantity'] += $quantity;
    }
}

// Function to update cart item quantity
function update_cart_quantity($item_id, $quantity) {
    if (isset($_SESSION['cart'][$item_id])) {
        $_SESSION['cart'][$item_id]['quantity'] = $quantity;
    }
}


// Function to remove item from cart
function remove_from_cart($item_id) {
    if (isset($_SESSION['cart'][$item_id])) {
        unset($_SESSION['cart'][$item_id]);
    }
}


// Function to get cart items
function get_cart_items() {
    return $_SESSION['cart'];
}

// Function to calculate cart total
function calculate_cart_total() {
    $total = 0;
    foreach ($_SESSION['cart'] as $item) {
        $total += $item['price'] * $item['quantity'];
    }
    return $total;
}

// Handle add to cart action
if (isset($_POST['add_to_cart'])) {
    $item_id = $_POST['item_id'];
    $item_name = $_POST['item_name'];
    $price = $_POST['price'];
    $quantity = isset($_POST['quantity']) ? $_POST['quantity'] : 1;
    add_to_cart($item_id, $item_name, $price, $quantity);
}

// Handle update quantity action
if (isset($_POST['update_quantity'])) {
  $item_id = $_POST['item_id'];
  $quantity = isset($_POST['quantity']) ? $_POST['quantity'] : 0;
  update_cart_quantity($item_id, $quantity);
}

// Handle remove from cart action
if (isset($_POST['remove_from_cart'])) {
    $item_id = $_POST['item_id'];
    remove_from_cart($item_id);
}

// Get cart items to display
$cart_items = get_cart_items();

// Calculate cart total
$total = calculate_cart_total();

?>
