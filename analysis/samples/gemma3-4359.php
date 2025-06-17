

<?php

// Database connection details (Replace with your actual values)
$host     = 'localhost';
$username = 'your_username';
$password = 'your_password';
$database = 'your_database';

// Form data (from a POST request)
$username = $_POST['username'];
$email = $_POST['email'];
$password = $_POST['password'];
$confirm_password = $_POST['confirm_password'];

// Validation (Crucial to prevent security vulnerabilities)
if (empty($username) || empty($email) || empty($password) || empty($confirm_password)) {
    // Handle missing fields - Display error message
    echo "<p>Error: All fields are required.</p>";
    exit; // Stop further execution
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    // Handle invalid email format
    echo "<p>Error: Invalid email format.</p>";
    exit;
}

if ($password != $confirm_password) {
    // Handle password mismatch
    echo "<p>Error: Passwords do not match.</p>";
    exit;
}

// Hash the password (Important for security!)
$hashed_password = password_hash($password, PASSWORD_DEFAULT);

// Prepare the SQL query
$sql = "INSERT INTO users (username, email, password) VALUES (?, ?, ?)";

// Prepare the statement
$stmt = $conn->prepare($sql);

// Bind the parameters
$stmt->bind_param("ss", $username, $hashed_password); // Use "ss" for string parameters

// Execute the query
if ($stmt->execute()) {
    // Registration successful
    echo "<p>Registration successful!  You have been redirected.</p>";
    // Redirect to a success page or display a message
    // header("Location: success.php"); // Example redirect
    exit;
} else {
    // Handle database errors
    echo "<p>Error: " . $stmt->error . "</p>";
}

// Close the statement
$stmt->close();


//  *** Important: Add this if you're using PHP 5.3.0 or older ***
//  password_needs_rehash($hashed_password)  // for older PHP versions
?>
