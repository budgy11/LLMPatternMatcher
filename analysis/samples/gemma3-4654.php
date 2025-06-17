
        <br><br>

        <label for="password">Password:</label>
        <input type="password" id="password" name="password" placeholder="Enter password">

        <br><br>

        <button type="submit">Register</button>
    </form>

</body>
</html>


<?php

// Database Configuration (Replace with your actual credentials)
$db_host = "localhost";
$db_name = "your_database_name";
$db_user = "your_username";
$db_pass = "your_password";

// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Get form data
    $username = $_POST["username"];
    $email = $_POST["email"];
    $password = $_POST["password"];

    // Validate the data (Important for security!)
    if (empty($username) || empty($email) || empty($password)) {
        $errors = "All fields are required.";
    } elseif (
        !preg_match("/^[a-zA-Z0-9_]+$/", $username) || // Username validation
        !filter_var($email, FILTER_VALIDATE_EMAIL)
    ) {
        $errors = "Invalid email or username format.";
    } elseif (strlen($password) < 8) {
        $errors = "Password must be at least 8 characters long.";
    }
    
    // Hash the password (Crucial for security!)
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Connect to the database
    $conn = new mysqli($db_host, $db_user, $db_pass, $db_name);

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Prepare the SQL query
    $sql = "INSERT INTO users (username, email, password) VALUES (?, ?, ?)";

    // Use prepared statements to prevent SQL injection
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sss", $username, $email, $hashed_password);

    // Execute the query
    if ($stmt->execute()) {
        // Registration successful
        echo "Registration successful! Please check your email to activate your account.";
    } else {
        // Registration failed
        echo "Registration failed: " . $stmt->error;
    }

    // Close the statement and connection
    $stmt->close();
    $conn->close();
}
?>
