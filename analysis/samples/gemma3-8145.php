
</body>
</html>


<?php
session_start();

// Database connection details (replace with your actual credentials)
$db_host = 'localhost';
$db_name = 'shop_db';
$db_user = 'your_username';
$db_password = 'your_password';

// Function to connect to the database
function connectToDatabase() {
    $conn = new mysqli($db_host, $db_user, $db_password, $db_name);
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    return $conn;
}

// Function to check if a product is in the cart
function isInCart($product_id) {
    if (isset($_SESSION['cart'][$product_id])) {
        return true;
    }
    return false;
}

// Function to update the cart
function updateCart($product_id, $quantity) {
    if (isInCart($product_id)) {
        $_SESSION['cart'][$product_id] = $quantity;
    } else {
        // Add the product to the cart
        $_SESSION['cart'][$product_id] = $quantity;
    }
}

// Function to get the cart items
function getCartItems() {
    $cart_items = array();
    foreach ($_SESSION['cart'] as $product_id => $quantity) {
        $product_info = mysqli_fetch_assoc(mysqli_query(connectToDatabase(), "SELECT * FROM products WHERE id = '$product_id'"));
        $cart_items[] = array(
            'id' => $product_info['id'],
            'name' => $product_info['name'],
            'price' => $product_info['price'],
            'quantity' => $quantity
        );
    }
    return $cart_items;
}

// Function to calculate the total price
function calculateTotalPrice() {
    $total = 0;
    foreach ($_SESSION['cart'] as $product_id => $quantity) {
        $product_info = mysqli_fetch_assoc(mysqli_query(connectToDatabase(), "SELECT * FROM products WHERE id = '$product_id'"));
        $total += $product_info['price'] * $quantity;
    }
    return $total;
}


// --- Handle Add to Cart Request ---
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_to_cart'])) {
    $product_id = $_POST['product_id'];
    $quantity = $_POST['quantity'];

    updateCart($product_id, $quantity);
    // Redirect to the cart page
    header("Location: cart.php");
    exit();
}

// --- Handle Remove from Cart Request ---
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['remove_from_cart'])) {
    $product_id = $_POST['product_id'];
    unset($_SESSION['cart'][$product_id]);
    // Redirect to the cart page
    header("Location: cart.php");
    exit();
}


// --- Display Cart Items ---
$cart_items = getCartItems();
$total_price = calculateTotalPrice();

?>
