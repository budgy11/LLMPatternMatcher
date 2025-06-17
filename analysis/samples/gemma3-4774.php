    Username: <input type="text" name="username" required><br><br>
    Password: <input type="password" name="password" required><br><br>
    Email: <input type="email" name="email" required><br><br>
    <input type="submit" value="Register">
</form>

</body>
</html>


<?php

// Database Configuration (Replace with your actual database details)
$db_host = 'localhost';
$db_name = 'your_database_name';
$db_user = 'your_username';
$db_password = 'your_password';

// Function to register a new user
function registerUser($username, $password, $email) {

    // Validate inputs (Crucial for security!)
    $username = trim($username);
    $password = trim($password);
    $email = trim($email);

    // Check if inputs are empty
    if (empty($username) || empty($password) || empty($email)) {
        return "Error: All fields are required.";
    }

    // Sanitize inputs - VERY IMPORTANT to prevent SQL injection
    $username = mysqli_real_escape_string($GLOBALS['conn'], $username);
    $password = mysqli_real_escape_string($GLOBALS['conn'], $password);
    $email = mysqli_real_escape_string($GLOBALS['conn'], $email);

    // Check if username or email already exists
    $check_sql = "SELECT * FROM users WHERE username = '$username' OR email = '$email'";
    $result = mysqli_query($GLOBALS['conn'], $check_sql);

    if (mysqli_num_rows($result) > 0) {
        return "Error: Username or email already exists.";
    }

    // Hash the password (IMPORTANT for security!)
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Insert the user into the database
    $insert_sql = "INSERT INTO users (username, password, email) VALUES ('$username', '$hashed_password', '$email')";

    if (mysqli_query($GLOBALS['conn'], $insert_sql)) {
        return "Registration successful! You have been sent a verification email.";
    } else {
        return "Error: Failed to register. Please try again.";
    }
}

// --- Example Usage (Demonstration - should be handled by a form submission)
// $registration_result = registerUser("john_doe", "P@$$wOrd", "john.doe@example.com");
// echo $registration_result;

// ---  Form Handling (This would be part of a web form processing script)
// Assuming you have a form with fields: username, password, email
// 1. Get user input from the form.
// 2. Call registerUser() with the input values.
// 3. Display the return value (success message or error message) to the user.


// --- Database Setup (Connection)
$conn = new mysqli($db_host, $db_user, $db_password, $db_name);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Table Structure (Create the 'users' table if it doesn't exist)
$create_table_sql = "CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(255) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL UNIQUE
)";

if (!$conn->query($create_table_sql)) {
    die("Error creating table: " . $conn->error);
}


?>
