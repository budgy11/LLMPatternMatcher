

<?php

// Database credentials (replace with your actual details)
$db_host = "localhost";
$db_name = "your_database_name";
$db_user = "your_db_user";
$db_pass = "your_db_password";

// Function to handle user login
function loginUser($username, $password) {
    // Connect to the database
    $conn = new mysqli($db_host, $db_user, $db_pass, $db_name);

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Prepare the SQL query
    $sql = "SELECT * FROM users WHERE username = ? AND password = ?"; 

    // Bind parameters to prevent SQL injection
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $username, $password); // "ss" indicates two string parameters

    // Execute the query
    if ($stmt->execute() === TRUE) {
        // Get the result
        $result = $stmt->get_result();

        if ($result->num_rows == 1) {
            // User found, get the user data
            $row = $result->fetch_assoc();

            // Authenticate
            if (password_verify($password, $row['password'])) { // Use password_verify
                // Login successful, set session variables
                $_SESSION['user_id'] = $row['id'];
                $_SESSION['username'] = $row['username'];

                return true;
            } else {
                // Password mismatch
                return false;
            }
        } else {
            // No user found or other error
            return false;
        }
    } else {
        // Query error
        return false;
    }

    // Close the statement and connection
    $stmt->close();
    $conn->close();
}

// Example usage (in your login form):
if (isset($_POST['username']) && isset($_POST['password'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];

    if (loginUser($username, $password)) {
        // Login successful, redirect to a protected page or display a success message
        header("Location: protected_page.php");  // Replace with your protected page
        exit();
    } else {
        // Login failed, display an error message
        echo "<p style='color:red;'>Invalid username or password.</p>";
    }
}
?>
