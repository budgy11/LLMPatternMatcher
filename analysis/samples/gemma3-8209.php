

<?php
session_start();

// Database connection (replace with your actual database credentials)
$db_host = "localhost";
$db_user = "your_username";
$db_password = "your_password";
$db_name = "your_database";

// Connection
$conn = new mysqli($db_host, $db_user, $db_password, $db_name);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Cart variables (initialize if not already set)
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

// --- Helper Functions ---

/**
 * Add an item to the cart.
 *
 * @param int $product_id The ID of the product to add.
 * @param int $quantity The quantity of the product to add.
 */
function addToCart($product_id, $quantity) {
    if (!isset($_SESSION['cart'][$product_id])) {
        $_SESSION['cart'][$product_id] = $quantity;
    } else {
        $_SESSION['cart'][$product_id] += $quantity;
    }
}

/**
 * Get the total quantity of an item in the cart.
 *
 * @param int $product_id The ID of the product.
 * @return int The quantity of the product in the cart, or 0 if not found.
 */
function getCartItemQuantity($product_id) {
    if (isset($_SESSION['cart'][$product_id])) {
        return $_SESSION['cart'][$product_id];
    } else {
        return 0;
    }
}

/**
 * Remove an item from the cart.
 *
 * @param int $product_id The ID of the product to remove.
 */
function removeFromCart($product_id) {
    if (isset($_SESSION['cart'][$product_id])) {
        unset($_SESSION['cart'][$product_id]);
    }
}


/**
 * Calculate the total number of items in the cart.
 *
 * @return int The total number of items in the cart.
 */
function getCartTotal() {
    $total = 0;
    foreach ($_SESSION['cart'] as $product_id => $quantity) {
        $total = $total + getCartItemQuantity($product_id) * getProductPrice($product_id); // Assuming product price is available
    }
    return $total;
}

/**
 * Get the price of a product
 *
 * @param int $product_id The ID of the product
 * @return int The price of the product
 */
function getProductPrice($product_id) {
    //  Replace with your logic to retrieve product prices from the database.
    // This is just an example.  It's crucial to replace with your actual product data retrieval.
    //  You could use a database query here.
    if ($product_id == 1) {
        return 10;
    } elseif ($product_id == 2) {
        return 20;
    } else {
        return 0; // Default price or handle error appropriately
    }
}


// ---  Cart Actions based on HTTP Methods ---

// 1. GET - Display the cart contents
if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    // Output the cart contents
    echo "<h1>Shopping Cart</h1>";
    if (empty($_SESSION['cart'])) {
        echo "<p>Your cart is empty.</p>";
    } else {
        echo "<ul>";
        foreach ($_SESSION['cart'] as $product_id => $quantity) {
            $product_name = getProductName($product_id); // Assumes a function to get product name
            $total_price = getCartItemQuantity($product_id) * getProductPrice($product_id); //Calculates total price
            echo "<li>Product: " . $product_name . ", Quantity: " . $quantity . ", Total: $" . $total_price . "</li>";
        }
        echo "</ul>";
        echo "<p><strong>Total: $" . getCartTotal() . "</strong></p>";
        echo "<a href='cart.php?action=empty'>Clear Cart</a>"; // Link to empty cart
    }
}

// 2. POST - Add an item to the cart
elseif ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['product_id']) && isset($_POST['quantity'])) {
        $product_id = intval($_POST['product_id']);
        $quantity = intval($_POST['quantity']);
        addToCart($product_id, $quantity);
        // Redirect back to the cart page
        header("Location: cart.php");
        exit();
    }
}


// 3.  POST - Remove item from cart
elseif ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['remove_product_id'])) {
    $product_id = intval($_POST['remove_product_id']);
    removeFromCart($product_id);
    header("Location: cart.php");
    exit();
}



?>
