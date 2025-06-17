

   $stmt = $conn->prepare("SELECT * FROM users WHERE username = ? AND password = ?");
   $stmt->bind_param("ss", $username, $password); // "ss" means two string parameters
   $stmt->execute();
   $result = $stmt->get_result();

   if ($result->num_rows > 0) {
       // ...
   }
   $stmt->close();
   

   - `bind_param()`:  Escapes the values, preventing malicious code from being injected into the query.

2. **Password Hashing:**
   - **Never store passwords in plain text.**  Always hash passwords before storing them in the database.
   - Use a strong hashing algorithm like `password_hash()`:

   
   // When creating a new user:
   $password = $_POST['password'];
   $hashed_password = password_hash($password, PASSWORD_DEFAULT);
   // Then, in your database, store $hashed_password instead of $password.

   // To verify the password during login:
   $hashed_password_from_db = $result->fetch_assoc()['password']; // Retrieve the hashed password from the database
   if (password_verify($password, $hashed_password_from_db)) {
       // Password is correct
   }
   

3. **CSRF Protection:**  Implement Cross-Site Request Forgery (CSRF) protection to prevent malicious websites from forging login requests on behalf of a logged-in user.  This typically involves using a unique token in the login form and verifying it on the server-side.

4. **HTTPS:**  Always use HTTPS to encrypt the communication between the user's browser and your server, protecting login credentials from eavesdropping.

5. **Input Validation and Sanitization:**  Validate and sanitize all user input (username, password) on the server-side.  This helps prevent various types of attacks and ensures data integrity.

6. **Limit Login Attempts:** Implement a mechanism to limit the number of failed login attempts to prevent brute-force attacks.  You could temporarily block the IP address of an attacker.

7. **Error Reporting:**  Disable detailed error reporting in a production environment to prevent exposing sensitive information to attackers.  Use logging instead for debugging.

**Complete, Secure Example (with Password Hashing - using prepared statements):**



<?php

// Database credentials (replace with your actual details)
$servername = "localhost";
$username = "your_username";
$password = "your_password";
$dbname = "your_database";

// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  // Get the username and password from the form
  $username = $_POST["username"];
  $password = $_POST["password"];

  // Connect to the database
  $conn = new mysqli($servername, $username, $password, $dbname);

  // Check connection
  if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
  }

  // SQL query to authenticate the user
  $sql = "SELECT * FROM users WHERE username = '$username' AND password = '$password'";
  $result = $conn->query($sql);

  // Check if the query returned any results
  if ($result->num_rows > 0) {
    // User found, set a session variable to indicate login
    session_start();
    $_SESSION["username"] = $username; // Store the username in the session
    echo "Login successful! You are now logged in.";
    // Redirect to a protected page or the homepage
    header("Location: /protected_page.php"); // Replace with your desired URL
    exit(); 
  } else {
    echo "Invalid username or password.";
  }

  $conn->close();
}

?>
