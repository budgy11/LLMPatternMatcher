    <label for="firstname">First Name:</label>
    <input type="text" id="firstname" name="firstname" placeholder="Enter your first name" required>

    <br><br>

    <label for="lastname">Last Name:</label>
    <input type="text" id="lastname" name="lastname" placeholder="Enter your last name" required>

    <br><br>

    <label for="email">Email:</label>
    <input type="email" id="email" name="email" placeholder="Enter your email" required>

    <br><br>

    <label for="password">Password:</label>
    <input type="password" id="password" name="password" placeholder="Enter your password" required>

    <br><br>

    <label for="confirm_password">Confirm Password:</label>
    <input type="password" id="confirm_password" name="confirm_password" placeholder="Confirm your password" required>

    <br><br>

    <button type="submit">Register</button>
  </form>

</body>
</html>


<?php

// Include the database connection file
require_once 'config.php'; // Assumes you have a config.php file with database credentials

/**
 * Registers a new user in the database.
 *
 * @param string $username The desired username.
 * @param string $password The desired password.
 * @param string $email The user's email address.
 *
 * @return bool True on successful registration, false otherwise.
 */
function registerUser($username, $password, $email) {
    // Input validation (Basic - Expand for more robust validation)
    if (empty($username) || empty($password) || empty($email)) {
        return false;
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        return false;
    }
    // Hash the password
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Prepare the SQL query
    $sql = "INSERT INTO users (username, password, email) VALUES (?, ?, ?)";

    // Prepare the statement
    $stmt = $conn->prepare($sql);

    // Bind the parameters
    $stmt->bind_param("sss", $username, $hashed_password, $email);  // "sss" means 3 string parameters

    // Execute the query
    if ($stmt->execute()) {
        return true;
    } else {
        // Handle errors
        error_log("Error: " . $stmt->error); // Log the error for debugging
        return false;
    }
    // Close the statement
    $stmt->close();
}


// Example Usage:
// $username = "newuser";
// $password = "SecurePassword123!";
// $email = "user@example.com";

// if (registerUser($username, $password, $email)) {
//     echo "User registration successful!";
// } else {
//     echo "User registration failed.";
// }
?>
