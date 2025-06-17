
<!-- Add some CSS for styling (example) -->
<style>
  .product-name {
    font-weight: bold;
  }
</style>


<?php

// Database connection (replace with your actual credentials)
$host = "localhost";
$username = "your_username";
$password = "your_password";
$database = "your_database";

try {
    $pdo = new PDO("mysql:host=$host;dbname=$database", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}

// Function to handle purchase processing
function processPurchase($cart, $customer_name, $customer_email, $payment_method) {
    // 1. Validate Cart Items
    foreach ($cart as $item_id => $quantity) {
        // Check if the product exists (basic validation)
        $stmt = $pdo->prepare("SELECT id, name, price FROM products WHERE id = ?");
        $stmt->execute([$item_id]);
        $product = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$product) {
            return "Error: Product ID '$item_id' not found.";
        }

        // Check if quantity is valid
        if ($quantity <= 0) {
            return "Error: Invalid quantity.";
        }
    }

    // 2. Calculate Total Amount
    $totalAmount = 0;
    foreach ($cart as $item_id => $quantity) {
        $stmt = $pdo->prepare("SELECT price FROM products WHERE id = ?");
        $stmt->execute([$item_id]);
        $product = $stmt->fetch(PDO::FETCH_ASSOC);
        $totalAmount += $product['price'] * $quantity;
    }

    // 3.  Database Insertion (Order Details)
    $order_date = date("Y-m-d H:i:s");

    $stmt = $pdo->prepare("INSERT INTO orders (customer_name, customer_email, order_date, total_amount, payment_method)
                           VALUES (?, ?, ?, ?, ?)");
    $stmt->execute([$customer_name, $customer_email, $order_date, $totalAmount, $payment_method]);

    $order_id = $pdo->lastInsertId(); // Get the ID of the newly created order

    // 4.  Database Insertion (Order Items)
    foreach ($cart as $item_id => $quantity) {
        $stmt = $pdo->prepare("INSERT INTO order_items (order_id, product_id, quantity)
                               VALUES (?, ?, ?)");
        $stmt->execute([$order_id, $item_id, $quantity]);
    }

    return "Purchase successful! Order ID: " . $order_id;
}



// --- Example Usage (GET request handling - this is a basic example) ---

// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get form data
    $customer_name = $_POST["customer_name"];
    $customer_email = $_POST["customer_email"];
    $payment_method = $_POST["payment_method"];

    // Get cart data (assuming this is passed as JSON - important!)
    $cart_json = $_POST["cart"];
    $cart = json_decode($cart_json, true); // Decode JSON into an associative array

    // Process the purchase
    $result = processPurchase($cart, $customer_name, $customer_email, $payment_method);

    // Display the result
    echo "<p>" . $result . "</p>";
}

?>
