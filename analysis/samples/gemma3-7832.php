
  <form method="post" action="">
    <input type="submit" value="Complete Purchase">
  </form>

</body>
</html>


<?php

// Database connection (Replace with your actual credentials)
$servername = "localhost";
$username = "your_username";
$password = "your_password";
$dbname = "your_database";

try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}


// Function to handle the purchase process
function handlePurchase($orderData) {
    global $conn;

    // Validate data (Essential for security)
    if (empty($orderData['product_id']) || empty($orderData['quantity']) || empty($orderData['customer_name']) || empty($orderData['customer_email'])) {
        return "Error: Missing required fields.";
    }
    
    // Sanitize data (Important to prevent SQL Injection)
    $product_id = $conn->real_escape_string($orderData['product_id']);
    $quantity = $conn->real_escape_string($orderData['quantity']);
    $customer_name = $conn->real_escape_string($orderData['customer_name']);
    $customer_email = $conn->real_escape_string($orderData['customer_email']);


    // Check if the product exists
    $stmt = $conn->prepare("SELECT id, price FROM products WHERE id = ?");
    $stmt->execute([$product_id]);
    $product = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$product) {
        return "Error: Product ID not found.";
    }

    // Calculate the total price
    $total_price = $product['price'] * $quantity;

    // Insert the order into the database
    $stmt = $conn->prepare("INSERT INTO orders (product_id, quantity, customer_name, customer_email, total_price, order_date) VALUES (?, ?, ?, ?, ?, NOW())");
    $stmt->execute([$product_id, $quantity, $customer_name, $customer_email, $total_price]);

    return "Order placed successfully! Order ID: " . $conn->lastInsertId();
}

// --- Example Usage (For demonstration purposes - this will be in a form submission) ---

// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $orderData = [];
    if (isset($_POST['product_id'])) {
        $orderData['product_id'] = $_POST['product_id'];
    }
    if (isset($_POST['quantity'])) {
        $orderData['quantity'] = $_POST['quantity'];
    }
    if (isset($_POST['customer_name'])) {
        $orderData['customer_name'] = $_POST['customer_name'];
    }
    if (isset($_POST['customer_email'])) {
        $orderData['customer_email'] = $_POST['customer_email'];
    }

    $result = handlePurchase($orderData);
    echo $result;
}
?>
