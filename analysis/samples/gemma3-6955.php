</body>
</html>


<?php
session_start();

// Database Connection (Replace with your actual database details)
$dbHost = 'localhost';
$dbUser = 'your_username';
$dbPass = 'your_password';
$dbName = 'your_database';

// Function to connect to the database
function connectToDatabase() {
    $conn = new mysqli($dbHost, $dbUser, $dbPass, $dbName);
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    return $conn;
}

// Get item ID from URL
$itemId = $_GET['item_id'];

// Remove item from cart
$conn = connectToDatabase();
$sql = "DELETE FROM cart WHERE item_id = '$itemId'";

if ($conn->query($sql) === TRUE) {
    // Optionally, redirect the user to the cart page
    header("Location: cart.php"); // Assuming cart.php is the page displaying the cart
    exit();
} else {
    echo "Error: " . $conn->error;
}

?>
