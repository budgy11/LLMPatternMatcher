    <label for="product_id">Product ID:</label>
    <input type="number" id="product_id" name="product_id" required><br><br>

    <label for="quantity">Quantity:</label>
    <input type="number" id="quantity" name="quantity" required><br><br>

    <label for="user_id">User ID:</label>
    <input type="number" id="user_id" name="user_id" required><br><br>

    <input type="submit" value="Place Order">
</form>

</body>
</html>


<?php

// Database Configuration (Replace with your actual database details)
$dbHost = "localhost";
$dbName = "ecommerce";
$dbUser = "root";
$dbPass = "";

// Function to connect to the database
function connectToDatabase() {
    $conn = new mysqli($dbHost, $dbUser, $dbPass, $dbName);
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    return $conn;
}

// Function to add a new order to the database
function addOrder($userId, $productId, $quantity, $orderDate) {
    $conn = connectToDatabase();

    $sql = "INSERT INTO orders (userId, productId, quantity, orderDate) 
            VALUES ('$userId', '$productId', '$quantity', '$orderDate')";

    if ($conn->query($sql) === TRUE) {
        return true;
    } else {
        return false;
    }
}

// Function to update the product quantity in the database
function updateProductQuantity($productId, $quantity) {
    $conn = connectToDatabase();

    $sql = "UPDATE products 
            SET quantity = quantity - '$quantity' 
            WHERE id = '$productId'";

    if ($conn->query($sql) === TRUE) {
        return true;
    } else {
        return false;
    }
}


// Handle Purchase Request (Example -  Simplified)
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get user input
    $userId = $_POST["userId"];
    $productId = $_POST["productId"];
    $quantity = $_POST["quantity"];

    // Validate Input (Important for security)
    if (empty($userId) || empty($productId) || empty($quantity)) {
        echo "Error: All fields are required.";
    } elseif (!is_numeric($quantity) || $quantity <= 0) {
        echo "Error: Quantity must be a positive number.";
    } else {
        // Add the order to the database
        if (addOrder($userId, $productId, $quantity, date("Y-m-d H:i:s"))) {
            // Update product quantity in the database
            if (updateProductQuantity($productId, $quantity)) {
                echo "Order placed successfully! Order ID: " . $productId; // Replace with actual order ID retrieval
            } else {
                echo "Error: Failed to update product quantity.";
            }
        } else {
            echo "Error: Failed to add order to database.";
        }
    }
}

// Example HTML Form (for demonstration purposes)
?>
