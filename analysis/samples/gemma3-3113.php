
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

    <label for="email">Email:</label>
    <input type="email" id="email" name="email" required><br><br>

    <label for="password">Password:</label>
    <input type="password" id="password" name="password" required><br><br>

    <button type="submit">Register</button>
  </form>

</body>
</html>


<?php

// Database connection details (replace with your actual credentials)
$db_host = "localhost";
$db_username = "your_username";
$db_password = "your_password";
$db_name = "your_database";

// Form data validation
function validateRegistrationData($name, $email, $password) {
    // Validate name
    if (empty($name)) {
        return false;
    }

    // Validate email
    if (empty($email)) {
        return false;
    }
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        return false;
    }

    // Validate password
    if (strlen($password) < 6) {
        return false;
    }
    return true;
}

// Register user function
function registerUser($name, $email, $password) {
    global $db_host, $db_username, $db_password, $db_name;

    // Validate data
    if (!validateRegistrationData($name, $email, $password)) {
        return false; // Indicate registration failure
    }

    // Hash the password (important for security!)
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Connect to the database
    $conn = new mysqli($db_host, $db_username, $db_password, $db_name);

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Prepare the SQL query
    $sql = "INSERT INTO users (name, email, password) VALUES (?, ?, ?)";

    // Prepare statement
    $stmt = $conn->prepare($sql);

    // Bind parameters
    $stmt->bind_param("ss", $name, $email, $hashed_password);

    // Execute the query
    if ($stmt->execute()) {
        $stmt->close();
        return true; // Indicate registration success
    } else {
        $stmt->close();
        return false; // Indicate registration failure
    }

    // Close connection
    $conn->close();
}


// Example Usage (this would typically be handled by a form submission)
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST["name"];
    $email = $_POST["email"];
    $password = $_POST["password"];

    if (registerUser($name, $email, $password)) {
        echo "Registration successful! Please check your email to verify.";
    } else {
        echo "Registration failed. Please try again.";
    }
}
?>
