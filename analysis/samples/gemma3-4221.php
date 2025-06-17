    <label for="name">Name:</label>
    <input type="text" id="name" name="name" required>

    <label for="email">Email:</label>
    <input type="email" id="email" name="email" required>

    <label for="password">Password:</label>
    <input type="password" id="password" name="password" required>

    <button type="submit">Register</button>
</form>

</body>
</html>


<?php

// Database connection details (Replace with your actual credentials)
$host = "localhost";
$username = "your_username";
$password = "your_password";
$database = "your_database_name";

// Check if the registration form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Get form data
    $username = $_POST["username"];
    $password = $_POST["password"];
    $email = $_POST["email"];
    $confirm_email = $_POST["confirm_email"];

    // Validate the form data
    $errors = [];

    // Username validation
    if (empty($username)) {
        $errors["username"] = "Username cannot be empty.";
    }

    // Password validation
    if (empty($password)) {
        $errors["password"] = "Password cannot be empty.";
    }
    if (strlen($password) < 8) {
        $errors["password"] = "Password must be at least 8 characters long.";
    }

    // Email validation
    if (empty($email)) {
        $errors["email"] = "Email cannot be empty.";
    }
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors["email"] = "Invalid email format.";
    }
    // Confirm email validation
    if (empty($confirm_email)) {
        $errors["confirm_email"] = "Confirm email cannot be empty.";
    }
    if ($password != $confirm_email) {
        $errors["confirm_email"] = "Passwords do not match.";
    }


    // If there are no errors, proceed with registration
    if (empty($errors)) {
        // Database connection
        $conn = new mysqli($host, $username, $password, $database);

        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        // Prepare the SQL query
        $sql = "INSERT INTO users (username, password, email) VALUES (?, ?, ?)";

        // Use prepared statement to prevent SQL injection
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sss", $username, $password, $email);  // "sss" represents three string parameters

        // Execute the query
        if ($stmt->execute()) {
            echo "Registration successful! Please check your email to verify your account.";
        } else {
            echo "Registration failed: " . $stmt->error;
        }

        // Close the statement and connection
        $stmt->close();
        $conn->close();
    }
}
?>
