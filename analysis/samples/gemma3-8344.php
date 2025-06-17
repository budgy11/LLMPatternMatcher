

<?php
session_start();

// Database connection (replace with your actual details)
$db_host = "localhost";
$db_name = "shopping_cart";
$db_user = "your_username";
$db_password = "your_password";

// Create database connection
$conn = new mysqli($db_host, $db_user, $db_password, $db_name);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}


// --- Product Data (Simulated for Example) ---
$products = [
    1 => ["id" => 1, "name" => "Laptop", "price" => 1200, "quantity" => 1],
    2 => ["id" => 2, "name" => "Mouse", "price" => 25, "quantity" => 5],
    3 => ["id" => 3, "name" => "Keyboard", "price" => 75, "quantity" => 3],
];

// --- Functions ---

/**
 * Adds an item to the cart.
 * @param int $product_id
 * @param int $quantity
 */
function add_to_cart($product_id, $quantity) {
    if (isset($_SESSION['cart'])) {
        $_SESSION['cart'][$product_id] = $_SESSION['cart'][$product_id] ?? 0;
        $_SESSION['cart'][$product_id] = $_SESSION['cart'][$product_id] + $quantity;
    } else {
        $_SESSION['cart'][$product_id] = $quantity;
    }
}

/**
 * Updates the quantity of an item in the cart.
 * @param int $product_id
 * @param int $quantity
 */
function update_cart_quantity($product_id, $quantity) {
    if (isset($_SESSION['cart'][$product_id])) {
        $_SESSION['cart'][$product_id] = $quantity;
    }
}


/**
 * Removes an item from the cart.
 * @param int $product_id
 */
function remove_from_cart($product_id) {
    if (isset($_SESSION['cart'][$product_id])) {
        unset($_SESSION['cart'][$product_id]);
    }
}

/**
 * Gets the cart contents
 * @return array An array containing the cart data.
 */
function get_cart_contents() {
    return $_SESSION['cart'] ?? []; //Return empty array if session not set
}

// --- Handling Add to Cart Request ---
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["add_to_cart"])) {
    $product_id = (int)$_POST["product_id"];
    $quantity = (int)$_POST["quantity"];

    if (isset($products[$product_id])) {
        add_to_cart($product_id, $quantity);
    } else {
        echo "Product ID $product_id not found.";
    }
}


// --- Handling Update Quantity Request ---
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["update_quantity"])) {
    $product_id = (int)$_POST["product_id"];
    $quantity = (int)$_POST["quantity"];

    update_cart_quantity($product_id, $quantity);
}

// --- Handling Remove from Cart Request ---
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["remove_from_cart"])) {
    $product_id = (int)$_POST["product_id"];
    remove_from_cart($product_id);
}

// --- Display Cart Contents ---
$cart_contents = get_cart_contents();

echo "<h2>Shopping Cart</h2>";

if (empty($cart_contents)) {
    echo "<p>Your cart is empty.</p>";
} else {
    echo "<ul>";
    foreach ($cart_contents as $product_id => $item_quantity) {
        $product = $products[$product_id];
        echo "<li>" . $product['name'] . " - Quantity: " . $item_quantity . " - Price: $" . $product['price'] . "</li>";
    }
    echo "</ul>";

    // --- Calculate Total Price ---
    $total_price = 0;
    foreach ($cart_contents as $product_id => $item_quantity) {
        $product = $products[$product_id];
        $total_price += $product['price'] * $item_quantity;
    }
    echo "<p><strong>Total Price: $" . number_format($total_price, 2) . "</strong></p>";
}

?>
