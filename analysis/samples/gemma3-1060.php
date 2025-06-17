        Username: <input type="text" name="username" required>
        Password: <input type="password" name="password" required>
        <button type="submit">Login</button>
    </form>

    <p>Don't have an account? <a href="register.php">Register here</a></p>

</body>
</html>


<?php

// Database credentials (replace with your actual values)
$db_host = "localhost";
$db_user = "your_db_user";
$db_password = "your_db_password";
$db_name = "your_db_name";

// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  // Get username and password from the form
  $username = $_POST["username"];
  $password = $_POST["password"];

  // Validate input (VERY IMPORTANT - prevent SQL injection)
  $username = mysqli_real_escape_string($conn, $username);
  $password = mysqli_real_escape_string($conn, $password);

  // Query to authenticate the user
  $query = "SELECT id, username, password FROM users WHERE username = '$username'";
  $result = mysqli_query($conn, $query);

  if ($result) {
    // Check if the query returned any rows
    if (mysqli_num_rows($result) > 0) {
      $row = mysqli_fetch_assoc($result);

      // Verify the password
      if (password_verify($password, $row["password"])) {
        // Authentication successful!
        // Set session variables to track the user
        session_start();
        $_SESSION["user_id"] = $row["id"];
        $_SESSION["username"] = $username;
        // You might want to store other user information here too
        
        // Redirect the user to the protected area
        header("Location: protected_page.php"); // Replace with your protected page URL
        exit(); // Important: Stop further script execution
      } else {
        // Incorrect password
        echo "<p>Incorrect password.</p>";
      }
    } else {
      // User not found
      echo "<p>User not found.</p>";
    }
  } else {
    // Query error
    echo "<p>Error querying the database.</p>";
  }
}

// Start the database connection
$conn = new mysqli($db_host, $db_user, $db_password, $db_name);

// Check connection
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

?>
