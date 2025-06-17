    <label for="username">Username:</label>
    <input type="text" id="username" name="username" required><br><br>

    <label for="email">Email:</label>
    <input type="email" id="email" name="email" required><br><br>

    <label for="password">Password:</label>
    <input type="password" id="password" name="password" required><br><br>

    <label for="confirm_password">Confirm Password:</label>
    <input type="password" id="confirm_password" name="confirm_password" required><br><br>

    <button type="submit">Register</button>
</form>

</body>
</html>


<?php

// Database credentials (replace with your actual values)
$db_host = "localhost";
$db_username = "your_username";
$db_password = "your_password";
$db_name = "your_database_name";

// Form handling - Check if the registration form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get form data
    $username = $_POST["username"];
    $email = $_POST["email"];
    $password = $_POST["password"];
    $confirm_password = $_POST["confirm_password"];

    // Validate the data
    $errors = [];

    // Check if username is empty
    if (empty($username)) {
        $errors[] = "Username cannot be empty.";
    }

    // Check if username is already registered (simple check - more robust checks needed in a real application)
    // This is a *very* basic check.  For production, you'd use a database query.
    if (usernameExists($username)) {
        $errors[] = "Username already exists. Please choose another.";
    }

    // Check if email is empty
    if (empty($email)) {
        $errors[] = "Email cannot be empty.";
    }

    // Check if email is valid (basic check, more validation needed)
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Invalid email format.";
    }

    // Check if passwords match
    if ($password != $confirm_password) {
        $errors[] = "Passwords do not match.";
    }

    // Check if password is empty
    if (empty($password)) {
        $errors[] = "Password cannot be empty.";
    }

    // If there are no errors, proceed with registration
    if (empty($errors)) {
        // Hash the password (IMPORTANT!)
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // Connect to the database
        $conn = new mysqli($db_host, $db_username, $db_password, $db_name);

        // Check the connection
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        // Prepare the SQL query
        $sql = "INSERT INTO users (username, email, password) VALUES (?, ?, ?)";

        // Prepare the statement
        $stmt = $conn->prepare($sql);

        // Bind the parameters
        $stmt->bind_param("ss", $username, $hashed_password);

        // Execute the query
        if ($stmt->execute()) {
            // Registration successful
            echo "Registration successful!  Please check your email to verify your account.";
            // Optionally redirect to a welcome page or login page
        } else {
            // Registration failed
            echo "Registration failed: " . $conn->error;
        }

        // Close the statement and connection
        $stmt->close();
        $conn->close();
    }
}
?>
