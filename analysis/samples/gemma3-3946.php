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

// Database connection details (replace with your actual details)
$dbHost = "localhost";
$dbUser = "your_db_user";
$dbPass = "your_db_password";
$dbName = "your_db_name";

// Check if the registration form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get form data
    $username = $_POST["username"];
    $password = $_POST["password"];
    $email = $_POST["email"];

    // Sanitize and validate the input data
    $username = filter_var($username, FILTER_SANITIZE_STRING);
    $email = filter_var($email, FILTER_SANITIZE_EMAIL);

    // Validate required fields
    if (empty($username) || empty($password) || empty($email)) {
        $error = "All fields are required.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Invalid email format.";
    } elseif (strlen($username) < 3) {
        $error = "Username must be at least 3 characters long.";
    }  else {
        // Hash the password using password_hash() - **Important for Security**
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        // Prepare the SQL query
        $sql = "INSERT INTO users (username, email, password) VALUES (?, ?, ?)";

        // Prepare the statement
        $stmt = $conn->prepare($sql);

        // Bind parameters
        $stmt->bind_param("sss", $username, $email, $hashedPassword);

        // Execute the statement
        if ($stmt->execute()) {
            // Registration successful
            echo "Registration successful!  Please verify your email.";
        } else {
            // Registration failed
            echo "Registration failed: " . $stmt->error;
        }
    }
}
?>
