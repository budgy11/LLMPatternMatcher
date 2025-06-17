

<?php

// Database connection details - Replace with your actual details
$servername = "localhost";
$username = "your_username";
$password = "your_password";
$dbname = "your_database_name";

// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Get username and password from the form
    $username = $_POST["username"];
    $password = $_POST["password"];

    // Validate input (Important for security!)
    if (empty($username) || empty($password)) {
        $error = "Username and Password must be filled.";
    } else {
        // Connect to the database
        $conn = new mysqli($servername, $username, $password, $dbname);

        // Check connection
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        // SQL query to select the user
        $sql = "SELECT id, username, password FROM users WHERE username = '$username'";
        $result = $conn->query($sql);

        if ($result->num_rows == 0) {
            $error = "Invalid username or password.";
        } else {
            // If a user is found, verify the password
            $user = $result->fetch_assoc();
            if (password_verify($password, $user['password'])) { // Using password_verify
                // Login successful!  Set a session variable to track the user
                session_start();
                $_SESSION["user_id"] = $user['id'];
                $_SESSION["username"] = $user['username'];
                echo "Login successful!";
                // Redirect to a protected page or display a welcome message
                header("Location: protected_page.php"); // Replace with your protected page
                exit();

            } else {
                $error = "Incorrect password.";
            }
        }
    }
}
?>
