        <label for="remove_item">Item to Remove:</label>
        <select name="remove_item" id="remove_item">
            <option value="T-Shirt">T-Shirt</option>
            <option value="Jeans">Jeans</option>
            <option value="Hat">Hat</option>
            <option value="Shoes">Shoes</option>
        </select><br><br>

        <label for="remove_quantity">Quantity to Remove:</label>
        <input type="number" name="remove_quantity" id="remove_quantity" min="1"><br><br>

        <button type="submit">Remove from Cart</button>
    </form>


</body>
</html>


<?php

// Database connection details (replace with your actual credentials)
$host = "localhost";
$username = "your_username";
$password = "your_password";
$database = "your_database";

// Create a database connection
$conn = new mysqli($host, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Define the purchase functionality
function createPurchase($productId, $quantity, $customerName, $customerEmail) {
    // Sanitize inputs to prevent SQL injection
    $productId = $conn->real_escape_string($productId);
    $quantity = $conn->real_escape_string($quantity);
    $customerName = $conn->real_escape_string($customerName);
    $customerEmail = $conn->real_escape_string($customerEmail);

    // SQL query to insert the purchase data
    $sql = "INSERT INTO purchases (product_id, quantity, customer_name, customer_email)
            VALUES ('$productId', '$quantity', '$customerName', '$customerEmail')";

    if ($conn->query($sql) === TRUE) {
        return true; // Purchase created successfully
    } else {
        return false; // Error creating purchase
    }
}

// Example Usage:  (This part is for demonstration - adjust to your application)

// 1.  Handle form submission (if a form is used to collect data)
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $productId = $_POST["product_id"];
    $quantity = $_POST["quantity"];
    $customerName = $_POST["customer_name"];
    $customerEmail = $_POST["customer_email"];

    if (createPurchase($productId, $quantity, $customerName, $customerEmail)) {
        echo "<p>Purchase created successfully! Order ID: " . $conn->insert_id . "</p>"; // Display success message
    } else {
        echo "<p>Error creating purchase. Please try again.</p>";
    }
}
?>
