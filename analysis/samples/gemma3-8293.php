

<?php
session_start();

// Database connection (Replace with your actual database credentials)
$dbHost = "localhost";
$dbUser = "your_username";
$dbPass = "your_password";
$dbName = "ecommerce_db";

// Database connection
$conn = new mysqli($dbHost, $dbUser, $dbPass, $dbName);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Cart Initialization - Check if cart exists in session
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

// --- Helper Functions ---

// Add item to cart
function addToCart($conn, $product_id, $quantity) {
    $product_id = $conn->real_escape_string($product_id);  //Escape string
    $quantity = $conn->real_escape_string($quantity);

    if (isset($_SESSION['cart'][$product_id])) {
        $_SESSION['cart'][$product_id]['quantity'] += $quantity;
    } else {
        $_SESSION['cart'][$product_id] = ['quantity' => $quantity];
    }
}

// Update item quantity in cart
function updateCartQuantity($conn, $product_id, $quantity) {
    $product_id = $conn->real_escape_string($product_id);
    $quantity = $conn->real_escape_string($quantity);

    if (isset($_SESSION['cart'][$product_id])) {
        $_SESSION['cart'][$product_id]['quantity'] = $quantity;
    } else {
        // Handle case where product is no longer in cart (e.g., delete)
        //  You might want to log this or take another action.
        // For this example, we'll just do nothing.
        return false; // Indicate failure
    }
    return true; // Indicate success
}

// Remove item from cart
function removeCartItem($conn, $product_id) {
    $product_id = $conn->real_escape_string($product_id);
    if (isset($_SESSION['cart'][$product_id])) {
        unset($_SESSION['cart'][$product_id]);
    }
}

// Get cart items
function getCartItems($conn) {
    $items = [];
    foreach ($_SESSION['cart'] as $product_id => $item) {
        $item_data = getProductDetails($conn, $product_id); //Fetch product details
        if ($item_data) {
           $item_data['quantity'] = $item_data['quantity'];
           $items[] = $item_data;
        }
    }
    return $items;
}

// Fetch product details from the database
function getProductDetails($conn, $product_id) {
    $product_id = $conn->real_escape_string($product_id);

    $query = "SELECT id, name, price, image FROM products WHERE id = $product_id";
    $result = $conn->query($query);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        return $row;
    }
    return null;
}

// Calculate total cart value
function calculateTotal($conn) {
    $total = 0;
    foreach ($_SESSION['cart'] as $product_id => $item) {
        $product_data = getProductDetails($conn, $product_id);
        if($product_data) {
            $total += $product_data['price'] * $item['quantity'];
        }
    }
    return $total;
}

// --- Cart Functions (Called from the form) ---

// Add to Cart
if (isset($_POST['add_to_cart'])) {
    $product_id = $_POST['product_id'];
    $quantity = $_POST['quantity'];
    addToCart($conn, $product_id, $quantity);
}

// Update Quantity
if (isset($_POST['update_quantity'])) {
    $product_id = $_POST['product_id'];
    $quantity = $_POST['quantity'];
    updateCartQuantity($conn, $product_id, $quantity);
}

// Remove from Cart
if (isset($_POST['remove_from_cart'])) {
    $product_id = $_POST['product_id'];
    removeCartItem($conn, $product_id);
}


// --- Display Cart ---

// Get cart items
$cart_items = getCartItems($conn);

// Calculate total
$total = calculateTotal($conn);

?>
