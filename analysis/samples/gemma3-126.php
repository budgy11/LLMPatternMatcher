

<?php

// Database credentials (replace with your actual credentials)
$host = "localhost";
$username = "your_username";
$password = "your_password";
$database = "your_database";

// Form data from the login form
$username = $_POST['username'];
$password = $_POST['password'];

// Connect to the database
$conn = new mysqli($host, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Prepare the SQL query
$sql = "SELECT * FROM users WHERE username = ? AND password = ?";
$stmt = $conn->prepare($sql);

// Bind parameters
$stmt->bind_param("ss", $username, $password); // "ss" indicates two strings

// Execute the query
$stmt->execute();

// Get the result
$result = $stmt->get_result();

// Check if a user was found
if ($result->num_rows > 0) {
    // User found, proceed with login
    $row = $result->fetch_assoc();
    // Verify password (important for security - hash the stored password)
    //  This is a simplified example. In a production environment, you MUST
    //  use a proper password hashing function like password_hash() and
    //  verify against the hashed password stored in your database.

    // Example:  Assuming password is stored hashed:
    if (password_verify($password, $row['password'])) {
        // Login successful
        // Set session variables to store user information
        session_start();
        $_SESSION['user_id'] = $row['id'];
        $_SESSION['username'] = $row['username'];
        // You can also store other user data in the session
        // e.g., $_SESSION['user_name'] = $row['name'];

        // Redirect the user to a secure page (e.g., the dashboard)
        header("Location: dashboard.php");
        exit();

    } else {
        // Password mismatch
        echo "Incorrect password.";
    }

} else {
    // User not found
    echo "User not found.";
}

// Close the database connection
$stmt->close();
$conn->close();

?>
