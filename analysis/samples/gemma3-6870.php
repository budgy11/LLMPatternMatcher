

<?php

// Database connection details (replace with your actual credentials)
$db_host = "localhost";
$db_user = "your_username";
$db_password = "your_password";
$db_name = "your_database_name";

// Function to connect to the database
function connectToDatabase() {
    $conn = new mysqli($db_host, $db_user, $db_password, $db_name);
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    return $conn;
}

// Function to add a new order to the database
function addOrder($customerName, $customerEmail, $items) {
    $conn = connectToDatabase();

    // Sanitize inputs (important for security)
    $customerName = $conn->real_escape_string($customerName);
    $customerEmail = $conn->real_escape_string($customerEmail);

    // Prepare the SQL statement
    $sql = "INSERT INTO orders (customer_name, customer_email, items) VALUES (?, ?, ?)";

    // Prepare the statement
    $stmt = $conn->prepare($sql);

    if ($stmt) {
        // Bind the parameters
        $stmt->bind_param("ss", $customerName, $customerEmail);

        // Execute the statement
        if ($stmt->execute()) {
            return true;
        } else {
            error_log("Query execution failed: " . $stmt->error); //Log errors for debugging
            return false;
        }
    } else {
        error_log("Query preparation failed: " . $stmt->error); // Log errors for debugging
        return false;
    }

    // Close the statement
    $stmt->close();
}


// Example usage (handling the form submission)

// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Validate and sanitize the input (IMPORTANT: Implement more robust validation)
    $customerName = $_POST["customer_name"];
    $customerEmail = $_POST["customer_email"];
    $items = $_POST["items"];  // Assuming items are comma-separated

    // Validate email format (very basic check)
    if (!filter_var($customerEmail, FILTER_VALIDATE_EMAIL)) {
        $error = "Invalid email address.";
    } else {
        // Add the order to the database
        if (addOrder($customerName, $customerEmail, $items)) {
            $success = "Order placed successfully!";
        } else {
            $error = "Failed to place order.  Please try again.";
        }
    }
}

?>
