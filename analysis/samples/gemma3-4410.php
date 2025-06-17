    <label for="username">Username:</label>
    <input type="text" id="username" name="username" required><br><br>

    <label for="password">Password:</label>
    <input type="password" id="password" name="password" required><br><br>

    <label for="email">Email:</label>
    <input type="email" id="email" name="email" required><br><br>

    <button type="submit">Register</button>
  </form>

</body>
</html>


<?php

// Database credentials (Replace with your actual values!)
$db_host = "localhost";
$db_name = "your_database_name";
$db_user = "your_database_user";
$db_pass = "your_database_password";

// Check if the registration form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validate the form data
    $username = $_POST["username"];
    $email = $_POST["email"];
    $password = $_POST["password"];
    $confirm_password = $_POST["confirm_password"];

    // Validate required fields
    if (empty($username) || empty($email) || empty($password) || empty($confirm_password)) {
        $error = "All fields are required.";
    }

    // Validate email format
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Invalid email format.";
    }

    // Validate password confirmation
    if ($password != $confirm_password) {
        $error = "Passwords do not match.";
    }

    // Password validation (basic - consider stronger validation)
    if (strlen($password) < 8) {
        $error = "Password must be at least 8 characters long.";
    }

    // If no errors, proceed with registration
    if (empty($error)) {
        // Hash the password (VERY IMPORTANT for security!)
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // Prepare the SQL query
        $sql = "INSERT INTO users (username, email, password) VALUES (?, ?, ?)";

        // Prepare the statement
        $stmt = $conn->prepare($sql);

        // Bind the parameters
        $stmt->bind_param("sss", $username, $email, $hashed_password);

        // Execute the query
        if ($stmt->execute()) {
            // Registration successful
            echo "<p style='color: green;'>Registration successful! You have been redirected.</p>";
            // Redirect to a success page or login form
            header("Location: login.php");
            exit();
        } else {
            // Query execution failed
            echo "<p style='color: red;'>Registration failed.  Error: " . $conn->error . "</p>";
        }
    }
}
?>
