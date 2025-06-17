    <label for="username">Username:</label>
    <input type="text" id="username" name="username" placeholder="Enter your username">

    <br><br>

    <label for="email">Email:</label>
    <input type="email" id="email" name="email" placeholder="Enter your email">

    <br><br>

    <label for="password">Password:</label>
    <input type="password" id="password" name="password" placeholder="Enter your password">

    <br><br>

    <button type="submit">Register</button>
  </form>

</body>
</html>


<?php

// Database configuration (replace with your actual values)
$host = "localhost";
$username = "your_username";
$password = "your_password";
$database = "your_database_name";

// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Collect form data
    $username = $_POST["username"];
    $email = $_POST["email"];
    $password = $_POST["password"];
    $confirm_password = $_POST["confirm_password"];

    // Validate the input data
    $errors = [];

    // Username validation
    if (empty($username)) {
        $errors["username"] = "Username cannot be empty.";
    } elseif (strlen($username) < 3) {
        $errors["username"] = "Username must be at least 3 characters long.";
    }

    // Email validation
    if (empty($email)) {
        $errors["email"] = "Email cannot be empty.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors["email"] = "Invalid email format.";
    }

    // Password validation
    if (empty($password)) {
        $errors["password"] = "Password cannot be empty.";
    } elseif (strlen($password) < 8) {
        $errors["password"] = "Password must be at least 8 characters long.";
    } elseif ($password != $confirm_password) {
        $errors["password"] = "Passwords must match.";
    }

    // If there are errors, display them
    if (!empty($errors)) {
        echo "<div class='error'>Error: " . $errors["username"] . " - " . $errors["email"] . " - " . $errors["password"] . "</div>";
    } else {
        // Prepare the SQL query
        $sql = "INSERT INTO users (username, email, password) VALUES (?, ?, ?)";

        // Prepare the statement
        $stmt = $conn->prepare($sql);

        // Hash the password
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // Bind the parameters
        $stmt->bind_param("sss", $username, $email, $hashed_password);

        // Execute the statement
        if ($stmt->execute()) {
            // Registration successful
            echo "<div class='success'>Registration successful!</div>";
            // You might redirect here or display a welcome message.
        } else {
            // Registration failed
            echo "<div class='error'>Registration failed: " . $stmt->error . "</div>";
        }

        // Close the statement
        $stmt->close();
    }
}

// Start the session
session_start();

// Function to connect to the database
function connectToDatabase() {
    $conn = new mysqli($host, $username, $password, $database);

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    return $conn;
}

// Instantiate the database connection
$conn = connectToDatabase();

?>
