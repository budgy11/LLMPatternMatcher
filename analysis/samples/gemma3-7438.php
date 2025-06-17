

<?php

// Database Connection (Replace with your actual credentials)
$host = "localhost";
$username = "your_username";
$password = "your_password";
$dbname = "your_database";

try {
    $conn = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
    die();
}

// Function to handle the purchase process
function handlePurchase($cart, $customer_name, $customer_email, $payment_method) {
    global $conn;

    // 1. Create Order Record
    $stmt = $conn->prepare("INSERT INTO orders (customer_name, customer_email, order_date, payment_method) VALUES (:customer_name, :customer_email, NOW(), :payment_method)");
    $order_id = $stmt->execute([
        ':customer_name' => $customer_name,
        ':customer_email' => $customer_email,
        ':payment_method' => $payment_method
    ]);

    $order_id = $conn->lastInsertId(); // Get the last inserted ID
    echo "Order created with ID: " . $order_id . "<br>";


    // 2. Add Order Items to Order Items Table
    foreach ($cart['items'] as $item_id => $quantity) {
        $stmt = $conn->prepare("INSERT INTO order_items (order_id, product_id, quantity) VALUES (:order_id, :product_id, :quantity)");
        $stmt->execute([
            ':order_id' => $order_id,
            ':product_id' => $item_id,
            ':quantity' => $quantity
        ]);
    }

    // 3. Update Inventory (Simple example - adjust for complex scenarios)
    foreach ($cart['items'] as $item_id => $quantity) {
        $stmt = $conn->prepare("UPDATE products SET stock = stock - :quantity WHERE id = :product_id");
        $stmt->execute([
            ':product_id' => $item_id,
            ':quantity' => $quantity
        ]);
    }

    // 4. Clear Cart (For demonstration - in a real application, you'd handle this more robustly)
    $cart['items'] = [];
    echo "Cart cleared.";
}

// --- Example Usage (Simulated Form Handling) ---

// Assuming you have a form that collects this data.  This is just an example.

// 1. Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // 2. Validate Form Data (IMPORTANT - Add robust validation here!)
    $customer_name = htmlspecialchars($_POST["customer_name"]);
    $customer_email = htmlspecialchars($_POST["customer_email"]);
    $payment_method = htmlspecialchars($_POST["payment_method"]);
    $cart = $_POST['cart'];

    if (empty($customer_name) || empty($customer_email) || empty($payment_method)) {
        echo "Error: Please fill in all fields.";
    } else {
        // 3. Handle the Purchase
        handlePurchase($cart, $customer_name, $customer_email, $payment_method);
    }
}
?>
