

<?php
session_start();

// Database connection details (replace with your actual credentials)
$db_host = 'localhost';
$db_name = 'shopping_cart';
$db_user = 'your_username';
$db_password = 'your_password';

// --- Database Connection ---
$conn = new mysqli($db_host, $db_user, $db_password, $db_name);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// --- Helper Functions ---
function sanitize_input($data) {
    global $conn;
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data); // Important for security!
    return $data;
}

// --- Product Data (for demonstration - replace with your database query) ---
$products = array(
    1 => array('id' => 1, 'name' => 'T-Shirt', 'price' => 20.00, 'stock' => 10),
    2 => array('id' => 2, 'name' => 'Jeans', 'price' => 50.00, 'stock' => 5),
    3 => array('id' => 3, 'name' => 'Sneakers', 'price' => 80.00, 'stock' => 3)
);

// --- Cart Functions ---

// Initialize Cart if it doesn't exist
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = array();
}

// Add to Cart
function addToCart($product_id, $quantity) {
    global $_SESSION['cart'];

    if (isset($_SESSION['cart'][$product_id])) {
        $_SESSION['cart'][$product_id]['quantity'] += $quantity;
    } else {
        $_SESSION['cart'][$product_id] = array('quantity' => $quantity);
    }
}

// Remove from Cart
function removeFromCart($product_id) {
    if (isset($_SESSION['cart'][$product_id])) {
        unset($_SESSION['cart'][$product_id]);
    }
}

// Update Quantity in Cart
function updateQuantity($product_id, $quantity) {
    if (isset($_SESSION['cart'][$product_id])) {
        $_SESSION['cart'][$product_id]['quantity'] = $quantity;
    }
}

// Get Cart Contents
function getCartContents() {
    return $_SESSION['cart'];
}

// --- Cart Actions based on HTTP request ---

// 1. Add to Cart (GET request)
if (isset($_GET['action']) && $_GET['action'] == 'add') {
    $product_id = sanitize_input($_GET['product_id']);
    $quantity = sanitize_input($_GET['quantity']);

    if (is_numeric($product_id) && is_numeric($quantity) && $quantity > 0) {
        addToCart($product_id, $quantity);
        echo "<p>Item added to cart.</p>";
    } else {
        echo "<p>Invalid product ID or quantity.</p>";
    }
}

// 2. Remove from Cart (GET request)
if (isset($_GET['action']) && $_GET['action'] == 'remove') {
    $product_id = sanitize_input($_GET['product_id']);
    removeFromCart($product_id);
    echo "<p>Item removed from cart.</p>";
}

// 3. Update Quantity in Cart (GET request)
if (isset($_GET['action']) && $_GET['action'] == 'update') {
    $product_id = sanitize_input($_GET['product_id']);
    $quantity = sanitize_input($_GET['quantity']);

    if (is_numeric($product_id) && is_numeric($quantity) && $quantity > 0) {
        updateQuantity($product_id, $quantity);
        echo "<p>Quantity updated in cart.</p>";
    } else {
        echo "<p>Invalid product ID or quantity.</p>";
    }
}


// --- Display Cart Contents ---
?>
