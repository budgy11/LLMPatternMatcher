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

// Database connection details (replace with your actual credentials)
$servername = "localhost";
$username = "your_username";
$password = "your_password";
$dbname = "your_database_name";

// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validate the form data (Important for security!)
    $username = trim($_POST["username"]);
    $email = trim($_POST["email"]);
    $password = trim($_POST["password"]);

    // Basic validation
    if (empty($username) || empty($email) || empty($password)) {
        $error = "All fields are required.";
    } elseif (
        !preg_match("/^[a-zA-Z0-9._-]+$/", $username) || // Username format
        !preg_match("/^[^\s@]+@[^\s@]+\.[^\s@]+$/", $email) || // Email format
        $password < 6 // Password length requirement
    ) {
        $error = "Invalid input. Please check your username, email, and password.";
    } else {
        // Hash the password (VERY IMPORTANT for security!)
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // Attempt to connect to the database
        $conn = new mysqli($servername, $username, $password, $dbname);

        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        // Prepare the SQL query
        $sql = "INSERT INTO users (username, email, password) VALUES (?, ?, ?)";

        // Use prepared statements to prevent SQL injection
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ss", $username, $email, $hashed_password);

        // Execute the query
        if ($stmt->execute()) {
            // Registration successful
            echo "Registration successful! Please check your email to verify your account.";
        } else {
            // Registration failed
            echo "Registration failed: " . $stmt->error;
        }

        // Close the statement and connection
        $stmt->close();
        $conn->close();
    }
}
?>
