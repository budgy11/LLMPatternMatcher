    <label for="item_name">Item Name:</label>
    <input type="text" id="item_name" name="item_name" required><br><br>

    <label for="item_price">Item Price:</label>
    <input type="number" id="item_price" name="item_price" step="0.01" required><br><br>

    <input type="submit" value="Add to Cart">
</form>

</body>
</html>


<?php

// Assuming we have a database connection established
// (You'll need to adapt this to your database setup)
// Example using mysqli:
$servername = "localhost";
$username = "your_username";
$password = "your_password";
$dbname = "your_database_name";

try {
    $conn = new mysqli($servername, $username, $password, $dbname);
    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
} catch (Exception $e) {
    echo "Database connection error: " . $e->getMessage();
    exit();
}


// Function to handle the purchase process
function handlePurchase($product_id, $quantity, $customer_name, $customer_email) {
    global $conn; // Access the database connection

    // Validate inputs -  Crucial for security!
    if (empty($product_id) || empty($quantity) || empty($customer_name) || empty($customer_email)) {
        return "Error: All fields are required.";
    }

    if (!is_numeric($quantity) || $quantity <= 0) {
        return "Error: Quantity must be a positive number.";
    }

    // Sanitize inputs to prevent SQL injection
    $product_id = $conn->real_escape_string($product_id);
    $customer_name = $conn->real_escape_string($customer_name);
    $customer_email = $conn->real_escape_string($customer_email);

    // Get product price
    $sql = "SELECT price FROM products WHERE id = '$product_id'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $product_price = $row['price'];
    } else {
        return "Error: Product not found.";
    }

    // Calculate total price
    $total_price = $product_price * $quantity;

    // Insert into the order table
    $sql = "INSERT INTO orders (product_id, customer_name, customer_email, quantity, total_price, order_date)
            VALUES ('$product_id', '$customer_name', '$customer_email', '$quantity', '$total_price', NOW())";

    if ($conn->query($sql) === TRUE) {
        return "Purchase successful! Order ID: " . $conn->insert_id;
    } else {
        return "Error: " . $conn->error;
    }
}

// Example usage (handling form submission)
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $product_id = $_POST["product_id"];
    $quantity = $_POST["quantity"];
    $customer_name = $_POST["customer_name"];
    $customer_email = $_POST["customer_email"];

    $purchaseResult = handlePurchase($product_id, $quantity, $customer_name, $customer_email);
    echo $purchaseResult;
}
?>
