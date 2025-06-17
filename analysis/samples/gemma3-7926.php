    <label for="customer_name">Customer Name:</label>
    <input type="text" id="customer_name" name="customer_name" required><br><br>

    <label for="customer_email">Email:</label>
    <input type="email" id="customer_email" name="customer_email" required><br><br>

    <label for="address">Address:</label>
    <textarea id="address" name="address" rows="4" cols="50" required></textarea><br><br>

    <label for="payment_method">Payment Method:</label>
    <select id="payment_method" name="payment_method" required>
        <option value="credit_card">Credit Card</option>
        <option value="paypal">PayPal</option>
    </select><br><br>

    <button type="submit">Place Order</button>
</form>

</body>
</html>


<?php

// Database connection details (Replace with your actual details)
$dbHost = "localhost";
$dbName = "shopping_cart";
$dbUser = "your_user";
$dbPass = "your_password";

// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Sanitize and validate the input
    $product_id = isset($_POST["product_id"]) ? filter_var($_POST["product_id"], FILTER_SANITIZE_NUMBER_INT) : null;
    $quantity = isset($_POST["quantity"]) ? filter_var($_POST["quantity"], FILTER_SANITIZE_NUMBER_INT) : 1; // Default quantity is 1
    $customer_name = isset($_POST["customer_name"]) ? filter_var($_POST["customer_name"], FILTER_SANITIZE_STRING) : "";
    $customer_email = isset($_POST["customer_email"]) ? filter_var($_POST["customer_email"], FILTER_SANITIZE_EMAIL) : "";


    // Check if the product ID is valid
    if ($product_id !== null && $product_id > 0) {
        // Connect to the database
        $conn = new mysqli($dbHost, $dbUser, $dbPass, $dbName);

        // Check connection
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        // Prepare the SQL query
        $sql = "INSERT INTO orders (product_id, quantity, customer_name, customer_email) VALUES (?, ?, ?, ?)";

        // Prepare the statement
        $stmt = $conn->prepare($sql);

        // Bind the parameters
        $stmt->bind_param("isss", $product_id, $quantity, $customer_name, $customer_email);

        // Execute the statement
        if ($stmt->execute()) {
            echo "Order placed successfully!  Thank you, " . htmlspecialchars($customer_name) . "!";
        } else {
            echo "Error placing order: " . $stmt->error;
        }

        // Close the statement and connection
        $stmt->close();
        $conn->close();
    } else {
        echo "Invalid product ID.  Please try again.";
    }
}
?>
