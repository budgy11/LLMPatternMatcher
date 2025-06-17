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

// Database configuration (Replace with your actual credentials)
$host = "localhost";
$username = "your_username";
$password = "your_password";
$database = "your_database";

// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Get form data
    $username = $_POST["username"];
    $email = $_POST["email"];
    $password = $_POST["password"];
    $confirm_password = $_POST["confirm_password"];

    // Validation (Crucial for security)
    if (empty($username) || empty($email) || empty($password) || empty($confirm_password)) {
        $errors = "All fields are required.";
    } elseif (strlen($username) < 3) {
        $errors = "Username must be at least 3 characters.";
    } elseif (preg_match("/@/", $email)) {
        $errors = "Invalid email format.";
    } elseif ($password != $confirm_password) {
        $errors = "Passwords do not match.";
    }


    // Hash the password (Never store passwords in plain text!)
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);


    // Connect to the database
    $conn = new mysqli($host, $username, $password, $database);

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Prepare the SQL query
    $sql = "INSERT INTO users (username, email, password) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($sql);

    if ($stmt) {
        // Bind parameters
        $stmt->bind_param("ss", $username, $hashed_password);

        // Execute the query
        if ($stmt->execute()) {
            // Registration successful
            echo "Registration successful! Please check your email to activate your account.";
        } else {
            // Query failed
            echo "Query failed: " . $stmt->error;
        }

        // Close the statement
        $stmt->close();
    } else {
        echo "Error preparing statement.";
    }

    // Close the connection
    $conn->close();
}
?>
