
</body>
</html>


<?php

// 1. Database Connection (Replace with your database details)
$host = "localhost";
$username = "your_username";
$password = "your_password";
$database = "your_database";

$conn = new mysqli($host, $username, $password, $database);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// 2. Product Information (Example Data - Replace with your product data)
$products = [
    1 => ['id' => 1, 'name' => 'Laptop', 'price' => 1200],
    2 => ['id' => 2, 'name' => 'Mouse', 'price' => 25],
    3 => ['id' => 3, 'name' => 'Keyboard', 'price' => 75],
];

// 3. Handle Form Submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $productId = $_POST["product_id"];
    $quantity = $_POST["quantity"];

    // Validate Input (Crucial for security)
    if (!isset($products[$productId])) {
        $errorMessage = "Product ID not found.";
    } elseif (empty($quantity) || !is_numeric($quantity)) {
        $errorMessage = "Quantity must be a number.";
    } elseif ($quantity <= 0) {
        $errorMessage = "Quantity must be greater than zero.";
    } else {
        // Process the purchase
        $totalAmount = $products[$productId]['price'] * $quantity;

        // Display Confirmation Message
        $successMessage = "Order placed successfully! Total: $" . $totalAmount;
    }
}

// 4. Display the Purchase Form
?>
