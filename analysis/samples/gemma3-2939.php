    <label for="username">Username:</label>
    <input type="text" id="username" name="username" required><br><br>

    <label for="email">Email:</label>
    <input type="email" id="email" name="email" required><br><br>

    <label for="password">Password:</label>
    <input type="password" id="password" name="password" required><br><br>

    <button type="submit">Register</button>
  </form>

  <p>Already have an account? <a href="login.php">Login</a></p> <!-- Assuming you have a login page -->

</body>
</html>


<?php

// Database connection details (replace with your actual credentials)
$host = "localhost";
$username = "your_db_username";
$password = "your_db_password";
$database = "your_database_name";

// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Validate the form input
    $username = trim($_POST["username"]);
    $password = trim($_POST["password"]);
    $email = trim($_POST["email"]);

    // Basic validation
    if (empty($username) || empty($password) || empty($email)) {
        $errors = "All fields are required.";
    } elseif (strlen($username) < 3) {
        $errors = "Username must be at least 3 characters long.";
    } elseif (strlen($password) < 6) {
        $errors = "Password must be at least 6 characters long.";
    } elseif (filter_var($email, FILTER_VALIDATE_EMAIL) === false) {
        $errors = "Invalid email format.";
    } else {
        // Prepare the SQL query
        $sql = "INSERT INTO users (username, password, email) VALUES (?, ?, ?)";

        // Prepare the statement (recommended for security)
        $stmt = $conn->prepare($sql);

        if ($stmt) {
            // Bind the parameters
            $stmt->bind_param("ss", $username, $password);

            // Execute the statement
            if ($stmt->execute()) {
                // Registration successful
                echo "Registration successful! You have been redirected.";
                // Redirect to a success page or home page
                header("Location: success.php");
                exit(); // Stop further execution
            } else {
                // Handle errors
                echo "Error: " . $stmt->error;
            }

            $stmt->close();
        } else {
            echo "Error preparing statement.";
        }
    }

    // Display errors if any
    if (!empty($errors)) {
        echo "<p style='color: red;'>$errors</p>";
    }
}

// Registration form
?>
