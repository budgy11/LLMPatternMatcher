

   $stmt = $conn->prepare("SELECT id, username, password FROM users WHERE username = ?");
   $stmt->bind_param("s", $username); // 's' indicates a string
   $stmt->execute();
   $result = $stmt->fetch_assoc();
   $stmt->close();
   
* **Session Management Security:**
    * Set the `session.cookie_lifetime` and `session.gc_maxlifetime` settings in your `php.ini` file to control session timeout values.
    * Use `session_regenerate_id()` to generate a new session ID on each login attempt to mitigate session fixation attacks.
* **Input Validation and Sanitization:**  Validate and sanitize all user input *before* using it in any database queries or other operations.
* **HTTPS:**  Always use HTTPS to encrypt communication between the user's browser and your server, protecting user credentials during transmission.
* **Rate Limiting:** Implement rate limiting to prevent brute-force attacks.

**Example of adding Password Hashing and Prepared Statements:**



<?php

// Database credentials (replace with your actual values)
$db_host = "localhost";
$db_name = "your_database_name";
$db_user = "your_username";
$db_password = "your_password";

// Function to handle user login
function loginUser($username, $password) {
  // Connect to the database
  $conn = new mysqli($db_host, $db_user, $db_password, $db_name);

  // Check connection
  if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
  }

  // SQL query to check username and password
  $sql = "SELECT * FROM users WHERE username = ? AND password = ?";

  // Prepare statement
  $stmt = $conn->prepare($sql);

  if ($stmt) {
    // Bind parameters (important for security)
    $stmt->bind_param("ss", $username, $password);

    // Execute the statement
    $stmt->execute();

    // Get the result
    $result = $stmt->get_result();

    // Check if a user was found
    if ($result->num_rows > 0) {
      $row = $result->fetch_assoc();
      //  You can store session variables here if needed
      // session_start();  // Ensure session is started
      // $_SESSION['user_id'] = $row['id']; // Replace 'id' with your user ID column
      // $_SESSION['username'] = $username;

      // Return true if login successful
      return true;
    } else {
      // Return false if no user found
      return false;
    }

    // Close statement
    $stmt->close();
  } else {
    // Handle statement preparation error
    return false;
  }
}

// Example usage:
//  This is just for demonstration, you'll typically get the username and password from a form
$username = $_POST['username'];
$password = $_POST['password'];

if (loginUser($username, $password)) {
  echo "Login successful!";
  // Redirect to a protected page or display a welcome message
  // header("Location: protected_page.php"); // Example
} else {
  echo "Invalid username or password.";
}

?>
