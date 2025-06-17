
  <a href="index.php">Back to Products</a>

</body>
</html>


<?php

// Assuming you have a database connection established (e.g., $db)
// You'll need to adapt this to your specific database connection.
// This example uses mysqli but you can easily adapt to PDO or other libraries.

//  Example database connection (replace with your actual details)
$host = "localhost";
$username = "your_username";
$password = "your_password";
$database = "your_database";

$db = new mysqli($host, $username, $password, $database);

if ($db->connect_error) {
    die("Connection failed: " . $db->connect_error);
}

// --- Product Data (Simulated for demonstration) ---
$products = [
    1 => ["id" => 1, "name" => "Laptop", "price" => 1200],
    2 => ["id" => 2, "name" => "Mouse", "price" => 25],
    3 => ["id" => 3, "name" => "Keyboard", "price" => 75],
];


// --- Purchase Functionality ---

function placeOrder($productId, $quantity) {
    global $db, $products; // Access the global database connection and products array

    // Validate inputs (very important!)
    if (!is_numeric($productId)) {
        return "Invalid product ID.";
    }
    if (!is_numeric($quantity) || $quantity <= 0) {
        return "Invalid quantity.";
    }

    // Get product details
    $product = $products[$productId];

    if (!$product) {
        return "Product not found.";
    }

    // Calculate the total price
    $totalPrice = $product['price'] * $quantity;

    // ---  Simulate Order Processing (Replace with actual database logic) ---
    // In a real application, you would:
    // 1. Insert the order into a database table.
    // 2. Update the product quantity in the database.
    // 3.  Handle payment processing (e.g., integrate with a payment gateway).

    // For this example, we'll just return a confirmation message.
    return "Order placed successfully! Product: " . $product['name'] . ", Quantity: " . $quantity . ", Total: $" . $totalPrice;
}

// --- Handling Form Submission ---
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $productId = $_POST["product_id"];
    $quantity = $_POST["quantity"];

    $orderConfirmation = placeOrder($productId, $quantity);
    echo "<br>" . $orderConfirmation;
}

?>
