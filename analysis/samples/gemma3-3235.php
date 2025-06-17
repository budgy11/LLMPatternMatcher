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

// Database Configuration (Replace with your actual credentials)
$db_host = "localhost";
$db_user = "your_db_user";
$db_pass = "your_db_password";
$db_name = "your_db_name";

// Function to register a new user
function registerUser($username, $password, $email) {
    // 1. Connect to the database
    $conn = new mysqli($db_host, $db_user, $db_pass, $db_name);

    // Check the connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // 2. Hash the password (IMPORTANT for security)
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // 3. Prepare the SQL query
    $sql = "INSERT INTO users (username, password, email) VALUES (?, ?, ?)";

    // 4. Prepare the statement
    $stmt = $conn->prepare($sql);

    // 5. Bind the parameters
    $stmt->bind_param("ss", $username, $hashed_password, $email);

    // 6. Execute the query
    if ($stmt->execute()) {
        // Registration successful
        return true;
    } else {
        // Registration failed
        return false;
    }

    // 7. Close the statement and connection
    $stmt->close();
    $conn->close();
}

// --- Example Usage (Demonstration - for testing) ---

// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get user input from the form
    $username = $_POST["username"];
    $password = $_POST["password"];
    $email = $_POST["email"];

    // Validate input (VERY IMPORTANT - add more validation as needed)
    if (empty($username) || empty($password) || empty($email)) {
        echo "<p>All fields are required.</p>";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo "<p>Invalid email address.</p>";
    } else {
        // Call the registration function
        if (registerUser($username, $password, $email)) {
            echo "<p>User registered successfully! You have been redirected.</p>";
            // Redirect to a success page or login page here
        } else {
            echo "<p>User registration failed.</p>";
        }
    }
}
?>
