    <br><br>

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

// Form handling - check for POST request
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Validate inputs
    $username = trim($_POST["username"]);
    $email = trim($_POST["email"]);
    $password = trim($_POST["password"]);

    // Basic validation
    if (empty($username) || empty($email) || empty($password)) {
        $error = "All fields are required.";
    } elseif (
        !filter_var($email, FILTER_VALIDATE_EMAIL)
    ) {
        $error = "Invalid email format.";
    } elseif (strlen($password) < 8) {
        $error = "Password must be at least 8 characters long.";
    } else {
        // Prepare the SQL query - IMPORTANT: Use prepared statements to prevent SQL injection
        $sql = "INSERT INTO users (username, email, password) VALUES (?, ?, ?)";
        $stmt = $conn->prepare($sql); // Assuming $conn is your database connection object
        $stmt->bind_param("ss", $username, $password);  // "ss" indicates two strings
        $stmt->execute();

        // Success message
        $success = "Registration successful. Please log in.";

        // Clear form fields after successful registration
        $username = "";
        $email = "";
        $password = "";
    }
}
?>
