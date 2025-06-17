
<!DOCTYPE html>
<html>
<head>
    <title>Login Form</title>
</head>
<body>

    <h2>Login</h2>

    <form method="post" action="">
        <label for="username">Username:</label>
        <input type="text" id="username" name="username" required><br><br>

        <label for="password">Password:</label>
        <input type="password" id="password" name="password" required><br><br>

        <button type="submit">Login</button>
    </form>

</body>
</html>


<?php

// Database credentials - Replace with your actual credentials
$servername = "localhost";
$username = "your_username";
$password = "your_password";
$dbname = "your_database_name";

// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get the login details from the form
    $username = $_POST["username"];
    $password = $_POST["password"];

    // Connect to the database
    $conn = new mysqli($servername, $username, $password, $dbname);

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // SQL query to retrieve the user's password
    $sql = "SELECT password FROM users WHERE username = '$username'";
    $result = $conn->query($sql);

    if ($result->num_rows == 0) {
        echo "<p style='color: red;'>Invalid username or password.</p>";
    } else {
        $row = $result->fetch_assoc();
        $stored_password = $row["password"];

        // Verify the password
        if (password_verify($password, $stored_password)) {
            // Login successful
            echo "<p style='color: green;'>Login successful!</p>";

            // You can redirect the user to another page or display a welcome message here
            // Example:
            // header("Location: welcome.php");
            // die();
        } else {
            echo "<p style='color: red;'>Invalid username or password.</p>";
        }
    }

    $conn->close();
}
?>
