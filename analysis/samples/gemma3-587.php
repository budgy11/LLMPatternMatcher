

<?php

// Database credentials (Replace with your actual credentials)
$db_host = "localhost";
$db_name = "your_database_name";
$db_user = "your_username";
$db_pass = "your_password";

// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get username and password from the form
    $username = $_POST["username"];
    $password = $_POST["password"];

    // Validate input (IMPORTANT - prevent SQL injection and bad input)
    if (empty($username) || empty($password)) {
        $error_message = "Username and Password cannot be empty.";
    } else {
        // Hash the password before comparing
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // Query the database to check for the username
        $stmt = $conn->prepare("SELECT id, username, password FROM users WHERE username = ?");
        $stmt->bind_param("s", $username);  // "s" indicates a string
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows == 0) {
            $error_message = "Invalid username or password.";
        } else {
            $user = $result->fetch_assoc();
            if (password_verify($password, $user["password"])) {
                // Password matches, so login successful
                session_start();  // Start the session
                $_SESSION["user_id"] = $user["id"];
                $_SESSION["username"] = $username;
                header("Location: welcome.php"); // Redirect to welcome page
                exit();
            } else {
                $error_message = "Invalid username or password.";
            }
        }
    }
}
?>
