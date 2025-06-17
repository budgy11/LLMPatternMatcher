

<?php

// Database connection details (replace with your actual credentials)
$host = "localhost";
$username = "your_username";
$password = "your_password";
$database = "your_database";

// Establish database connection
$conn = new mysqli($host, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Session handling (for maintaining cart and user info)
session_start();

// Cart data (stored in session)
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

// Function to add an item to the cart
function add_to_cart($conn, $product_id, $quantity) {
    $product_id = intval($product_id); // Sanitize input
    $quantity = intval($quantity);      // Sanitize input

    if ($quantity <= 0) {
        return false; // Invalid quantity
    }

    // Check if product exists (simplified for demonstration)
    $result = $conn->query("SELECT id, name, price FROM products WHERE id = $product_id");
    if ($result->num_rows == 0) {
        return false; // Product not found
    }
    $product = $result->fetch_assoc();

    // Add to cart
    $_SESSION['cart'][] = [
        'id' => $product['id'],
        'name' => $product['name'],
        'price' => $product['price'],
        'quantity' => $quantity
    ];
    return true;
}

// Function to remove an item from the cart
function remove_from_cart($conn, $product_id) {
    $product_id = intval($product_id);

    // Iterate through the cart and remove the item
    foreach ($_SESSION['cart'] as $key => $item) {
        if ($item['id'] == $product_id) {
            unset($_SESSION['cart'][$key]);
            // Re-index the array (important after deleting)
            $_SESSION['cart'] = array_values($_SESSION['cart']);
            return true;
        }
    }
    return false;
}


// Function to update the quantity of an item in the cart
function update_quantity($conn, $product_id, $quantity) {
    $product_id = intval($product_id);
    $quantity = intval($quantity);

    if ($quantity <= 0) {
        return false; // Invalid quantity
    }

    // Find the product in the cart
    foreach ($_SESSION['cart'] as $key => $item) {
        if ($item['id'] == $product_id) {
            $_SESSION['cart'][$key]['quantity'] = $quantity;
            return true;
        }
    }

    return false;
}


// Function to calculate the total cart value
function calculate_cart_total($conn) {
    $total = 0;
    foreach ($_SESSION['cart'] as $item) {
        $result = $conn->query("SELECT price FROM products WHERE id = $item['id']");
        $product = $result->fetch_assoc();
        $total += $product['price'] * $item['quantity'];
    }
    return $total;
}

// Handle form submission (adding to cart)
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['add_to_cart'])) {
        $product_id = $_POST['product_id'];
        $quantity = $_POST['quantity'];
        add_to_cart($conn, $product_id, $quantity);
    } elseif (isset($_POST['remove_from_cart'])) {
        $product_id = $_POST['product_id'];
        remove_from_cart($conn, $product_id);
    } elseif (isset($_POST['update_quantity'])) {
        $product_id = $_POST['product_id'];
        $quantity = $_POST['quantity'];
        update_quantity($conn, $product_id, $quantity);
    }
}


// Display Cart
$cart_total = calculate_cart_total($conn);

echo "<h2>Shopping Cart</h2>";

if (empty($_SESSION['cart'])) {
    echo "<p>Your cart is empty.</p>";
} else {
    echo "<ul>";
    foreach ($_SESSION['cart'] as $item) {
        echo "<li>" . $item['name'] . " - $" . $item['price'] . " x " . $item['quantity'] . " = $" . $item['price'] * $item['quantity'] . "</li>";
    }
    echo "</ul>";
    echo "<p>Total: $" . $cart_total . "</p>";
}

?>
