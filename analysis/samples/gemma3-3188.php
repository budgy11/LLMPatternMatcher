    <label for="username">Username:</label>
    <input type="text" id="username" name="username" placeholder="Enter username" required>

    <br><br>

    <label for="email">Email:</label>
    <input type="email" id="email" name="email" placeholder="Enter email" required>

    <br><br>

    <label for="password">Password:</label>
    <input type="password" id="password" name="password" placeholder="Enter password" required>

    <br><br>

    <button type="submit">Register</button>
  </form>

</body>
</html>


<?php

// Database connection details (replace with your actual credentials)
$host = "localhost";
$username = "your_db_username";
$password = "your_db_password";
$database = "your_db_name";

// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sanitize the input
    $username = trim($_POST["username"]);
    $email = trim($_POST["email"]);
    $password = trim($_POST["password"]);

    // Validate the input
    $errors = [];

    if (empty($username)) {
        $errors[] = "Username cannot be empty.";
    }
    if (empty($email)) {
        $errors[] = "Email cannot be empty.";
    }
    if (empty($password)) {
        $errors[] = "Password cannot be empty.";
    }
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Invalid email format.";
    }
    if (strlen($password) < 8) {
        $errors[] = "Password must be at least 8 characters long.";
    }

    // If no errors, proceed with registration
    if (empty($errors)) {
        // Prepare the SQL query
        $sql = "INSERT INTO users (username, email, password) VALUES (?, ?, ?)";

        // Prepare the statement
        $stmt = $conn->prepare($sql);

        // Bind the parameters
        $stmt->bind_param("sss", $username, $email, password_hash($password, PASSWORD_DEFAULT));

        // Execute the query
        if ($stmt->execute()) {
            // Registration successful
            echo "Registration successful! Please check your email to verify.";
        } else {
            // Registration failed
            echo "Registration failed. " . $conn->error;
        }

        // Close the statement
        $stmt->close();
    } else {
        // Display errors
        echo "Error: " . implode(", ", $errors);
    }
}
?>
