

<?php

// Database connection details (replace with your actual details)
$db_host = "localhost";
$db_name = "shop";
$db_user = "your_user";
$db_password = "your_password";

// Start the session to maintain user state
session_start();

// Function to connect to the database
function connectToDatabase() {
    $conn = new mysqli($db_host, $db_user, $db_password, $db_name);
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    return $conn;
}

// Function to check if a product exists
function productExists($product_id) {
    $conn = connectToDatabase();
    $stmt = $conn->prepare("SELECT 1 FROM products WHERE id = ?");
    $stmt->bind_param("i", $product_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $exists = $result->num_rows > 0;
    $stmt->close();
    $conn->close();
    return $exists;
}


// Function to handle the purchase process
function handlePurchase($product_id, $quantity, $shipping_address) {
    // Validate input (basic - improve this for production)
    if (!is_numeric($product_id) || !is_numeric($quantity)) {
        return "Invalid product ID or quantity.";
    }

    if ($quantity <= 0) {
        return "Quantity must be greater than zero.";
    }

    // Check if the product exists
    if (!productExists($product_id)) {
        return "Product not found.";
    }


    // Database connection
    $conn = connectToDatabase();

    // Prepare the INSERT statement
    $stmt = $conn->prepare("INSERT INTO orders (product_id, quantity, shipping_address) VALUES (?, ?, ?)");

    // Bind parameters (use prepared statements to prevent SQL injection)
    $stmt->bind_param("iii", $product_id, $quantity, $shipping_address);

    // Execute the statement
    if ($stmt->execute()) {
        // Update the product quantity (assuming you have a quantity column in the products table)
        $update_stmt = $conn->prepare("UPDATE products SET quantity = quantity - ? WHERE id = ?");
        $update_stmt->bind_param("ii", $quantity, $product_id);
        $update_stmt->execute();

        return "Purchase successful!  Order ID: " . $conn->insert_id;
    } else {
        return "Purchase failed: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
}

//  Example Usage (Handle the purchase request)

// Check if a product ID is submitted in the POST request
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $product_id = $_POST["product_id"];
    $quantity = $_POST["quantity"];
    $shipping_address = $_POST["shipping_address"];

    $purchase_result = handlePurchase($product_id, $quantity, $shipping_address);
    echo $purchase_result;
}

?>
