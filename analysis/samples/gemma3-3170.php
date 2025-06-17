        <label for="username">Username:</label>
        <input type="text" id="username" name="username" required>

        <br><br>

        <label for="email">Email:</label>
        <input type="email" id="email" name="email" required>

        <br><br>

        <label for="password">Password:</label>
        <input type="password" id="password" name="password" required>

        <br><br>

        <button type="submit">Register</button>
    </form>

</body>
</html>


<?php

// Database credentials (Replace with your actual credentials)
$db_host = "localhost";
$db_name = "your_database_name";
$db_user = "your_database_user";
$db_pass = "your_database_password";

// Check if the registration form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Validate and sanitize the input
    $username = filter_input(INPUT_POST, "username", FILTER_SANITIZE_STRING);
    $email = filter_input(INPUT_POST, "email", FILTER_SANITIZE_EMAIL);
    $password = filter_input(INPUT_POST, "password", FILTER_SANITIZE_STRING);

    // Validate required fields
    if (empty($username) || empty($email) || empty($password)) {
        $error = "All fields are required.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Invalid email format.";
    } elseif (strlen($password) < 8) {
        $error = "Password must be at least 8 characters long.";
    } else {
        // Hash the password before storing it
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // SQL query to insert the new user into the database
        $sql = "INSERT INTO users (username, email, password) VALUES (?, ?, ?)";

        // Prepare the SQL statement
        $stmt = $conn->prepare($sql);

        // Bind the parameters
        $stmt->bind_param("ss", $username, $hashed_password);

        // Execute the statement
        if ($stmt->execute()) {
            // Registration successful
            echo "<p style='color: green;'>Registration successful.  You have been redirected to the login page.</p>";
            // Optionally, redirect to a login page or display a success message
            // header("Location: login.php");
            // exit;
        } else {
            // Error executing the query
            echo "<p style='color: red;'>Error registering user. Please try again later.</p>";
            // Log the error for debugging
            error_log("Error registering user: " . $stmt->error);
        }

        // Close the statement and connection
        $stmt->close();
        $conn->close();
    }
} else {
    // If the form hasn't been submitted, display the registration form
    echo "<h1>Registration Form</h1>";
    echo "<form action='register.php' method='POST'>";
    echo "Username: <input type='text' name='username'><br>";
    echo "Email: <input type='email' name='email'><br>";
    echo "Password: <input type='password' name='password'><br>";
    echo "<input type='submit' value='Register'>";
    echo "</form>";
}
?>
