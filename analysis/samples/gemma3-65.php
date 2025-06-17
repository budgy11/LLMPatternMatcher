    Username: <input type="text" name="username" placeholder="Enter your username"><br><br>
    Password: <input type="password" name="password" placeholder="Enter your password"><br><br>
    <input type="submit" value="Login">
  </form>

  <p>Don't have an account? <a href="register.php">Register</a></p>  <!-- Link to registration page -->

</body>
</html>


<?php

// Database credentials (Replace with your actual values!)
$host = "localhost";
$username = "your_username";
$password = "your_password";
$database = "your_database";

// Function to handle login
function login($username, $password) {
    // Connect to the database
    $conn = new mysqli($host, $username, $password, $database);

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Prepare the SQL query
    $stmt = $conn->prepare("SELECT id, username, password FROM users WHERE username = ?");

    // Bind the username parameter
    $stmt->bind_param("s", $username);

    // Execute the query
    $stmt->execute();

    // Get the result
    $result = $stmt->get_result();

    // Check if a user was found
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        // Verify the password
        if (password_verify($password, $row["password"])) {
            // Authentication successful - Set session variables
            session_start();
            $_SESSION["user_id"] = $row["id"];
            $_SESSION["username"] = $row["username"];
            $result->free(); // Free up the result set
            return true;
        } else {
            $result->free();
            return false;
        }
    } else {
        $result->free();
        return false;
    }
}


// Example Usage (Demonstration - Don't expose this in a production environment!)
// This is just to show how you'd call the function.
// You would typically get the username and password from a form submission.

// Simulate a username and password
$username = "testuser";
$password = "password123";

if (login($username, $password)) {
    echo "Login successful!  You are now logged in as " . $_SESSION["username"];
} else {
    echo "Login failed.  Invalid username or password.";
}

//Important Security Notes:

//1. NEVER store passwords in plain text.
//   This example uses password_verify() to hash the password before comparing
//   with the stored hash.  ALWAYS use a strong hashing algorithm like
//   password_hash() when storing passwords.

//2. Input Validation and Sanitization:
//   Before using any user input (username, password, etc.), you **must**
//   validate and sanitize it to prevent SQL injection and other security vulnerabilities.
//   This is *crucial*.  Use prepared statements (as demonstrated) and
//   proper input validation functions.

//3. Session Security:
//   - `session_start()` is called at the beginning of the function to initiate the session.
//   - Use `session_regenerate_id()` periodically or after successful login to prevent session fixation attacks.
//   - Set appropriate session cookie attributes (HttpOnly, Secure, SameSite) for security.
//   - Implement proper session timeout settings.

//4. Error Handling:
//   - Implement robust error handling. Catch exceptions and display user-friendly error messages.
//   - Log errors for debugging purposes (but don't expose sensitive error details to the user).

//5. Password Complexity:
//   Enforce strong password policies (minimum length, character types) to improve security.

//6. Security Audits:
//   Regularly review your code and security practices to identify and address vulnerabilities.
?>
