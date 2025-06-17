

<?php

// Database connection (Replace with your database details)
$host = "localhost";
$username = "your_db_username";
$password = "your_db_password";
$database = "your_db_name";

try {
    $conn = new PDO("mysql:host=$host;dbname=$database", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}

// Function to handle purchase creation
function createPurchase($cart_id, $customer_name, $customer_email, $address, $total_amount) {
    global $conn;

    try {
        // Prepare the SQL query
        $stmt = $conn->prepare("INSERT INTO purchases (cart_id, customer_name, customer_email, address, total_amount, purchase_date) VALUES (?, ?, ?, ?, ?, NOW())");

        // Bind parameters
        $stmt->bindParam(1, $cart_id);
        $stmt->bindParam(2, $customer_name);
        $stmt->bindParam(3, $customer_email);
        $stmt->bindParam(4, $address);
        $stmt->bindParam(5, $total_amount);

        // Execute the query
        $stmt->execute();

        return true; // Success
    } catch(PDOException $e) {
        error_log("Database error creating purchase: " . $e->getMessage());  // Log the error for debugging
        return false; // Failure
    }
}

// --- Example Usage (This part demonstrates how you might use the functionality) ---

// 1.  Get cart ID from a form or session
//  (This would typically come from a form submission)
$cart_id = isset($_POST['cart_id']) ? $_POST['cart_id'] : null;  // Example:  Retrieve from a form

if ($cart_id !== null && $cart_id !== "") { //validate that $cart_id is not empty or null
    // 2. Get customer details from a form
    $customer_name = isset($_POST['customer_name']) ? $_POST['customer_name'] : "";
    $customer_email = isset($_POST['customer_email']) ? $_POST['customer_email'] : "";
    $address = isset($_POST['address']) ? $_POST['address'] : "";
    $total_amount = isset($_POST['total_amount']) ? $_POST['total_amount'] : 0.00; // Default to 0 if not provided


    // 3. Create the purchase
    if (createPurchase($cart_id, $customer_name, $customer_email, $address, $total_amount)) {
        echo "Purchase created successfully!  Purchase ID: " . $conn->lastInsertId();  // Display the new purchase ID.
    } else {
        echo "Error creating purchase. Please try again later.";
    }
} else {
    echo "Invalid cart ID. Please provide a valid cart ID.";
}


// --- Database Schema (Example) ---
/*
CREATE TABLE purchases (
    purchase_id INT AUTO_INCREMENT PRIMARY KEY,
    cart_id INT NOT NULL,
    customer_name VARCHAR(255) NOT NULL,
    customer_email VARCHAR(255) NOT NULL,
    address TEXT NOT NULL,
    total_amount DECIMAL(10, 2) NOT NULL,
    purchase_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
*/
?>
