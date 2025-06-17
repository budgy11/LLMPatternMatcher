

// Example of using prepared statements to prevent SQL injection
$stmt = $conn->prepare("SELECT * FROM cart WHERE user_id = ? AND product_id = ?");
$stmt->bind_param("is", $user_id, $product_id); // 'i' for integer, 's' for string
$stmt->execute();
$result = $stmt->get_result();
// ... process the result ...
$stmt->close();


<?php

// Database Connection (Replace with your database credentials)
$host = "localhost";
$username = "your_username";
$password = "your_password";
$database = "your_database";

try {
    $conn = new PDO("mysql:host=$host;dbname=$database", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}

// Helper Function to sanitize input (Important for security)
function sanitizeInput($data) {
  $data = trim($data);
  $data = stripslashes($data);
  $data = htmlspecialchars($data);
  return $data;
}


// 1. Handle Form Submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validate Input (Crucial - Prevents security vulnerabilities)
    $product_id = sanitizeInput($_POST["product_id"]);
    $quantity = (int)sanitizeInput($_POST["quantity"]); // Cast to integer
    $customer_name = sanitizeInput($_POST["customer_name"]);
    $customer_email = sanitizeInput($_POST["customer_email"]);
    $shipping_address = sanitizeInput($_POST["shipping_address"]);

    // Check if product exists (Important)
    $stmt = $conn->prepare("SELECT id, name, price FROM products WHERE id = ?");
    $product_stmt = $stmt->execute([$product_id]);
    $product = $product_stmt->fetch(PDO::FETCH_ASSOC);

    if (!$product) {
        $error = "Product not found.";
    } elseif ($quantity <= 0) {
        $error = "Quantity must be greater than 0.";
    } else {
        // Process Order
        $total_price = $product["price"] * $quantity;

        // Prepare Order Data
        $order_data = [
            "product_id" => $product_id,
            "quantity" => $quantity,
            "customer_name" => $customer_name,
            "customer_email" => $customer_email,
            "shipping_address" => $shipping_address,
            "total_price" => $total_price,
            "order_date" => date("Y-m-d H:i:s") // Add timestamp for order date
        ];

        // Insert Order into Database
        $order_query = "INSERT INTO orders (product_id, quantity, customer_name, customer_email, shipping_address, total_price, order_date) VALUES (?, ?, ?, ?, ?, ?, ?)";
        $order_stmt = $conn->prepare($order_query);
        $order_stmt->execute([$product_id, $quantity, $customer_name, $customer_email, $shipping_address, $total_price, date("Y-m-d H:i:s")]); // Using date() for consistent timestamp

        $success = "Order placed successfully! Order ID: " . $conn->lastInsertId();  // Show the order ID

    }
}

// 2. Display the Purchase Form
?>
