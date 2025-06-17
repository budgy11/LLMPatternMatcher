    <label for="username">Username:</label>
    <input type="text" id="username" name="username" placeholder="Enter username">
    <br><br>

    <label for="email">Email:</label>
    <input type="email" id="email" name="email" placeholder="Enter email">
    <br><br>

    <label for="password">Password:</label>
    <input type="password" id="password" name="password" placeholder="Enter password">
    <br><br>

    <button type="submit">Register</button>
</form>

</body>
</html>


<?php

// Include the database connection file (assuming it's named 'db_connect.php')
require_once 'db_connect.php'; // Adjust the path if needed

/**
 * Registers a new user to the database.
 *
 * @param string $username The desired username.
 * @param string $password The password for the new user.
 * @param string $email The user's email address.
 * @return bool True if registration was successful, false otherwise.
 */
function registerUser($username, $password, $email) {
  // Sanitize input to prevent SQL injection
  $username = mysqli_real_escape_string($conn, $username);
  $password = mysqli_real_escape_string($conn, $password);
  $email = mysqli_real_escape_string($conn, $email);

  // Hash the password (IMPORTANT for security!)
  $hashed_password = password_hash($password, PASSWORD_DEFAULT);


  // Check if the username already exists
  $sql = "SELECT id, username, email FROM users WHERE username = '$username' OR email = '$email'";
  $result = mysqli_query($conn, $sql);

  if ($result) {
    $row = mysqli_fetch_assoc($result);
    if ($row) {
      // Username or email already exists
      return false;
    }
  }


  // Insert the new user into the database
  $sql = "INSERT INTO users (username, password, email) VALUES ('$username', '$hashed_password', '$email')";

  if (mysqli_query($conn, $sql)) {
    return true;
  } else {
    // Error occurred during insertion
    error_log("Error registering user: " . mysqli_error($conn)); // Log the error for debugging
    return false;
  }
}


//  Example Usage (This would be part of a form submission handler)
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $username = $_POST["username"];
  $password = $_POST["password"];
  $email = $_POST["email"];

  if (registerUser($username, $password, $email)) {
    echo "User registration successful!  You have been redirected.";
    // Redirect to a success page or login form
  } else {
    echo "Error: User registration failed. Please try again.";
    // Display error messages (e.g., username already exists)
  }
}
?>
