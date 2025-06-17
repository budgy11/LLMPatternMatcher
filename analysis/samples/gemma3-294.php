

<?php

// Database credentials - Replace with your actual values
$db_host = "localhost";
$db_name = "your_database_name";
$db_user = "your_db_user";
$db_password = "your_db_password";

// Check if the login form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get username and password from the form
    $username = $_POST["username"];
    $password = $_POST["password"];

    // Validate input (Important for security!)
    if (empty($username) || empty($password)) {
        $error_message = "Username and password must be filled.";
    } elseif (!filter_var($username, FILTER_VALIDATE_EMAIL)) {
        $error_message = "Invalid username.  Please enter a valid email address.";
    } else {
        // Database connection
        $conn = new mysqli($db_host, $db_user, $db_password, $db_name);

        // Check connection
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        // SQL query - Use parameterized queries for security!
        $sql = "SELECT * FROM users WHERE email = ?";  // Use email for user authentication
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $username);  // "s" indicates a string parameter
        $stmt->execute();

        $result = $stmt->get_result();

        if ($result->num_rows == 0) {
            $error_message = "Invalid username or password.";
        } else {
            // If the query finds a user, verify the password
            $user = $result->fetch_assoc();
            if (password_verify($password, $user["password"])) {
                // Login successful!  Set session variables
                session_start();
                $_SESSION["user_id"] = $user["id"];
                $_SESSION["username"] = $user["email"]; // Use email for session
                header("Location: /welcome.php");  // Redirect to a welcome page
                exit();
            } else {
                $error_message = "Incorrect password.";
            }
        }
    }
}
?>
