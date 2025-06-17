    <label for="username">Username:</label>
    <input type="text" id="username" name="username" required><br><br>

    <label for="email">Email:</label>
    <input type="email" id="email" name="email" required><br><br>

    <label for="password">Password:</label>
    <input type="password" id="password" name="password" required><br><br>

    <button type="submit">Register</button>
  </form>

</body>
</html>


<?php

// Include the database connection file (adjust the path accordingly)
require_once 'config.php'; // Assuming your database connection is in 'config.php'

/**
 * Registers a new user.
 *
 * @param string $username
 * @param string $password
 * @param string $email
 * @return bool True on success, false on failure
 */
function registerUser($username, $password, $email) {
  // Sanitize inputs (important for security)
  $username = mysqli_real_escape_string($connection, $username);
  $email = mysqli_real_escape_string($connection, $email);

  // Check if username or email is empty
  if (empty($username) || empty($email)) {
    return false;
  }

  // Check if username already exists
  $sql = "SELECT id, username, email FROM users WHERE username = '$username'";
  $result = mysqli_query($connection, $sql);
  if (mysqli_num_rows($result) > 0) {
    return false; // Username already exists
  }

  // Check if email already exists
  $sql = "SELECT id, username, email FROM users WHERE email = '$email'";
  $result = mysqli_query($connection, $sql);
  if (mysqli_num_rows($result) > 0) {
    return false; // Email already exists
  }


  // Hash the password (VERY IMPORTANT for security)
  $hashed_password = password_hash($password, PASSWORD_DEFAULT);

  // Insert the new user into the database
  $sql = "INSERT INTO users (username, password, email) VALUES ('$username', '$hashed_password', '$email')";

  if (mysqli_query($connection, $sql)) {
    return true; // Registration successful
  } else {
    // Handle database error
    error_log("Error registering user: " . mysqli_error($connection)); // Log the error
    return false;
  }
}

// Example usage (replace with your HTML form)
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $username = $_POST["username"];
  $password = $_POST["password"];
  $email = $_POST["email"];

  if (registerUser($username, $password, $email)) {
    echo "User registration successful! You have been redirected.";
    // Redirect the user to a login page or another page
    header("Location: login.php");
    exit();
  } else {
    echo "User registration failed. Please try again.";
  }
}
?>
