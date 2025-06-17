    <label for="firstname">First Name:</label>
    <input type="text" id="firstname" name="firstname" required><br><br>

    <label for="lastname">Last Name:</label>
    <input type="text" id="lastname" name="lastname" required><br><br>

    <label for="email">Email:</label>
    <input type="email" id="email" name="email" required><br><br>

    <label for="password">Password:</label>
    <input type="password" id="password" name="password" required><br><br>

    <input type="submit" value="Register">
</form>

</body>
</html>


<?php

// Database connection details - REPLACE WITH YOUR ACTUAL CREDENTIALS
$host = "localhost";
$username = "your_username";
$password = "your_password";
$database = "your_database";

// Form data handling - this is a simplified example.  In a real app, you'd use proper form validation and sanitization.
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST["username"];
    $password = $_POST["password"];
    $email = $_POST["email"];

    // Basic validation (replace with more robust validation)
    if (empty($username) || empty($password) || empty($email)) {
        $error = "All fields are required.";
    } elseif (strlen($username) < 3) {
        $error = "Username must be at least 3 characters long.";
    } elseif (preg_match("/@/", $email)) {
        $error = "Invalid email format.";
    }

    // Database query
    $conn = new mysqli($host, $username, $password, $database);

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Prepare the SQL query
    $sql = "INSERT INTO users (username, password, email) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($sql);

    if ($stmt) {
        $stmt->bind_param("sss", $username, $password, $email); // 's' for string, 'i' for integer (if needed)
        if ($stmt->execute()) {
            $success = "Registration successful. Please login.";
            // You might redirect here or display a success message
        } else {
            $error = "Registration failed: " . $stmt->error;
        }
    } else {
        $error = "Error preparing statement.";
    }

    // Close the connection
    $conn->close();
}
?>
