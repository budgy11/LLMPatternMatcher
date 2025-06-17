    <label for="userId">User ID:</label>
    <input type="text" id="userId" name="userId" required><br><br>

    <label for="productId">Product ID:</label>
    <input type="text" id="productId" name="productId" required><br><br>

    <label for="quantity">Quantity:</label>
    <input type="number" id="quantity" name="quantity" required><br><br>

    <button type="submit">Place Order</button>
</form>

</body>
</html>


<?php

//... (Database connection code as before)

function addOrder($userId, $productId, $quantity, $orderDate) {
    $conn = connectToDatabase();

    // Use prepared statement
    $stmt = $conn->prepare("INSERT INTO orders (userId, productId, quantity, orderDate) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("sii", $userId, $productId, $orderDate);  // 's' indicates string type

    if ($stmt->query() === TRUE) {
        $orderId = $conn->insert_id;  // Get the ID of the newly inserted row
        return $orderId;
    } else {
        return false;
    }

    $stmt->close(); // Close the statement
}

// ... (rest of the code)


<?php

// Database configuration (Replace with your actual database details)
$db_host = "localhost";
$db_user = "your_username";
$db_password = "your_password";
$db_name = "your_database";

// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validate input (Important for security - add more validation as needed)
    $product_name = $_POST["product_name"];
    $quantity = $_POST["quantity"];
    $price = $_POST["price"];

    // Validate input (Basic example - improve for production)
    if (empty($product_name) || empty($quantity) || empty($price)) {
        $error = "Please fill in all fields.";
    } elseif (!is_numeric($quantity) || $quantity <= 0) {
        $error = "Quantity must be a positive number.";
    } elseif (!is_numeric($price) || $price <= 0) {
        $error = "Price must be a positive number.";
    }

    // If no errors, proceed with the purchase
    if (empty($error)) {
        // Connect to the database
        $conn = new mysqli($db_host, $db_user, $db_password, $db_name);

        // Check the connection
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        // Prepare the SQL statement
        $sql = "INSERT INTO purchases (product_name, quantity, price, purchase_date)
                VALUES ('$product_name', '$quantity', '$price', NOW())";

        // Execute the SQL statement
        if ($conn->query($sql) === TRUE) {
            echo "Purchase successful!  Order ID: " . $conn->insert_id; // Display the new order ID
        } else {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }

        // Close the connection
        $conn->close();
    }
}
?>
