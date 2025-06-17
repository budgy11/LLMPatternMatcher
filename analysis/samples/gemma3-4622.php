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

// Database connection details (replace with your actual credentials)
$db_host = "localhost";
$db_name = "your_database_name";
$db_user = "your_username";
$db_pass = "your_password";

// Form handling
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get form data
    $username = $_POST["username"];
    $email = $_POST["email"];
    $password = $_POST["password"];

    // Validate the data (basic validation - enhance this for production)
    if (empty($username) || empty($email) || empty($password)) {
        $error = "All fields are required.";
    } elseif (strlen($username) < 3) {
        $error = "Username must be at least 3 characters long.";
    } elseif (filter_var($email, FILTER_VALIDATE_EMAIL) === false) {
        $error = "Invalid email format.";
    } elseif (strlen($password) < 6) {
        $error = "Password must be at least 6 characters long.";
    } else {
        // Hash the password
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // SQL query to insert the user data
        $sql = "INSERT INTO users (username, email, password) VALUES (?, ?, ?)";

        // Prepare the statement
        $stmt = $conn->prepare($sql);

        if ($stmt) {
            // Bind the parameters
            $stmt->bind_param("ss", $username, $email, $hashed_password);

            // Execute the statement
            if ($stmt->execute()) {
                // Registration successful
                echo "<p>Registration successful! Please <a href='login.php'>login</a>.</p>";
            } else {
                // Error executing the query
                echo "<p>Error: " . $stmt->error . "</p>";
            }
        } else {
            echo "<p>Error preparing statement.</p>";
        }
    }
}
?>
