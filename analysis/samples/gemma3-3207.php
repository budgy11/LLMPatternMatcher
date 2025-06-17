

<?php

// Database connection details (replace with your actual values)
$servername = "localhost";
$username = "your_username";
$password = "your_password";
$dbname = "your_database_name";

// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Validate the input data
    $username = trim($_POST["username"]);
    $email = trim($_POST["email"]);
    $password = trim($_POST["password"]);

    // Validate username
    if (empty($username)) {
        $username_error = "Username cannot be empty.";
    } elseif (strlen($username) < 3) {
        $username_error = "Username must be at least 3 characters long.";
    }

    // Validate email
    if (empty($email)) {
        $email_error = "Email cannot be empty.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $email_error = "Invalid email address.";
    }

    // Validate password
    if (empty($password)) {
        $password_error = "Password cannot be empty.";
    } elseif (strlen($password) < 8) {
        $password_error = "Password must be at least 8 characters long.";
    }

    // If validation passes, insert the data into the database
    if (empty($username_error) && empty($email_error) && empty($password_error)) {

        // Prepare the SQL query
        $sql = "INSERT INTO users (username, email, password) VALUES (?, ?, ?)";

        // Use prepared statements to prevent SQL injection
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ss", $username, $email, password_hash($password, PASSWORD_DEFAULT));

        // Execute the query
        if ($stmt->execute()) {
            // Registration successful
            echo "Registration successful! You have been redirected.";
            // Optional: Redirect to a success page or login page
            header("Location: login.php"); // Or your desired redirect URL
            exit();
        } else {
            // Error executing query
            echo "Error: " . $sql . "<br>" . $conn->error;
        }

        $stmt->close();
    }
}
?>
