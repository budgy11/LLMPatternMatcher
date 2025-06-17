    <br><br>

    <label for="password">Password:</label>
    <input type="password" id="password" name="password" placeholder="Enter password" minlength="6">
    <br><br>

    <button type="submit">Register</button>
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

    // Validate the form data (IMPORTANT: Add more robust validation here)
    $username = trim($_POST["username"]);
    $email = trim($_POST["email"]);
    $password = trim($_POST["password"]);

    // Check if username and email are empty
    if (empty($username) || empty($email)) {
        $error[] = "Username and Email are required.";
    }

    // Check if password is empty
    if (empty($password)) {
        $error[] = "Password is required.";
    }

    // Validate email format (basic check)
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error[] = "Invalid Email format.";
    }

    // Hash the password (VERY IMPORTANT for security)
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Check if there are any errors
    if (empty($error)) {
        // Connect to the database
        $conn = new mysqli($servername, $username, $password, $dbname);

        // Check connection
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        // Prepare and execute the SQL query
        $sql = "INSERT INTO users (username, email, password) VALUES (?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ss", $username, $email, $hashed_password);
        $stmt->execute();

        // Success message
        $success = true;
        $success_message = "Registration successful.  Please check your email to verify your account.";
        $stmt->close();  // Close the statement to free up resources
    } else {
        // Error message
        $error_message = implode("<br>", $error);  // Combine errors with line breaks
    }
}
?>
