

$password = $_POST['password'];  // Get the password
$hashedPassword = password_hash($password, PASSWORD_DEFAULT);

// ... (Insert $username and $hashedPassword into the database)


$password = $_POST['password'];
$hashedPassword = password_hash($password, PASSWORD_DEFAULT);

// ... (Query for the hashed password from the database)

if (password_verify($password, $hashedPasswordFromDatabase)) {
  // Login successful!
} else {
  // Login failed - incorrect password
}


<?php

// Database credentials (replace with your actual credentials)
$servername = "localhost";
$username = "your_username";
$password = "your_password";
$dbname = "your_database";

// Form handling (for simplicity, we'll directly process the form)
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get input values from the form
    $username = $_POST["username"];
    $password = $_POST["password"];

    // Validate the input (basic example - you should add more robust validation)
    if (empty($username) || empty($password)) {
        $error = "Username and password cannot be empty.";
    } elseif (strlen($username) < 3) {
        $error = "Username must be at least 3 characters long.";
    } elseif (strlen($password) < 6) {
        $error = "Password must be at least 6 characters long.";
    }
    
    else {
        // Connect to the database
        $conn = new mysqli($servername, $username, $password, $dbname);

        // Check connection
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        // Query to select the user from the database
        $sql = "SELECT * FROM users WHERE username = '$username' AND password = '$password'";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            // User found!
            // Session management (important for security - see explanation below)
            session_start();
            $_SESSION["username"] = $username; // Store the username in the session
            echo "Login successful! You are now logged in.";
            // Redirect to a protected page or display a welcome message
            // header("Location: /protected_page.php"); // Example
            //exit();

        } else {
            // User not found
            $error = "Invalid username or password.";
        }
    }
}
?>
