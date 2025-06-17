    Username: <input type="text" name="username" placeholder="Your Username"><br><br>
    Password: <input type="password" name="password" placeholder="Your Password"><br><br>
    <input type="submit" value="Login">
</form>

</body>
</html>


<?php

// Database configuration (replace with your actual credentials)
$host = "localhost";
$username = "your_username";
$password = "your_password";
$database = "your_database";

// Function to handle user login
function loginUser($username, $password) {
  // Connect to the database
  $conn = new mysqli($host, $username, $password, $database);

  // Check connection
  if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
  }

  // SQL query to retrieve the user's password from the database
  $sql = "SELECT password FROM users WHERE username = '$username'";

  // Execute the query
  $result = $conn->query($sql);

  if ($result->num_rows > 0) {
    // Fetch the password from the result
    $row = $result->fetch_assoc();
    $stored_password = $row['password'];

    // Verify the password
    if (password_verify($password, $stored_password)) {
      // Password is correct, set session variables
      $_SESSION['username'] = $username;
      return true; // Login successful
    } else {
      return false; // Password incorrect
    }
  } else {
    return false; // User not found
  }

  // Close the connection
  $conn->close();
}

// Example usage (handle form submission)
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $username = $_POST["username"];
  $password = $_POST["password"];

  if (loginUser($username, $password)) {
    // Login successful - Redirect to a protected page or display a success message
    header("Location: protected_page.php"); // Example: Redirect to a protected page
    exit();
  } else {
    // Login failed - Display an error message
    echo "<p style='color: red;'>Invalid username or password.</p>";
  }
}

?>
