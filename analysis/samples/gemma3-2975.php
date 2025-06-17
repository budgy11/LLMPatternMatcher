
<!DOCTYPE html>
<html>
<head>
  <title>User Registration</title>
</head>
<body>

  <h1>User Registration</h1>

  <form action="register.php" method="POST">
    <label for="firstname">First Name:</label>
    <input type="text" id="firstname" name="firstname" required><br><br>

    <label for="lastname">Last Name:</label>
    <input type="text" id="lastname" name="lastname" required><br><br>

    <label for="email">Email:</label>
    <input type="email" id="email" name="email" required><br><br>

    <label for="password">Password:</label>
    <input type="password" id="password" name="password" required><br><br>

    <label for="confirm_password">Confirm Password:</label>
    <input type="password" id="confirm_password" name="confirm_password" required><br><br>

    <button type="submit">Register</button>
  </form>

</body>
</html>


<?php

// Database configuration (replace with your actual database details)
$db_host = "localhost";
$db_user = "your_db_user";
$db_pass = "your_db_password";
$db_name = "your_db_name";

// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get form data
    $username = $_POST["username"];
    $email = $_POST["email"];
    $password = $_POST["password"];

    // Validate the form data (essential for security)
    $errors = [];

    // Username validation
    if (empty($username)) {
        $errors[] = "Username cannot be empty.";
    }
    if (strlen($username) < 3) {
        $errors[] = "Username must be at least 3 characters long.";
    }
    if (alreadyRegistered($username, $db_name)) {
        $errors[] = "Username already exists.";
    }


    // Email validation
    if (empty($email)) {
        $errors[] = "Email cannot be empty.";
    }
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Invalid email format.";
    }

    // Password validation (basic - consider stronger validation)
    if (empty($password)) {
        $errors[] = "Password cannot be empty.";
    }
    if (strlen($password) < 8) {
        $errors[] = "Password must be at least 8 characters long.";
    }


    // If there are errors, display them
    if (!empty($errors)) {
        echo "<h2>Error:</h2>";
        echo "<ol>";
        foreach ($errors as $error) {
            echo "<li>" . $error . "</li>";
        }
        echo "</ol>";
    } else {
        // Hash the password (VERY IMPORTANT for security)
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // Prepare the SQL query
        $sql = "INSERT INTO users (username, email, password) VALUES (?, ?, ?)";
        $stmt = $conn->prepare($sql); // Use prepared statements to prevent SQL injection

        // Bind the parameters
        $stmt->bind_param("sss", $username, $email, $hashed_password); // 's' for string, 'i' for integer, etc.

        // Execute the query
        if ($stmt->execute()) {
            // Registration successful
            echo "<h2>Registration Successful!</h2>";
            echo "<p>You have successfully registered.  You can now log in.</p>";
            // Redirect to login page (or another appropriate page)
            header("Location: login.php");
            exit();
        } else {
            // Query execution failed
            echo "<h2>Registration Failed</h2>";
            echo "<p>An error occurred while registering.  Please try again.</p>";
            echo "<p>Error: " . $conn->error . "</p>";
        }

        $stmt->close();
    }
}
?>
