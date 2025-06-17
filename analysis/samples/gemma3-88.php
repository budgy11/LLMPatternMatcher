

<?php

/**
 * User Login Function
 *
 * This function handles user login verification and returns data if successful.
 *
 * @param string $username The username provided by the user.
 * @param string $password The password provided by the user.
 * @return array|null  An array containing user data if login is successful, 
 *                       or null if login fails.
 */
function loginUser(string $username, string $password): ?array {
  // **IMPORTANT:  NEVER STORE PASSWORDS IN PLAIN TEXT!**
  // This is just for demonstration purposes.  In a real application,
  // you would hash the password before storing it in the database.

  // **Dummy User Data (Replace with your database query)**
  $users = [
    ['username' => 'john.doe', 'password' => 'securepassword123'],
    ['username' => 'jane.smith', 'password' => 'anotherpass'],
  ];

  // Loop through the users and find a match
  foreach ($users as $user) {
    if ($user['username'] === $username && password_verify($password, $user['password'])) {
      // Login successful - Return user data
      return [
        'username' => $user['username'],
        'id' => 123, // Example user ID (replace with your database ID)
        'role' => 'admin', // Example user role (replace with your database role)
      ];
    }
  }

  // Login failed
  return null;
}

// **Example Usage**

// Get username and password from the form (example)
$username = $_POST['username']; // Or $_GET, etc.
$password = $_POST['password'];

// Perform the login
$user_data = loginUser($username, $password);

if ($user_data) {
  // Login successful - Redirect or display user information
  echo "Login successful!<br>";
  echo "Username: " . htmlspecialchars($user_data['username']) . "<br>";
  echo "User ID: " . htmlspecialchars($user_data['id']) . "<br>";
  echo "Role: " . htmlspecialchars($user_data['role']) . "<br>";

  // Optionally, create a session for the user
  session_start();
  $_SESSION['user_id'] = $user_data['id'];
  $_SESSION['username'] = $user_data['username'];

} else {
  // Login failed
  echo "Invalid username or password.<br>";
}


// **Important Security Considerations and Best Practices:**

// 1. **Hashing Passwords:**  NEVER store passwords in plain text.  Use a strong hashing algorithm like `password_hash()` to securely hash the password before storing it in the database.
//    - `password_hash($password, PASSWORD_DEFAULT);`  This creates a secure, one-way hash of the password.
//    - Use `password_verify($password, $hashed_password)` to verify the password during login.

// 2. **Input Validation and Sanitization:**  Always validate and sanitize user input to prevent SQL injection and cross-site scripting (XSS) attacks.
//    - Use `htmlspecialchars()` to escape output to prevent XSS.
//    - Use prepared statements or parameterized queries to prevent SQL injection.

// 3. **Rate Limiting:** Implement rate limiting to prevent brute-force attacks.

// 4. **Two-Factor Authentication (2FA):**  Consider adding 2FA for enhanced security.

// 5. **Session Management:** Use sessions to keep track of the logged-in user. Use `session_start()` before accessing session variables.

// 6. **Error Handling:** Implement robust error handling to gracefully handle login failures and other errors.

?>
