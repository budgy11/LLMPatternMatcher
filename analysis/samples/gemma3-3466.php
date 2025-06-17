    <br><br>

    <button type="submit">Register</button>
  </form>

  <p>Already have an account? <a href="login.php">Login</a></p> <!-- Replace login.php with your login page -->

</body>
</html>


<?php

// Database connection details (replace with your actual credentials)
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
    $confirm_password = $_POST["confirm_password"];

    // Validate form data
    $errors = [];

    // Username validation
    if (empty($username)) {
        $errors[] = "Username cannot be empty.";
    }
    if (strlen($username) < 3) {
        $errors[] = "Username must be at least 3 characters long.";
    }

    // Email validation
    if (empty($email)) {
        $errors[] = "Email cannot be empty.";
    }
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Invalid email format.";
    }

    // Password validation
    if (empty($password)) {
        $errors[] = "Password cannot be empty.";
    }
    if (strlen($password) < 8) {
        $errors[] = "Password must be at least 8 characters long.";
    }
    if ($password != $confirm_password) {
        $errors[] = "Passwords do not match.";
    }

    // If no errors, register the user
    if (empty($errors)) {
        // Hash the password (Important for security)
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // Prepare the SQL query
        $sql = "INSERT INTO users (username, email, password) VALUES (?, ?, ?)";

        // Prepare the statement
        $stmt = $conn->prepare($sql);

        // Bind the parameters
        $stmt->bind_param("sss", $username, $email, $hashed_password);

        // Execute the statement
        if ($stmt->execute()) {
            echo "Registration successful!  You have been redirected.";
            // Redirect the user to a success page or login page
            header("Location: login.php"); // Or your desired redirect
            exit();
        } else {
            echo "Error registering: " . $stmt->error;
        }
    }
}

// Display the registration form
?>
