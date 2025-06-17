

   $stmt = $conn->prepare("SELECT * FROM users WHERE username = ? AND password = ?");
   $stmt->bind_param("ss", $username, $password); // "ss" indicates two strings
   $stmt->execute();
   $result = $stmt->get_result();

   if ($result->num_rows > 0) {
       // ...
   } else {
       // ...
   }

   $stmt->close();
   

   - `prepare()`:  Prepares the SQL query for execution.
   - `bind_param()`:  Binds the variables `$username` and `$password` to the query placeholders (`?`).  The type of each variable (e.g., "s" for string, "i" for integer) is specified.
   - `execute()`:  Executes the prepared statement.
   - `get_result()`:  Retrieves the result set.
   - `close()`: Closes the statement.

* **Password Hashing:** **Never** store passwords in plain text in your database.  Instead, hash them using a strong hashing algorithm like bcrypt or Argon2.  bcrypt is a good choice because it's computationally expensive, making brute-force attacks much slower.

   
   // Example (using password_hash - you need to set salt)
   $password = password_hash($password, PASSWORD_DEFAULT);
   // ... insert $password into the database
   

   When a user tries to log in, you'll compare the hashed password from the database with the hashed password entered by the user:

   
   if (password_verify($password, $hashed_password_from_db)) {
       // Login successful
   } else {
       // Login failed
   }
   

* **Input Validation and Sanitization:**  Validate and sanitize all user input (including username and password) on the server-side to prevent unexpected behavior and potential security vulnerabilities.  At a minimum, ensure that the username and password meet certain length requirements and character restrictions.

* **Rate Limiting:** Implement rate limiting to prevent brute-force attacks.  This limits the number of login attempts allowed within a certain time period.

* **HTTPS:** Always use HTTPS (SSL/TLS) to encrypt the communication between the user's browser and your server, protecting user credentials during transmission.

* **Error Handling:** Implement robust error handling to gracefully handle potential errors (e.g., database connection errors, invalid input).  Don't display detailed error messages to users in a production environment, as they can reveal sensitive information.

* **Session Management:**  Use secure session management techniques.  Set the `session.cookie_secure` option in your `php.ini` to ensure that session cookies are only transmitted over HTTPS.  Use the `session_regenerate_id()` function to generate a new session ID after a user logs in, mitigating the risk of session fixation attacks.

This improved code provides a better starting point for a secure login function.  Remember to carefully implement all the security recommendations and adapt the code to your specific application requirements.  Consult the official PHP documentation and security resources for more information.


<?php

// Database Configuration (Replace with your actual credentials)
$db_host = "localhost";
$db_name = "your_database_name";
$db_user = "your_username";
$db_pass = "your_password";

// Function to handle user login
function loginUser($username, $password) {
  // Connect to the database
  $conn = new mysqli($db_host, $db_user, $db_pass, $db_name);

  // Check connection
  if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
  }

  // SQL query to check username and password
  $sql = "SELECT * FROM users WHERE username = '$username' AND password = '$password'";  // Use prepared statements for security!

  $result = $conn->query($sql);

  if ($result->num_rows > 0) {
    // User found, create a session
    session_start();
    $user = $result->fetch_assoc(); // Fetch the user data as an associative array

    // Store user data in session
    $_SESSION['user_id'] = $user['id'];
    $_SESSION['username'] = $user['username'];
    // You might want to store other user data here as needed

    return true; // Login successful
  } else {
    return false; // Login failed
  }

  $conn->close();
}

// Example Usage (This part is just for demonstrating how to call the function)

// Check if the login form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $username = $_POST["username"];
  $password = $_POST["password"];

  // Validate input (VERY IMPORTANT for security)
  if (empty($username) || empty($password)) {
    $error = "Username and password cannot be empty.";
  } elseif (!loginUser($username, $password)) {
    $error = "Invalid username or password.";
  }
}

// Display error message (if any)
if (isset($error)) {
  echo "<p style='color: red;'>$error</p>";
}

?>
