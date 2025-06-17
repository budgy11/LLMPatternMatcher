

<?php
session_start();

// Database connection details (Replace with your actual details)
$db_host = 'localhost';
$db_user = 'your_username';
$db_password = 'your_password';
$db_name = 'your_database_name';

// Initialize the cart
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

// --- Helper Functions ---

// Function to add an item to the cart
function addToCart($conn, $product_id, $quantity) {
    $product_id = intval($product_id); // Ensure product_id is an integer
    $quantity = intval($quantity); // Ensure quantity is an integer

    if ($product_id <= 0 || $quantity <= 0) {
        return false; // Invalid input
    }

    // Check if the product exists in the database (Simple example - enhance for real use)
    $query = "SELECT id, name, price FROM products WHERE id = $product_id";
    $result = mysqli_query($conn, $query);
    if (!$result) {
        return false; // Product not found
    }

    $product_data = mysqli_fetch_assoc($result);
    if ($product_data['id'] == 0) { // Check if any data was retrieved
        return false;
    }

    if (isset($_SESSION['cart'][$product_id])) {
        $_SESSION['cart'][$product_id]['quantity'] += $quantity;
    } else {
        $_SESSION['cart'][$product_id] = [
            'quantity' => $quantity,
            'name' => $product_data['name'],
            'price' => $product_data['price']
        ];
    }

    return true;
}


// Function to get the cart total
function calculateCartTotal($cart) {
    $total = 0;
    foreach ($cart as $item) {
        $total += $item['price'] * $item['quantity'];
    }
    return $total;
}

// Function to clear the cart
function clearCart() {
    unset($_SESSION['cart']);
}

// --- Product Listing (Example - Replace with your product database query) ---
$product_list = [
    ['id' => 1, 'name' => 'Laptop', 'price' => 1200],
    ['id' => 2, 'name' => 'Mouse', 'price' => 25],
    ['id' => 3, 'name' => 'Keyboard', 'price' => 75],
];

// --- Cart Handling Functions ---

// Function to display the cart contents
function displayCart() {
    $cart = $_SESSION['cart'];
    if (empty($cart)) {
        echo "<p>Your cart is empty.</p>";
        return;
    }

    echo "<h2>Shopping Cart</h2>";
    echo "<table border='1'>";
    echo "<tr><th>Product Name</th><th>Price</th><th>Quantity</th><th>Total</th><th>Action</th></tr>";

    foreach ($cart as $product_id => $item) {
        $name = $item['name'];
        $price = $item['price'];
        $quantity = $item['quantity'];
        $total = $price * $quantity;

        echo "<tr>";
        echo "<td>" . $name . "</td>";
        echo "<td>$" . number_format($price, 2) . "</td>";
        echo "<td>" . $quantity . "</td>";
        echo "<td>$" . number_format($total, 2) . "</td>";
        echo "<td><a href='cart.php?action=remove&product_id=$product_id'>Remove</a></td>";
        echo "</tr>";
    }

    echo "</table>";
    echo "<p><strong>Total: $" . number_format(calculateCartTotal($cart), 2) . "</p>";
}

// --- Actions Based on User Input ---

if (isset($_GET['action']) && $_GET['action'] == 'remove') {
    $product_id = isset($_GET['product_id']) ? intval($_GET['product_id']) : 0; // Ensure product_id is an integer
    if (addToCart($conn, $product_id, 0) === true) { // Add quantity 0 to effectively remove
        unset($_SESSION['cart'][$product_id]);
    }
}


// --- Display the Cart and the Product List ---

// Establish database connection
$conn = mysqli_connect($db_host, $db_user, $db_password, $db_name);
if (!$conn) {
    die('Connection failed: ' . mysqli_connect_error());
}

?>
