    <?php } ?>

    <br><br>

    <input type="submit" value="Register">
  </form>

</body>
</html>


<?php

// Include the database connection file (assuming you have one)
require_once 'db_connection.php'; // Replace with your actual file name

/**
 * Registers a new user in the database.
 *
 * @param string $username The desired username.
 * @param string $password The desired password.
 * @param string $email The user's email address.
 * @return bool True on success, false on failure.
 */
function registerUser($username, $password, $email) {

    // Input Validation (Important for security)
    if (empty($username) || empty($password) || empty($email)) {
        return false; // Require all fields
    }

    if (strlen($username) < 3 || strlen($username) > 20) {
        return false; // Username length constraints
    }
    if (strlen($password) < 6 || strlen($password) > 50) {
        return false; // Password length constraints
    }

    // Sanitize Inputs (Prevent SQL Injection)
    $username = mysqli_real_escape_string($conn, $username);
    $password = mysqli_real_escape_string($conn, $password);
    $email = mysqli_real_escape_string($conn, $email);

    // Hash the password (Crucial for security)
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Prepare the SQL query
    $sql = "INSERT INTO users (username, password, email) VALUES ('$username', '$hashed_password', '$email')";

    // Execute the query
    $result = mysqli_query($conn, $sql);

    if ($result) {
        return true;
    } else {
        // Handle query error (e.g., duplicate username)
        error_log("Error registering user: " . mysqli_error($conn)); // Log the error
        return false;
    }
}

// Example Usage (This would be in your form processing code)
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST["username"];
    $password = $_POST["password"];
    $email = $_POST["email"];

    if (registerUser($username, $password, $email)) {
        echo "User registered successfully!  You have been <a href='login.php'>logged in</a>.";
    } else {
        echo "Registration failed. Please try again.";
    }
}
?>
