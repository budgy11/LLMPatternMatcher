    <label for="username">Username:</label>
    <input type="text" id="username" name="username" required>
    <br><br>

    <label for="email">Email:</label>
    <input type="email" id="email" name="email" required>
    <br><br>

    <label for="password">Password:</label>
    <input type="password" id="password" name="password" required>
    <br><br>

    <label for="confirm_password">Confirm Password:</label>
    <input type="password" id="confirm_password" name="confirm_password" required>
    <br><br>

    <button type="submit">Register</button>
  </form>

</body>
</html>


<?php

// Database configuration (replace with your actual credentials)
$db_host = "localhost";
$db_name = "your_database_name";
$db_user = "your_database_user";
$db_password = "your_database_password";

// Function to register a new user
function registerUser($username, $password, $email) {
    // 1. Connect to the database
    $conn = new mysqli($db_host, $db_user, $db_password, $db_name);

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // 2. Sanitize and Validate Input (IMPORTANT!)
    //   -  Escape the username and email to prevent SQL injection
    $username = $conn->real_escape_string($username);
    $email = $conn->real_escape_string($email);

    // Add more validation rules here:
    // - Password strength (minimum length, special characters, etc.)
    // - Email format validation
    // - Check if username or email already exists
    

    // 3. Hash the password (VERY IMPORTANT for security!)
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // 4. Prepare and execute the SQL query
    $sql = "INSERT INTO users (username, password, email) VALUES ('$username', '$hashed_password', '$email')";

    if ($conn->query($sql) === TRUE) {
        // Success!
        return true;
    } else {
        // Error
        echo "Error: " . $sql . "<br>" . $conn->error;
        return false;
    }

    // 5. Close the connection
    $conn->close();
}

// Example Usage (Illustrative - This would be part of a form submission)
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST["username"];
    $password = $_POST["password"];
    $email = $_POST["email"];

    if (registerUser($username, $password, $email)) {
        echo "Registration successful!  You have been redirected.";
        // Redirect to a success page or login form
    } else {
        echo "Registration failed. Please try again.";
    }
}

?>
