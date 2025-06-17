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

// Database configuration (Replace with your actual values)
$db_host = "localhost";
$db_name = "your_database_name";
$db_user = "your_db_user";
$db_password = "your_db_password";

// Function to register a user
function registerUser($username, $password, $email) {
    // 1. Validate Inputs (Important for security!)
    if (empty($username) || empty($password) || empty($email)) {
        return "Error: All fields are required.";
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        return "Error: Invalid email address.";
    }

    // Sanitize inputs to prevent SQL injection
    $username = mysqli_real_escape_string($GLOBALS['conn'], $username);
    $password = mysqli_real_escape_string($GLOBALS['conn'], $password);
    $email = mysqli_real_escape_string($GLOBALS['conn'], $email);

    // 2. Database Query
    $sql = "INSERT INTO users (username, password, email) VALUES ('$username', '$password', '$email')";

    if ($GLOBALS['conn']->query($sql) === TRUE) {
        return "Registration successful!  You have been redirected.";
    } else {
        return "Error: " . $sql . "<br>" . $GLOBALS['conn']->error;
    }
}


// Example Usage (This would typically be in a form submission handler)
//  In a real application, this would be part of a form processing handler.
//  This is just for demonstration.

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST["username"];
    $password = $_POST["password"];
    $email = $_POST["email"];

    // Call the registration function
    $registration_result = registerUser($username, $password, $email);

    // Display the result
    echo "<p>" . $registration_result . "</p>";
}
?>
