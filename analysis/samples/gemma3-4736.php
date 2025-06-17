    Username: <input type="text" name="username" required><br><br>
    Password: <input type="password" name="password" required><br><br>
    Email: <input type="email" name="email" required><br><br>
    <input type="submit" value="Register">
</form>

</body>
</html>


<?php

// Database Configuration (Replace with your actual credentials)
$db_host = "localhost";
$db_name = "your_database_name";
$db_user = "your_username";
$db_password = "your_password";

// Check if the registration form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validate Input
    $username = trim($_POST["username"]);
    $email = trim($_POST["email"]);
    $password = trim($_POST["password"]);
    $confirm_password = trim($_POST["confirm_password"]);

    // Validation
    $errors = [];

    // Username Validation
    if (empty($username)) {
        $errors[] = "Username cannot be empty.";
    } elseif (strlen($username) < 3) {
        $errors[] = "Username must be at least 3 characters long.";
    }

    // Email Validation
    if (empty($email)) {
        $errors[] = "Email cannot be empty.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Invalid email format.";
    }

    // Password Validation
    if (empty($password)) {
        $errors[] = "Password cannot be empty.";
    } elseif (strlen($password) < 8) {
        $errors[] = "Password must be at least 8 characters long.";
    } elseif ($password != $confirm_password) {
        $errors[] = "Passwords do not match.";
    }

    // If no errors, proceed with registration
    if (empty($errors)) {
        // Prepare the SQL query
        $sql = "INSERT INTO users (username, email, password) 
                VALUES (?, ?, ?)";

        // Prepare the statement
        $stmt = $conn->prepare($sql);

        // Hash the password
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // Bind the parameters
        $stmt->bind_param("s", $username, $email, $hashed_password);

        // Execute the query
        if ($stmt->execute()) {
            // Registration successful
            echo "Registration successful!  Please check your email for verification.";
            // Redirect to a welcome page or login page
            header("Location: welcome.php");
            exit;
        } else {
            // Handle errors
            echo "Error: " . $stmt->error;
        }

        // Close the statement
        $stmt->close();
    }
}

// Display the registration form
?>
