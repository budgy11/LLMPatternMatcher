</div>

</body>
</html>


<?php

// Database connection details (replace with your actual credentials)
$host = "localhost";
$username = "your_username";
$password = "your_password";
$database = "your_database";

// Database connection
$conn = new mysqli($host, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Function to handle purchase creation
function createPurchase($cart_id, $customer_name, $customer_email, $payment_method) {
    global $conn; // Access the global connection

    // Validate input (add more validation as needed)
    if (empty($cart_id) || empty($customer_name) || empty($customer_email) || empty($payment_method)) {
        return "Error: Missing required fields.";
    }

    // Sanitize input (important to prevent SQL injection)
    $cart_id = $conn->real_escape_string($cart_id);
    $customer_name = $conn->real_escape_string($customer_name);
    $customer_email = $conn->real_escape_string($customer_email);
    $payment_method = $conn->real_escape_string($payment_method);

    // SQL query to create the purchase record
    $sql = "INSERT INTO purchases (cart_id, customer_name, customer_email, payment_method, purchase_date)
            VALUES ('$cart_id', '$customer_name', '$customer_email', '$payment_method', NOW())";

    if ($conn->query($sql) === TRUE) {
        return "Purchase created successfully!  Purchase ID: " . $conn->insert_id;
    } else {
        return "Error creating purchase: " . $conn->error;
    }
}


// Example usage (handling form submission)
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $cart_id = $_POST["cart_id"];
    $customer_name = $_POST["customer_name"];
    $customer_email = $_POST["customer_email"];
    $payment_method = $_POST["payment_method"];

    $result = createPurchase($cart_id, $customer_name, $customer_email, $payment_method);

    echo "<p>" . $result . "</p>"; // Display the result
}

// ---- HTML Form for Submission ----
?>
