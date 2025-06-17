

<?php
session_start();

// Database Connection (Replace with your actual database credentials)
$dbHost = "localhost";
$dbName = "shopping_cart";
$dbUser = "root";
$dbPass = "";

// Create a database connection
$conn = new mysqli($dbHost, $dbUser, $dbPass, $dbName);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Function to add an item to the cart
function addToCart($conn, $product_id, $quantity) {
    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = array();
    }

    if (isset($_SESSION['cart'][$product_id])) {
        $_SESSION['cart'][$product_id]['quantity'] += $quantity;
    } else {
        $_SESSION['cart'][$product_id] = array('quantity' => $quantity);
    }
}

// Function to get all items in the cart
function getCartItems($conn) {
    $cart_items = array();
    if (isset($_SESSION['cart'])) {
        foreach ($_SESSION['cart'] as $product_id => $item) {
            // Fetch product details from the database
            $product_query = "SELECT id, name, price FROM products WHERE id = $product_id";
            $product_result = $conn->query($product_query);

            if ($product_result->num_rows > 0) {
                $product = $product_result->fetch_assoc();
                $cart_items[] = array(
                    'id' => $product['id'],
                    'name' => $product['name'],
                    'price' => $product['price'],
                    'quantity' => $item['quantity']
                );
            }
        }
    }
    return $cart_items;
}

// Function to remove an item from the cart
function removeFromCart($conn, $product_id) {
    if (isset($_SESSION['cart'][$product_id])) {
        unset($_SESSION['cart'][$product_id]);
    }
}

// Function to update the quantity of an item in the cart
function updateQuantity($conn, $product_id, $quantity) {
    if (isset($_SESSION['cart'][$product_id])) {
        $_SESSION['cart'][$product_id]['quantity'] = $quantity;
    }
}

// ---  Shopping Cart Operations  ---

// 1. Add to Cart (Handle POST request)
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['add_to_cart'])) {
    $product_id = $_POST['product_id'];
    $quantity = $_POST['quantity'];
    addToCart($conn, $product_id, $quantity);
}

// 2. Get Cart Items (Display the cart content)
$cart_items = getCartItems($conn);

// 3. Remove Item from Cart (Handle DELETE request)
if ($_SERVER["REQUEST_METHOD"] == "DELETE" && isset($_GET['remove'])) {
    $product_id = $_GET['remove'];
    removeFromCart($conn, $product_id);
}

// 4. Update Quantity (Handle PUT request)
if ($_SERVER["REQUEST_METHOD"] == "PUT" && isset($_GET['update'])) {
    $product_id = $_GET['update'];
    $quantity = $_GET['quantity'];
    updateQuantity($conn, $product_id, $quantity);
}


// --- Product Data (Simulated for demonstration) ---
// In a real application, this would come from your database.
$products = array(
    1 => array('id' => 1, 'name' => 'Laptop', 'price' => 1200),
    2 => array('id' => 2, 'name' => 'Mouse', 'price' => 25),
    3 => array('id' => 3, 'name' => 'Keyboard', 'price' => 75),
);

?>
