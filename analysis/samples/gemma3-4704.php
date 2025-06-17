        <label for="username">Username:</label>
        <input type="text" id="username" name="username" required><br><br>

        <label for="password">Password:</label>
        <input type="password" id="password" name="password" required><br><br>

        <label for="email">Email:</label>
        <input type="email" id="email" name="email" required><br><br>

        <button type="submit">Register</button>
    </form>

</body>
</html>


<?php

// Database credentials (replace with your actual values)
$db_host = "localhost";
$db_name = "your_database_name";
$db_user = "your_db_user";
$db_password = "your_db_password";

// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get form data
    $username = $_POST["username"];
    $email = $_POST["email"];
    $password = $_POST["password"];

    // Validation (Important: Add more robust validation here)
    if (empty($username) || empty($email) || empty($password)) {
        $errors = ["Username", "Email", "Password"] ;
        $error_message = "All fields are required.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors = ["Email"] ;
        $error_message = "Invalid email format.";
    } elseif (strlen($password) < 8) {
        $errors = ["Password"] ;
        $error_message = "Password must be at least 8 characters long.";
    }
    else {
        // Hash the password - NEVER store passwords in plain text!
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // SQL query to insert user data
        $sql = "INSERT INTO users (username, email, password) VALUES (?, ?, ?)";

        // Prepare the SQL statement
        $stmt = $conn->prepare($sql);

        // Bind the parameters
        $stmt->bind_param("ss", $username, $hashed_password);

        // Execute the query
        if ($stmt->execute()) {
            // Registration successful
            echo "Registration successful! Please check your email to activate your account.";
        } else {
            // Registration failed
            echo "Registration failed: " . $stmt->error;
        }
    }
}
?>
