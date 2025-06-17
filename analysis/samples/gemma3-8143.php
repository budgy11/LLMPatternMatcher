

<?php
session_start();

// Database connection (Replace with your actual database credentials)
$db_host = 'localhost';
$db_user = 'your_username';
$db_password = 'your_password';
$db_name = 'your_database_name';

// ------------------------------------------------------------------
// Helper Functions
// ------------------------------------------------------------------

// Function to connect to the database
function connectDB() {
    $conn = new mysqli($db_host, $db_user, $db_password, $db_name);
    if ($conn->connect_error) {
        die('Connection failed: ' . $conn->connect_error);
    }
    return $conn;
}

// Function to sanitize input (basic example, enhance for production)
function sanitizeInput($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}


// ------------------------------------------------------------------
// Cart Management Functions
// ------------------------------------------------------------------

// Function to initialize the cart
function initializeCart() {
    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = [];
    }
}

// Function to add an item to the cart
function addItemToCart($product_id, $name, $price, $quantity) {
    initializeCart();

    $item = [
        'product_id' => $product_id,
        'name' => $name,
        'price' => $price,
        'quantity' => $quantity
    ];

    $_SESSION['cart'][] = $item;
}

// Function to update the quantity of an item in the cart
function updateQuantity($product_id, $quantity) {
    initializeCart();
    
    // Find the index of the item to update
    $index = -1;
    for ($i = 0; $i < count($_SESSION['cart']); $i++) {
        if ($_SESSION['cart'][$i]['product_id'] == $product_id) {
            $index = $i;
            break;
        }
    }

    if ($index !== -1) {
        $_SESSION['cart'][$index]['quantity'] = $quantity;
    }
}

// Function to remove an item from the cart
function removeItemFromCart($product_id) {
    initializeCart();
    
    $key = array_search($product_id, $_SESSION['cart']);
    if ($key !== false) {
        unset($_SESSION['cart'][$key]);
        // Optionally, re-index the array to prevent gaps
        $_SESSION['cart'] = array_values($_SESSION['cart']);
    }
}

// Function to get the cart total
function getCartTotal() {
    initializeCart();
    $total = 0;
    foreach ($_SESSION['cart'] as $item) {
        $total += $item['price'] * $item['quantity'];
    }
    return $total;
}

// Function to get the cart items
function getCartItems() {
    initializeCart();
    return $_SESSION['cart'];
}

// ------------------------------------------------------------------
// Cart Handling based on user request
// ------------------------------------------------------------------

// Example: Handle adding to cart (replace with your actual form handling)
if (isset($_POST['add_to_cart'])) {
    $product_id = sanitizeInput($_POST['product_id']);
    $name = sanitizeInput($_POST['name']);
    $price = sanitizeInput($_POST['price']);
    $quantity = sanitizeInput($_POST['quantity']);

    addItemToCart($product_id, $name, $price, $quantity);
}

// Example: Handle updating quantity
if (isset($_POST['update_quantity'])) {
    $product_id = sanitizeInput($_POST['product_id']);
    $quantity = sanitizeInput($_POST['quantity']);

    updateQuantity($product_id, $quantity);
}

// Example: Handle removing an item
if (isset($_POST['remove_item'])) {
    $product_id = sanitizeInput($_POST['product_id']);
    removeItemFromCart($product_id);
}

// ------------------------------------------------------------------
// Display Cart Function
// ------------------------------------------------------------------

function displayCart() {
    echo '<h2>Your Shopping Cart</h2>';

    if (empty($_SESSION['cart'])) {
        echo '<p>Your cart is empty.</p>';
        return;
    }

    echo '<table border="1">';
    echo '<thead><tr><th>Product Name</th><th>Price</th><th>Quantity</th><th>Total</th><th>Action</th></tr></thead>';
    echo '<tbody>';

    $cartItems = getCartItems();

    foreach ($cartItems as $item) {
        $total = $item['price'] * $item['quantity'];
        echo '<tr>';
        echo '<td>' . $item['name'] . '</td>';
        echo '<td>' . $item['price'] . '</td>';
        echo '<td>' . $item['quantity'] . '</td>';
        echo '<td>' . $total . '</td>';
        echo '<td><a href="?action=remove&product_id=' . $item['product_id'] . '">Remove</a></td>';
        echo '</tr>';
    }

    echo '</tbody>';
    echo '</table>';
    echo '<br>';
    echo '<strong>Total: ' . getCartTotal() . '</strong>';
}

// ------------------------------------------------------------------
// Output
// ------------------------------------------------------------------

?>
