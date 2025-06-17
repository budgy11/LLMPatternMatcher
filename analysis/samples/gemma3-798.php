     

     - `prepare()`:  Prepares the SQL statement, separating the query structure from the data.
     - `bind_param()`:  Binds the variables to the prepared statement, using the correct data types ("s" for string, "i" for integer, etc.).  This prevents SQL injection.
     - `execute()`: Executes the prepared statement with the bound parameters.
     - `get_result()`: Retrieves the result set from the prepared statement.
     - This approach is *much* more secure than directly embedding user input into the SQL query string.

   - **Password Hashing:**  *Never* store passwords in plain text in your database.  Instead, you *must* hash them using a strong hashing algorithm (like bcrypt or Argon2) *before* storing them.  This is crucial for security.

   - **HTTPS:**  Always use HTTPS to encrypt the connection between the user's browser and your server, protecting usernames and passwords in transit.

   - **Rate Limiting:** Implement rate limiting to prevent brute-force attacks.

   - **Regular Security Audits:** Regularly review your code and security practices.

**File Structure (Suggested):**



<?php

/**
 * User Login Function
 *
 * This function handles user login based on provided credentials.
 *
 * @param string $username The username provided by the user.
 * @param string $password The password provided by the user.
 * @return int|false  The user ID if login is successful, false otherwise.
 */
function loginUser(string $username, string $password) {
  // **Database Connection (Replace with your actual connection details)**
  // This is a placeholder.  You *must* replace this with your database connection.
  $db = new PDO('mysql:host=localhost;dbname=your_database_name', 'your_username', 'your_password');

  // **Authentication Logic**
  //  This is where you'd perform the actual authentication.
  //  This example uses a hardcoded user and password for demonstration only.
  //  You should *never* do this in a real application.  Use a secure hashing method.

  $hashedPassword = "hashed_password_here"; //Replace with the hashed password from your database.  This is just a placeholder.

  if ($username == 'admin' && $password == 'password') {
    // Login successful!
    $stmt = $db->prepare("SELECT id FROM users WHERE username = ?");
    $stmt->execute([$username]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user) {
      // Store the user's session ID
      session_start(); // Make sure session is started!
      $_SESSION['userId'] = $user['id'];
      return $user['id'];
    } else {
      return false; // User not found
    }
  } else {
    return false; // Invalid credentials
  }
}

// **Example Usage (for testing)**
//This code is just to demonstrate how to use the function
// in a test environment or during development.

// Simulate a login attempt
$username = 'testuser';
$password = 'testpassword';

$userId = loginUser($username, $password);

if ($userId) {
  echo "Login successful! User ID: " . $userId . "<br>";
  // Redirect to a protected page or perform other actions here.
  // For example:
  // header("Location: protected_page.php");
  // exit();

} else {
  echo "Login failed.  Invalid username or password.<br>";
}

?>
