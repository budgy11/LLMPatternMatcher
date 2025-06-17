    <label for="email">Email Address:</label>
    <input type="text" id="email" name="email" placeholder="Your Email">
    <button type="submit">Reset Password</button>
  </form>

</body>
</html>


<?php

// Database connection (Replace with your actual credentials)
$host = "localhost";
$username = "your_db_username";
$password = "your_db_password";
$database = "your_database_name";

// Function to reset password
function forgotPassword($email, $new_password) {
  // 1. Database connection
  $conn = new mysqli($host, $username, $password, $database);

  if ($conn->connect_error) {
    // Handle connection error
    die("Connection failed: " . $conn->connect_error);
  }

  // 2. Check if the email exists
  $query = "SELECT id FROM users WHERE email = ?";
  $stmt = $conn->prepare($query);
  $stmt->bind_param("s", $email); // 's' for string
  $stmt->execute();
  $result = $stmt->get_result();

  if ($result->num_rows == 0) {
    // Email not found
    $stmt->close();
    $conn->close();
    return false;
  }

  // 3. Hash the new password
  $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);

  // 4. Update the password in the database
  $query = "UPDATE users SET password = ? WHERE email = ?";
  $stmt = $conn->prepare($query);
  $stmt->bind_param("ss", $hashed_password, $email);
  $stmt->execute();

  if ($stmt->affected_rows === 0) {
    // Update failed - likely due to a problem with the email or password
    $stmt->close();
    $conn->close();
    return false;
  }
  
  $stmt->close();
  $conn->close();
  return true; // Password reset successful
}


// Example Usage (For demonstration purposes - would typically be in a form submission)
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $email = $_POST["email"];
  $new_password = $_POST["new_password"];

  if (empty($email) || empty($new_password)) {
    echo "Error: Email and new password are required.";
  } else {
    // Call the forgotPassword function
    if (forgotPassword($email, $new_password)) {
      echo "Password reset email sent. Please check your inbox.";
    } else {
      echo "Error: Password reset failed. Please try again.";
    }
  }
}

?>
