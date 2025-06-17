        <label for="firstname">First Name:</label>
        <input type="text" id="firstname" name="firstname" required><br><br>

        <label for="lastname">Last Name:</label>
        <input type="text" id="lastname" name="lastname" required><br><br>

        <label for="email">Email:</label>
        <input type="email" id="email" name="email" required><br><br>

        <label for="password">Password:</label>
        <input type="password" id="password" name="password" required><br><br>

        <input type="submit" value="Register">
    </form>

</body>
</html>


<?php

// Include the database connection file (assuming you have one)
require_once 'db_connect.php'; // Replace 'db_connect.php' with the actual file name

/**
 * Registers a new user.
 *
 * @param string $username  The desired username.
 * @param string $password The desired password.
 * @param string $email The user's email address.
 * @return bool True on successful registration, false otherwise.
 */
function registerUser($username, $password, $email) {
  // Sanitize inputs to prevent vulnerabilities (VERY IMPORTANT!)
  $username = filter_var($username, FILTER_SANITIZE_STRING);
  $email = filter_var($email, FILTER_SANITIZE_EMAIL);

  // Validate inputs
  if (empty($username) || empty($password) || empty($email)) {
    return false;
  }

  // Hash the password for security
  $hashed_password = password_hash($password, PASSWORD_DEFAULT);

  // Check if the username already exists
  $check_query = "SELECT * FROM users WHERE username = '$username'";
  $result = mysqli_query($connection, $check_query);
  $user = mysqli_fetch_assoc($result);

  if ($user) {
    return false; // Username already exists
  }

  // Insert the user into the database
  $insert_query = "INSERT INTO users (username, password, email) VALUES ('$username', '$hashed_password', '$email')";
  $result = mysqli_query($connection, $insert_query);

  if (!$result) {
    // Handle database errors
    error_log("Database error: " . mysqli_error($connection)); // Log the error
    return false;
  }

  return true; // Registration successful
}

// --- Example Usage (For testing - remove or adjust for your application) ---
// This part demonstrates how to use the registerUser function.
// It's good for testing, but you'll likely integrate it into your registration form.

// **IMPORTANT:**  DO NOT directly use these values in a production environment.
// This is just for demonstrating how the function is called.  Use form input instead.

// if (isset($_POST['register'])) {
//   $username = $_POST['username'];
//   $password = $_POST['password'];
//   $email = $_POST['email'];

//   if (registerUser($username, $password, $email)) {
//     echo "<p>User registered successfully!</p>";
//     // Redirect to a success page or login form
//   } else {
//     echo "<p>Registration failed. Please try again.</p>";
//   }
// }
?>
