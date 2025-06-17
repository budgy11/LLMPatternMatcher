    <br><br>

    <button type="submit">Place Order</button>
  </form>

</body>
</html>


<?php

// Database configuration (Replace with your actual database details)
$db_host = "localhost";
$db_name = "e_commerce";
$db_user = "your_username";
$db_password = "your_password";

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validate form input (Important for security!)
    $name = trim($_POST["name"]);
    $email = trim($_POST["email"]);
    $quantity = intval($_POST["quantity"]);
    $product_id = intval($_POST["product_id"]); // Assuming product ID is passed

    // Sanitize inputs to prevent XSS and SQL injection
    $name = filter_var($name, FILTER_SANITIZE_STRING);
    $email = filter_var($email, FILTER_SANITIZE_EMAIL);
    $quantity = intval($quantity);
    $product_id = intval($product_id);

    // Check if required fields are filled
    if (empty($name) || empty($email) || empty($quantity) || empty($product_id)) {
        $error_message = "Please fill in all required fields.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error_message = "Invalid email address.";
    } elseif ($quantity <= 0) {
        $error_message = "Quantity must be greater than zero.";
    }  else {

        // Create a database connection
        $conn = new mysqli($db_host, $db_user, $db_password, $db_name);

        // Check connection
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        // Prepare and execute a SQL query (Important for security!)
        $sql = "INSERT INTO purchases (name, email, quantity, product_id) VALUES (?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssii", $name, $email, $quantity, $product_id);
        $stmt->execute();

        // Check if the query was successful
        if ($stmt->affected_rows > 0) {
            $success_message = "Purchase successful! Thank you for your order.";
        } else {
            $error_message = "Purchase failed.";
        }

        // Close the statement and connection
        $stmt->close();
        $conn->close();
    }
}
?>
