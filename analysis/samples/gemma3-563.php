        <label for="username">Username:</label>
        <input type="text" id="username" name="username" placeholder="Enter your username">
        <br><br>
        <label for="password">Password:</label>
        <input type="password" id="password" name="password" placeholder="Enter your password">
        <br><br>
        <button type="submit">Login</button>
    </form>

    <br>
    <a href="register.php">Not a user? Register here.</a>

</body>
</html>


     $stmt = $conn->prepare("SELECT * FROM users WHERE username = ? AND password = ?");
     $stmt->bind_param("ss", $username, $password); // "ss" means two strings
     $stmt->execute();
     $result = $stmt->get_result();
     

2. **Password Hashing:**
   - **Never** store passwords in your database in plain text.  Always hash passwords before storing them.  Use a strong hashing algorithm like bcrypt or Argon2. PHP's `password_hash()` function is excellent for this:

     
     $password = $_POST["password"];
     $hashed_password = password_hash($password, PASSWORD_DEFAULT);
     // Store $hashed_password in the database
     

3. **Cross-Site Scripting (XSS) Prevention:**
   - Use `htmlspecialchars()` to escape special characters in user input before displaying it in your HTML. This prevents attackers from injecting malicious JavaScript code.

4. **CSRF Protection:**
   - Implement Cross-Site Request Forgery (CSRF) protection to prevent attackers from submitting forms on behalf of a logged-in user.

5. **Rate Limiting:**
   - Implement rate limiting to prevent brute-force attacks (repeated attempts to guess passwords).

6. **Error Handling:**
   - Implement proper error handling to prevent sensitive information from being revealed to users.

7. **HTTPS:**
   - Always use HTTPS to encrypt the communication between the user's browser and your server.


**Example of a `register.php` page (for completeness):**



<?php

// Database connection details (replace with your actual values)
$servername = "localhost";
$username = "your_username";
$password = "your_password";
$dbname = "your_database_name";

// Form data from the login form
$username = $_POST["username"];
$password = $_POST["password"];

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

// SQL query to select the user
$sql = "SELECT * FROM users WHERE username = '$username' AND password = '$password'"; // Use parameterized queries for security
$result = $conn->query($sql);

// Check if the query was successful
if ($result->num_rows > 0) {
  // User found, set session variables
  // You should also check other fields in the 'users' table if needed.
  $user_data = $result->fetch_assoc();

  session_start();
  $_SESSION['username'] = $username;
  $_SESSION['user_id'] = $user_data['id']; // Assuming you have an 'id' column in the 'users' table
  // You can add other user data to the session as needed.

  // Redirect the user to the protected page
  header("Location: protected_page.php");
  exit(); // Stop further script execution

} else {
  // User not found
  echo "Invalid username or password.";
}

$conn->close();

?>
