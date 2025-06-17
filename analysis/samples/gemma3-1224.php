

<?php

// Database connection details
$host = "localhost";
$username = "your_username";
$password = "your_password";
$database = "your_database";

// Form data
$username = $_POST['username'];
$password = $_POST['password'];

// SQL query to fetch the user
$sql = "SELECT id, username, password FROM users WHERE username = '$username'";
$result = mysqli_query($host, $sql);

if ($result) {
    while ($row = mysqli_fetch_assoc($result)) {
        // Verify the password
        if (password_verify($password, $row['password'])) {
            // Password is correct, set session variables
            session_start();
            $_SESSION['user_id'] = $row['id'];
            $_SESSION['username'] = $username;

            // Redirect to a protected page or display a success message
            header("Location: protected_page.php"); // Replace with your protected page
            exit();
        } else {
            // Incorrect password
            echo "<p style='color:red;'>Incorrect username or password.</p>";
        }
    }
} else {
    // Error querying the database
    echo "<p style='color:red;'>Database error: " . mysqli_error($host) . "</p>";
}

?>
