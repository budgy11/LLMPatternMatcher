        <label for="name">Name:</label>
        <input type="text" id="name" name="name" required><br><br>

        <label for="email">Email:</label>
        <input type="email" id="email" name="email" required><br><br>

        <label for="product_id">Product ID:</label>
        <input type="number" id="product_id" name="product_id" required><br><br>

        <label for="quantity">Quantity:</label>
        <input type="number" id="quantity" name="quantity" required><br><br>

        <input type="submit" value="Place Order">
    </form>

</body>
</html>


<?php
session_start();

// Database connection (replace with your actual database credentials)
$dbHost = 'localhost';
$dbUser = 'your_username';
$dbPass = 'your_password';
$dbName = 'your_database';

// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Validate the form data
    $name = trim($_POST["name"]);
    $email = trim($_POST["email"]);
    $quantity = (int)($_POST["quantity"]);
    $product_id = (int)($_POST["product_id"]);

    // Basic validation (still recommended for input sanity)
    if (empty($name) || empty($email) || $quantity <= 0 || $product_id <= 0) {
        $error = "Please fill in all fields and ensure quantity and product ID are positive.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Invalid email format.";
    } else {
        // Process the purchase using prepared statements
        // 1.  Create a prepared statement
        $conn = new mysqli($dbHost, $dbUser, $dbPass, $dbName);

        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        $stmt = $conn->prepare("INSERT INTO orders (name, email, product_id, quantity) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("sss", $name, $email, $product_id); // "sss" means three string parameters
        $stmt->execute();

        $conn->close();
        $success = "Order placed successfully! Order ID: " . $conn->insert_id;
    }
}
?>
