

<?php
session_start();

// --- Database Connection (Replace with your database details) ---
$db_host = "localhost";
$db_name = "ecommerce";
$db_user = "root";
$db_password = "";

// Create a database connection
$conn = new mysqli($db_host, $db_user, $db_password, $db_name);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// --- Product Data (Example) ---
$products = [
    1 => ["id" => 1, "name" => "Laptop", "price" => 1200],
    2 => ["id" => 2, "name" => "Mouse", "price" => 25],
    3 => ["id" => 3, "name" => "Keyboard", "price" => 75],
];


// --- Purchase Functionality ---

function handlePurchase($cart, $conn) {
    $total = 0;
    $order_items = [];

    foreach ($cart as $product_id => $quantity) {
        if (isset($products[$product_id])) {
            $product = $products[$product_id];
            $item_price = $product['price'] * $quantity;
            $total += $item_price;
            $order_items[] = [
                "product_id" => $product_id,
                "name" => $product['name'],
                "quantity" => $quantity,
                "price" => $product['price'],
                "total" => $item_price,
            ];
        } else {
            // Product not found - Handle this case (e.g., display an error message)
            return false; // Or throw an exception
        }
    }

    // Check if the cart is empty
    if (empty($cart)) {
        return false;
    }

    // Save the order to the database
    $order_id = $conn->insert("orders", [
        "total_amount" => $total,
        "created_at" => date("Y-m-d H:i:s"),
    ]);


    $conn->insert("order_items", [
        "order_id" => $order_id,
        "product_id" => json_encode($order_items), // Store as JSON to preserve the items
        "quantity" => json_encode($order_items), // Can use for future retrieval
        "total" => json_encode($order_items),
    ]);


    // Clear the cart (you can also persist this in a session)
    $_SESSION['cart'] = [];

    return true;
}

// ---  Purchase Handling (Example) ---

// Check if the "purchase" form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sanitize and Validate input (IMPORTANT!)
    $cart = $_SESSION['cart'];
    if (handlePurchase($cart, $conn) === true) {
        echo "<p>Order placed successfully!  Order ID: " . $conn->insert_id . "</p>"; // Show order ID
        // You might want to redirect to a confirmation page.
    } else {
        echo "<p>An error occurred during the purchase.</p>";
    }
}

// --- Display the Cart (for the shopping page) ---

// Display the cart contents
echo "<h2>Shopping Cart</h2>";
if (isset($_SESSION['cart']) && !empty($_SESSION['cart'])) {
    echo "<ul>";
    foreach ($_SESSION['cart'] as $product_id => $quantity) {
        if (isset($products[$product_id])) {
            $product = $products[$product_id];
            echo "<li>" . $product['name'] . " - $" . $product['price'] . " x " . $quantity . " = $" . ($product['price'] * $quantity) . "</li>";
        }
    }
    echo "</ul>";
} else {
    echo "<p>Your cart is empty.</p>";
}
?>
