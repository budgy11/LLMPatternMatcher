
<!DOCTYPE html>
<html>
<head>
  <title>User Registration</title>
</head>
<body>

  <h1>User Registration</h1>

  <form method="post" action="">
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

// Database connection details (Replace with your actual credentials)
$dbHost = "localhost";
$dbName = "your_database_name";
$dbUser = "your_database_user";
$dbPass = "your_database_password";

// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Get form data
    $username = $_POST["username"];
    $email = $_POST["email"];
    $password = $_POST["password"];
    $confirm_password = $_POST["confirm_password"];

    // Validate the form data (Important for security!)
    $errors = [];

    // Username validation
    if (empty($username)) {
        $errors["username"] = "Username cannot be empty.";
    }
    if (strlen($username) < 3) {
        $errors["username"] = "Username must be at least 3 characters long.";
    }
    if (already_exists($username, $dbUser, $dbPass, $dbName)) {
        $errors["username"] = "Username already exists.";
    }

    // Email validation
    if (empty($email)) {
        $errors["email"] = "Email cannot be empty.";
    }
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors["email"] = "Invalid email format.";
    }

    // Password validation
    if (empty($password)) {
        $errors["password"] = "Password cannot be empty.";
    }
    if (strlen($password) < 8) {
        $errors["password"] = "Password must be at least 8 characters long.";
    }

    if (!empty($confirm_password) && $password != $confirm_password) {
        $errors["confirm_password"] = "Passwords do not match.";
    }

    // If no errors, proceed with registration
    if (empty($errors)) {
        // Hash the password using password_hash
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // Prepare the SQL query
        $sql = "INSERT INTO users (username, email, password) VALUES (?, ?, ?)";

        // Prepare the statement
        $stmt = $conn->prepare($sql);

        // Bind the parameters
        $stmt->bind_param("ss", $username, $hashed_password);

        // Execute the query
        if ($stmt->execute()) {
            // Registration successful
            echo "Registration successful! Please check your email for verification.";
            // Redirect to a welcome page or login page
            header("Location: welcome.php"); // Replace welcome.php with your desired destination
            exit();
        } else {
            // Query execution failed
            echo "Error: " . $stmt->error;
        }
    }
}

// Display the registration form
?>
