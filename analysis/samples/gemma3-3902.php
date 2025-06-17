    Username: <input type="text" name="username" required><br><br>
    Email: <input type="email" name="email" required><br><br>
    Password: <input type="password" name="password" required><br><br>
    Confirm Password: <input type="password" name="confirm_password" required><br><br>
    <button type="submit">Register</button>
  </form>

</body>
</html>


<?php

// Database Configuration (Replace with your actual credentials)
$db_host = "localhost";
$db_name = "your_database_name";
$db_user = "your_username";
$db_password = "your_password";

// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validate the form data (basic validation - expand as needed)
    $username = trim($_POST["username"]);
    $email = trim($_POST["email"]);
    $password = trim($_POST["password"]);

    // Input validation -  Crucial for security!
    if (empty($username) || empty($email) || empty($password)) {
        $errors = "All fields are required.";
    } elseif (strlen($username) < 3) {
        $errors = "Username must be at least 3 characters.";
    } elseif (filter_var($email, FILTER_VALIDATE_EMAIL) === false) {
        $errors = "Invalid email format.";
    } elseif (strlen($password) < 8) {
        $errors = "Password must be at least 8 characters.";
    }

    // Hash the password before storing it
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Connect to the database
    $conn = new mysqli($db_host, $db_user, $db_password, $db_name);

    // Check the connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Prepare the SQL query
    $sql = "INSERT INTO users (username, email, password) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($sql);

    if ($stmt) {
        $stmt->bind_param("sss", $username, $email, $hashed_password); // s = string
        $stmt->execute();

        // Check for errors
        if ($stmt->errno) {
            $errors = "Error registering user: " . $stmt->error;
        } else {
            $success = "Registration successful!";
        }
    } else {
        $errors = "Error preparing statement.";
    }

    // Close the statement and connection
    $stmt->close();
    $conn->close();
}
?>
