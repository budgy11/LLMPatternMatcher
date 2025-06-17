    <label for="username">Username:</label>
    <input type="text" id="username" name="username" required>
    <br><br>

    <label for="password">Password:</label>
    <input type="password" id="password" name="password" required>
    <br><br>

    <label for="email">Email:</label>
    <input type="email" id="email" name="email" required>
    <br><br>

    <button type="submit">Register</button>
</form>

</body>
</html>


<?php

// Database connection details (Replace with your actual credentials)
$host = "localhost";
$username = "your_username";
$password = "your_password";
$database = "your_database";

// Form handling - Check if the registration form was submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Validate and sanitize input (VERY IMPORTANT!)
    $username = trim($_POST["username"]);
    $email = trim($_POST["email"]);
    $password = trim($_POST["password"]);

    // Basic validation - Add more robust validation as needed
    if (empty($username) || empty($email) || empty($password)) {
        $errors = "All fields are required.";
    } elseif (strlen($username) < 3) {
        $errors = "Username must be at least 3 characters long.";
    } elseif (preg_match("/@/", $email)) {
        $errors = "Invalid email format.";
    } elseif (strlen($password) < 8) {
        $errors = "Password must be at least 8 characters long.";
    } else {
        // Hash the password (VERY IMPORTANT for security)
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // Prepare the SQL query
        $sql = "INSERT INTO users (username, email, password) VALUES (?, ?, ?)";

        // Prepare the statement
        $stmt = $conn->prepare($sql);

        // Bind the parameters
        $stmt->bind_param("ss", $username, $hashed_password);

        // Execute the query
        if ($stmt->execute()) {
            $success = "Registration successful!  Please check your email to activate your account.";
        } else {
            $errors = "Registration failed. " . $stmt->error;
        }
    }
}

// Function to display errors
function displayErrors($errors) {
    if (!empty($errors)) {
        echo "<div class='error-messages'>";
        echo $errors;
        echo "</div>";
    }
}

// Display the registration form
?>
