
  <a href="checkout.php">Checkout</a>  <!--  Link to checkout page -->

</body>
</html>


<?php
session_start();

// Database connection details (Replace with your actual credentials)
$dbHost = "localhost";
$dbUser = "your_username";
$dbPass = "your_password";
$dbName = "shopping_cart";

// Check if the connection is successful
$conn = new mysqli($dbHost, $dbUser, $dbPass, $dbName);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// ------------------------------------------------------------------
// Product Data (Simulated Database for Example)
// In a real application, you'd retrieve this from a database.
$products = [
    1 => ['id' => 1, 'name' => 'T-Shirt', 'price' => 20],
    2 => ['id' => 2, 'name' => 'Jeans', 'price' => 50],
    3 => ['id' => 3, 'name' => 'Shoes', 'price' => 80],
];

// ------------------------------------------------------------------
// Functions
// ------------------------------------------------------------------

// Add to Cart Function
function addToCart($productId, $quantity = 1) {
    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = [];
    }

    if (isset($_SESSION['cart'][$productId])) {
        $_SESSION['cart'][$productId]['quantity'] += $quantity;
    } else {
        $_SESSION['cart'][$productId] = ['quantity' => $quantity];
    }
}

// Remove from Cart Function
function removeFromCart($productId) {
    if (isset($_SESSION['cart'][$productId])) {
        unset($_SESSION['cart'][$productId]);
    }
}

// Get Cart Items
function getCartItems() {
    return $_SESSION['cart'];
}

// Calculate Total Cart Value
function calculateTotal() {
    $total = 0;
    if (isset($_SESSION['cart'])) {
        foreach ($_SESSION['cart'] as $item) {
            $totalItemPrice = $item['price'] * $item['quantity'];
            $total = $total + $totalItemPrice;
        }
    }
    return $total;
}


// ------------------------------------------------------------------
// Handle Actions (Adding to Cart, etc.)
// ------------------------------------------------------------------

if (isset($_POST['action']) && $_POST['action'] == 'add_to_cart') {
    $productId = $_POST['product_id'];
    $quantity = isset($_POST['quantity']) ? $_POST['quantity'] : 1;
    addToCart($productId, $quantity);
}

if (isset($_POST['action']) && $_POST['action'] == 'remove_from_cart') {
    $productId = $_POST['product_id'];
    removeFromCart($productId);
}


// ------------------------------------------------------------------
// Display Cart Page
// ------------------------------------------------------------------
?>
