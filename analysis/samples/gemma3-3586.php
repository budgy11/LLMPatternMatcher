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

// Database configuration (Replace with your actual database credentials)
$db_host = "localhost";
$db_name = "your_database_name";
$db_user = "your_db_user";
$db_password = "your_db_password";

// Function to register a new user
function registerUser($username, $password, $email) {
    // 1. Connect to the database
    $conn = new mysqli($db_host, $db_user, $db_password, $db_name);

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // 2. Prepare and execute the SQL query
    $hashed_password = password_hash($password, PASSWORD_DEFAULT); // Hash the password for security

    $sql = "INSERT INTO users (username, password, email) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($sql);

    if ($stmt) {
        $stmt->bind_param("ssi", $username, $hashed_password, $email);
        if ($stmt->execute()) {
            // 3. Success!  Return a success message
            return "Registration successful!  Please check your email for verification.";
        } else {
            // 4.  Handle errors. Important for debugging
            return "Registration failed: " . $conn->error;
        }
    } else {
        return "Error preparing statement.";
    }

    // Close the connection (important to release resources)
    $conn->close();
}


// Example usage (This would be triggered by a form submission)
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST["username"];
    $password = $_POST["password"];
    $email = $_POST["email"];

    // Validate input (IMPORTANT!  Always validate user input)
    if (empty($username) || empty($password) || empty($email)) {
        echo "All fields are required.";
    } elseif (strlen($username) < 3) {
        echo "Username must be at least 3 characters long.";
    } else {
        // Call the registerUser function
        $registration_result = registerUser($username, $password, $email);
        echo $registration_result; // Display the result of the registration
    }
}
?>
