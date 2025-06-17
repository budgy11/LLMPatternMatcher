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

// Database configuration (Replace with your actual credentials)
$dbHost = "localhost";
$dbName = "your_database_name";
$dbUser = "your_database_user";
$dbPassword = "your_database_password";

// Check if the registration form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get form data
    $username = $_POST["username"];
    $email = $_POST["email"];
    $password = $_POST["password"];
    $confirmPassword = $_POST["confirmPassword"];

    // Validate the data
    $errors = [];

    // Username validation
    if (empty($username)) {
        $errors[] = "Username cannot be empty.";
    }
    if (strlen($username) < 3) {
        $errors[] = "Username must be at least 3 characters long.";
    }
    if (strlen($username) > 20) {
        $errors[] = "Username must be no more than 20 characters long.";
    }

    // Email validation
    if (empty($email)) {
        $errors[] = "Email cannot be empty.";
    }
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Invalid email format.";
    }

    // Password validation
    if (empty($password)) {
        $errors[] = "Password cannot be empty.";
    }
    if (strlen($password) < 8) {
        $errors[] = "Password must be at least 8 characters long.";
    }
    if ($password != $confirmPassword) {
        $errors[] = "Passwords do not match.";
    }

    // Check if there are any errors
    if (!empty($errors)) {
        // Display the errors (e.g., in an error message)
        echo "<h2>Error:</h2>";
        echo "<ol>";
        foreach ($errors as $error) {
            echo "<li>" . $error . "</li>";
        }
        echo "</ol>";
    } else {
        // Database connection
        $conn = new mysqli($dbHost, $dbUser, $dbPassword, $dbName);

        // Check the connection
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        // Hash the password (VERY IMPORTANT for security!)
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        // SQL query to insert the new user
        $sql = "INSERT INTO users (username, email, password) VALUES ('$username', '$email', '$hashedPassword')";

        // Execute the query
        if ($conn->query($sql)) {
            echo "<h2>Registration successful!</h2>";
            echo "<p>You have successfully registered.  Please check your email to verify your account.</p>";
        } else {
            echo "<h2>Error:</h2>" . $conn->error;
        }

        // Close the connection
        $conn->close();
    }
}
?>
