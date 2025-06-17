

<?php
session_start();

// Database connection details (Replace with your actual credentials)
$dbHost = 'localhost';
$dbUser = 'your_username';
$dbPass = 'your_password';
$dbName = 'your_database';

// Function to connect to the database
function connectToDatabase() {
    $conn = new mysqli($dbHost, $dbUser, $dbPass, $dbName);
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    return $conn;
}

// Function to handle adding to cart
function addToCart($productId, $quantity) {
    $conn = connectToDatabase();

    // Check if product exists in the cart
    $cartKey = 'cart_' . session_id();

    if (!isset($_SESSION[$cartKey])) {
        $_SESSION[$cartKey] = array();
    }

    if (isset($_SESSION[$cartKey][$productId])) {
        $_SESSION[$cartKey][$productId] += $quantity;
    } else {
        $_SESSION[$cartKey][$productId] = $quantity;
    }

    $conn->close();
}

// Function to get the cart contents
function getCartContents() {
    $cartKey = 'cart_' . session_id();

    if (!isset($cartKey)) {
        return []; // Return an empty array if cart doesn't exist
    }

    return $_SESSION[$cartKey];
}

// Function to remove a product from the cart
function removeFromCart($productId) {
    $cartKey = 'cart_' . session_id();

    if (!isset($cartKey)) {
        return; // Cart doesn't exist
    }

    unset($_SESSION[$cartKey][$productId]);
}


// Handling Add to Cart Request
if (isset($_POST['action']) && $_POST['action'] == 'addToCart') {
    $productId = $_POST['productId'];
    $quantity = $_POST['quantity'];

    addToCart($productId, $quantity);
}

// Handling Remove from Cart Request
if (isset($_POST['action']) && $_POST['action'] == 'removeFromCart') {
    $productId = $_POST['productId'];
    removeFromCart($productId);
}


// Displaying the Cart Contents
$cart = getCartContents();

$total = 0;
foreach ($cart as $productId => $quantity) {
    // Get product details from the database
    $product = getProductDetails($productId);

    if ($product) {
        $total += $product['price'] * $quantity;
    }
}

?>
