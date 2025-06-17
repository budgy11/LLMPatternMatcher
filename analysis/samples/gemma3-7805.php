

<?php
session_start();

// Database connection details (Replace with your actual details)
$db_host = "localhost";
$db_name = "shop";
$db_user = "root";
$db_pass = "";

// Function to connect to the database
function connectToDatabase() {
    $conn = new mysqli($db_host, $db_user, $db_pass, $db_name);
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    return $conn;
}

// Function to add a product to the cart
function addToCart($product_id, $quantity) {
    $conn = connectToDatabase();

    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = array();
    }

    // Check if the product is already in the cart
    $product_exists = false;
    foreach ($_SESSION['cart'] as $key => $item) {
        if ($item['product_id'] == $product_id) {
            $item['quantity'] += $quantity;
            $item['price'] = $item['price']; // No changes to price
            $product_exists = true;
            break;
        }
    }

    // If the product is not in the cart, add it
    if (!$product_exists) {
        $_SESSION['cart'][$product_id] = array(
            'product_id' => $product_id,
            'quantity' => $quantity,
            'price' => $product_id // Assuming product price is stored in product table.  Adjust if needed.
        );
    }

    $conn->close();
}

// Function to get the cart contents
function getCartContents() {
    if (isset($_SESSION['cart'])) {
        return $_SESSION['cart'];
    } else {
        return array();
    }
}

// Function to update the cart (e.g., change quantity)
function updateCartItem($product_id, $new_quantity) {
    $conn = connectToDatabase();

    if (isset($_SESSION['cart']) && isset($_SESSION['cart'][$product_id])) {
        // Validate quantity
        if ($new_quantity > 0) {
          $_SESSION['cart'][$product_id]['quantity'] = $new_quantity;
        } else {
          // You might want to remove the item entirely if quantity is invalid
          unset($_SESSION['cart'][$product_id]);
        }
    }

    $conn->close();
}

// Function to remove an item from the cart
function removeItemFromCart($product_id) {
    $conn = connectToDatabase();

    if (isset($_SESSION['cart']) && isset($_SESSION['cart'][$product_id])) {
        unset($_SESSION['cart'][$product_id]);
    }

    $conn->close();
}

// Function to calculate the total cart value
function calculateTotal() {
    $total = 0;
    $cart = getCartContents();
    foreach ($cart as $item) {
        $total += $item['quantity'] * $item['price'];
    }
    return $total;
}

// Handle Add to Cart functionality
if (isset($_POST['add_to_cart'])) {
    $product_id = $_POST['product_id'];
    $quantity = $_POST['quantity'];
    addToCart($product_id, $quantity);
}

// Handle Update Cart Item functionality
if (isset($_POST['update_cart'])) {
    $product_id = $_POST['product_id'];
    $new_quantity = $_POST['quantity'];
    updateCartItem($product_id, $new_quantity);
}

// Handle Remove Item functionality
if (isset($_POST['remove_from_cart'])) {
    $product_id = $_POST['product_id'];
    removeItemFromCart($product_id);
}

// Display the Cart
$cart_items = getCartContents();
$total = calculateTotal();
?>
