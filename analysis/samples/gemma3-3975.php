    <label for="username">Username:</label>
    <input type="text" id="username" name="username" required><br><br>

    <label for="email">Email:</label>
    <input type="email" id="email" name="email" required><br><br>

    <label for="password">Password:</label>
    <input type="password" id="password" name="password" required><br><br>

    <label for="confirm_password">Confirm Password:</label>
    <input type="password" id="confirm_password" name="confirm_password" required><br><br>

    <button type="submit">Register</button>
  </form>

</body>
</html>


<?php

// Database configuration (Replace with your actual credentials)
$db_host = "localhost";
$db_user = "your_db_user";
$db_pass = "your_db_password";
$db_name = "your_db_name";

// Function to register a new user
function registerUser($username, $password, $email) {
  // 1. Database Connection
  $conn = new mysqli($db_host, $db_user, $db_pass, $db_name);

  // Check connection
  if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
  }

  // 2.  Prepare and Execute SQL Query
  // Use prepared statements to prevent SQL injection
  $hashed_password = password_hash($password, PASSWORD_DEFAULT);  // Securely hash the password

  $sql = "INSERT INTO users (username, password, email) VALUES (?, ?, ?)";
  $stmt = $conn->prepare($sql);
  $stmt->bind_param("sss", $username, $hashed_password, $email);  // "s" for string
  $stmt->execute();

  // 3.  Close Connection
  $stmt->close();
  $conn->close();

  // 4.  Success Message
  return true; // Indicate success
}

// --- Example Usage (Simulated form submission) ---
//  In a real application, this would come from an HTML form.
//  This is just for demonstration.

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $username = $_POST["username"];
  $password = $_POST["password"];
  $email = $_POST["email"];

  // Validate input (Important for security and data integrity)
  if (empty($username) || empty($password) || empty($email)) {
    echo "<p style='color:red;'>All fields are required.</p>";
  } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    echo "<p style='color:red;'>Invalid email address.</p>";
  } else {
    // Register the user
    if (registerUser($username, $password, $email)) {
      echo "<p style='color:green;'>Registration successful!  You have been logged in.</p>";
      // Redirect to a success page or login page
    } else {
      echo "<p style='color:red;'>Registration failed. Please try again.</p>";
    }
  }
}
?>
