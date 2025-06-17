

<?php

// Database configuration (Replace with your actual database details)
$dbHost = 'localhost';
$dbName = 'e_commerce_db';
$dbUser = 'your_user';
$dbPass = 'your_password';

// Function to connect to the database
function connectToDatabase() {
    $conn = new mysqli($dbHost, $dbUser, $dbPass, $dbName);

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    return $conn;
}

// Function to add a purchase to the database
function addPurchase($userId, $productId, $quantity) {
    $conn = connectToDatabase();

    // Prepare the SQL statement
    $sql = "INSERT INTO purchases (user_id, product_id, quantity, purchase_date) 
            VALUES (
                '$userId', 
                '$productId', 
                '$quantity',
                NOW()  // Get the current date and time
            )";

    if ($conn->query($sql) === TRUE) {
        return true;
    } else {
        return false;
    }
}

// Function to get all purchases for a user
function getPurchasesByUser($userId) {
    $conn = connectToDatabase();

    $sql = "SELECT * FROM purchases WHERE user_id = '$userId'";

    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        return $result;
    } else {
        return false;
    }
}


// --- Example Usage (Handle Form Submission) ---

// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get user ID from the form
    $userId = $_POST["user_id"];

    // Get product ID and quantity from the form
    $productId = $_POST["product_id"];
    $quantity = $_POST["quantity"];

    // Validate inputs (Important for security)
    if (empty($userId) || empty($productId) || empty($quantity)) {
        echo "<p>Error: All fields are required.</p>";
    } elseif (!is_numeric($quantity)) {
        echo "<p>Error: Quantity must be a number.</p>";
    } else {
        // Add the purchase to the database
        if (addPurchase($userId, $productId, $quantity)) {
            echo "<p>Purchase added successfully!</p>";
        } else {
            echo "<p>Error adding purchase.</p>";
        }
    }
}

// --- Display Form ---
?>
