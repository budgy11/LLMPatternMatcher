    Username: <input type="text" name="username" required>
    Password: <input type="password" name="password" required>
    <button type="submit">Login</button>
  </form>

  <p>Don't have an account? <a href="register.php">Register</a></p>  <!-- Link to a registration page -->

</body>
</html>


     $hashed_password = password_hash($password, PASSWORD_DEFAULT);
     // Then, insert $hashed_password into the database.
     

3. **Input Validation and Sanitization:**
   - **ALWAYS** validate and sanitize user input *before* using it in any query or operation. This includes:
     - Checking the data type and format (e.g., ensure the username is alphanumeric).
     - Using `filter_var()` to sanitize data (e.g., `filter_var($username, FILTER_SANITIZE_STRING)`).
     - Escaping any output before displaying it to prevent XSS vulnerabilities.

4. **CSRF Protection:** Implement Cross-Site Request Forgery (CSRF) protection. This is a common web security vulnerability that involves attackers tricking a user's browser into submitting malicious requests to your website without their knowledge.

5. **Session Management:**
   -  Set an expiration time for session variables to prevent session hijacking.
   -  Implement session regeneration after login to further enhance security.

6. **Logging:** Implement logging to track login attempts (successful and failed) for security auditing and troubleshooting.

7. **Rate Limiting:**  Implement rate limiting to prevent brute-force attacks (e.g., limiting the number of login attempts from a single IP address).

8. **Testing:** Thoroughly test your login functionality, including:
   - Successful login
   - Failed login (invalid credentials)
   - Brute-force attack attempts
   - CSRF attacks

9. **HTTPS:**  Always use HTTPS to encrypt the communication between the user's browser and your server.

**Example of PDO (for modern development):**



<?php

// Database credentials (Replace with your actual values)
$db_host = "localhost";
$db_user = "your_username";
$db_password = "your_password";
$db_name = "your_database_name";

// Function to handle user login
function loginUser($username, $password) {
  // Connect to the database
  $conn = new mysqli($db_host, $db_user, $db_password, $db_name);

  // Check connection
  if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
  }

  // Prepare the SQL query
  $query = "SELECT * FROM users WHERE username = ? AND password = ?";

  // Prepare the statement (important for security - prevents SQL injection)
  $stmt = $conn->prepare($query);

  // Bind the parameters
  $stmt->bind_param("ss", $username, $password); // "ss" indicates two string parameters

  // Execute the query
  if ($stmt->execute()) {
    // Get the result
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
      $row = $result->fetch_assoc();
      // User found, set session variables
      $_SESSION["user_id"] = $row["id"];
      $_SESSION["username"] = $row["username"];
      return true; // Login successful
    } else {
      return false; // User not found
    }

  } else {
    return false; // Query error
  }

  // Close the statement and connection
  $stmt->close();
  $conn->close();
}



// Example usage:
// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  // Get form data
  $username = $_POST["username"];
  $password = $_POST["password"];

  // Validate input (VERY IMPORTANT - add more robust validation here)
  if (empty($username) || empty($password)) {
    echo "Username and password must be filled.";
  } else {
    // Attempt to login
    if (loginUser($username, $password)) {
      // Redirect to a secure page or display a welcome message
      header("Location: /welcome.php");  // Adjust path as needed
      exit();
    } else {
      echo "Invalid username or password.";
    }
  }
}
?>
