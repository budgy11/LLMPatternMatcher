
<!DOCTYPE html>
<html>
<head>
  <title>User Registration</title>
</head>
<body>

  <h1>User Registration</h1>

  <form action="register.php" method="POST">
    <label for="username">Username:</label>
    <input type="text" id="username" name="username" required>

    <br><br>

    <label for="email">Email:</label>
    <input type="email" id="email" name="email" required>

    <br><br>

    <label for="password">Password:</label>
    <input type="password" id="password" name="password" required>

    <br><br>

    <button type="submit">Register</button>
  </form>

</body>
</html>


<?php

// Database connection details (replace with your actual details)
$host = "localhost";
$username = "your_username";
$password = "your_password";
$database = "your_database_name";

// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get form data
    $firstname = $_POST["firstname"];
    $lastname = $_POST["lastname"];
    $email = $_POST["email"];
    $password = $_POST["password"];

    // Validate the data (Crucial for security!)
    $errors = [];

    // Check if required fields are empty
    if (empty($firstname)) {
        $errors["firstname"] = "First name cannot be empty.";
    }
    if (empty($lastname)) {
        $errors["lastname"] = "Last name cannot be empty.";
    }
    if (empty($email)) {
        $errors["email"] = "Email cannot be empty.";
    }
    if (strlen($email) < 5) {
        $errors["email"] = "Email must be at least 5 characters long.";
    }

    if (empty($password)) {
        $errors["password"] = "Password cannot be empty.";
    }

    if (strlen($password) < 8) {
        $errors["password"] = "Password must be at least 8 characters long.";
    }

    // Check if email is valid (basic validation - more robust validation is recommended)
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors["email"] = "Invalid email format.";
    }

    // If there are errors, do not proceed with registration
    if (!empty($errors)) {
        // Display errors (you can do this in a better way, like a form with error highlighting)
        echo "<div style='color: red;'>";
        echo "<ul>";
        foreach ($errors as $error => $message) {
            echo "<li>" . $message . "</li>";
        }
        echo "</ul>";
        echo "</div>";
        exit; // Stop further execution
    }

    // Prepare the SQL query
    $sql = "INSERT INTO users (firstname, lastname, email, password) VALUES (?, ?, ?, ?)";

    // Prepare the statement
    $stmt = $conn->prepare($sql);

    // Bind the parameters
    $stmt->bind_param("ssis", $firstname, $lastname, $password);

    // Execute the statement
    if ($stmt->execute()) {
        // Registration successful
        echo "<h2>Registration successful!</h2>";
        echo "<p>You have successfully created a new account.</p>";
        // Optionally redirect to a welcome page or login page
        // header("Location: welcome.php");
        // exit;
    } else {
        // Registration failed
        echo "<h2>Registration failed!</h2>";
        echo "<p>An error occurred during registration. Please try again later.</p>";
        // Log the error for debugging
        error_log("Registration error: " . $stmt->error);
    }

    // Close the statement
    $stmt->close();
}

// Start the database connection
$conn = new mysqli($host, $username, $password, $database);

// Check the connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

?>
