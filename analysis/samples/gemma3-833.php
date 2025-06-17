        <label for="username">Username:</label>
        <input type="text" id="username" name="username">
        <br><br>

        <label for="password">Password:</label>
        <input type="password" id="password" name="password">
        <br><br>

        <button type="submit">Login</button>
    </form>

    <br>
    <a href="register.php">Don't have an account? Register here.</a>
</body>
</html>


   // During registration:
   $password = $_POST["password"];
   $hashed_password = password_hash($password, PASSWORD_DEFAULT);
   $sql = "INSERT INTO users (username, password) VALUES ('$username', '$hashed_password')";
   

* **Prepared Statements:**  For even better security, use prepared statements (using `mysqli_prepare()` and `mysqli_stmt_execute()`) to prevent SQL injection attacks.  This is especially important if you're taking user input and inserting it into a query.

* **Session Management:**  This example uses sessions to keep the user logged in. Ensure that your session handling is properly configured and that sessions are destroyed when the user logs out.  Consider using `session_start()` at the beginning of your script.

* **CSRF Protection:** Implement CSRF (Cross-Site Request Forgery) protection to prevent malicious websites from submitting forms on behalf of a logged-in user.

* **Rate Limiting:**  Implement rate limiting to prevent brute-force attacks.

* **HTTPS:**  Always use HTTPS to encrypt all traffic between the user's browser and your server.

**How to Use:**

1. **Create a Database:** Create a MySQL database and a `users` table with at least `username` and `password` columns.
2. **Database Credentials:** Replace the placeholder values for `$db_host`, `$db_name`, `$db_user`, and `$db_password` with your actual database credentials.
3. **Create `register.php`:** Create a file named `register.php` to handle user registration. This will include form fields for username and password, and will insert the user's data into the `users` table.
4. **Save the Login Script:** Save the login script (the code above) as a `.php` file (e.g., `login.php`).
5. **Run the Login Script:** Access the login script in your web browser (e.g., `http://localhost/login.php`).

**Example `register.php` (Basic):**



<?php

// Database credentials - IMPORTANT: Replace with your actual credentials!
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

  // Prepare the SQL query - Using prepared statements for security
  $sql = "SELECT * FROM users WHERE username = ? AND password = ?";
  $stmt = $conn->prepare($sql);

  if ($stmt === false) {
    die("Error preparing statement: " . $conn->error);
  }

  // Bind parameters to the SQL query - IMPORTANT for security
  $stmt->bind_param("ss", $username, $password);

  // Execute the query
  if (!$stmt->execute()) {
    die("Error executing query: " . $stmt->error);
  }

  // Get the result
  $result = $stmt->get_result();

  // Check if any rows were returned
  if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    // Successful login
    $_SESSION['user_id'] = $row['id']; // Store user ID in session
    $_SESSION['username'] = $username;
    return true;
  } else {
    // Login failed
    return false;
  }

  // Close the statement and connection
  $stmt->close();
  $conn->close();
}


// Example Usage:

// Check if the user is already logged in
if (isset($_SESSION['user_id'])) {
  echo "Welcome, " . $_SESSION['username'] . "!";
} else {
  // Check if the form has been submitted
  if (isset($_POST['username']) && isset($_POST['password'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Validate input (VERY IMPORTANT - Add more validation here)
    if (empty($username) || empty($password)) {
      echo "Username and password cannot be empty.";
    } else {
      // Attempt to login
      if (loginUser($username, $password)) {
        echo "Login successful!";
        // Redirect to a protected page, or display a welcome message
        header("Location: /protected_page.php"); // Replace with your protected page URL
        exit();
      } else {
        echo "Invalid username or password.";
      }
    }
  } else {
    // Display the login form
    echo "<h2>Login</h2>";
    echo "<form method='post'>";
    echo "<label for='username'>Username:</label><br>";
    echo "<input type='text' id='username' name='username'><br>";
    echo "<label for='password'>Password:</label><br>";
    echo "<input type='password' id='password' name='password'><br>";
    echo "<button type='submit'>Login</button>";
    echo "</form>";
  }
}

?>
