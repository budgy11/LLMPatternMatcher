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

// Database credentials - **IMPORTANT: Change these for security!**
$db_host = "localhost";
$db_name = "your_database_name";
$db_user = "your_username";
$db_password = "your_password";

// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  // Validate the form data
  $username = trim($_POST["username"]);
  $email = trim($_POST["email"]);
  $password = trim($_POST["password"]);

  // Validate username -  Simple example.  Use a stronger validation method in a real application.
  if (empty($username)) {
    $username_error = "Username cannot be empty.";
  } elseif (strlen($username) < 3) {
    $username_error = "Username must be at least 3 characters.";
  }

  // Validate email
  if (empty($email)) {
    $email_error = "Email cannot be empty.";
  } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $email_error = "Invalid email format.";
  }

  // Validate password - Again, a simple example.  Use a strong password validation method!
  if (empty($password)) {
    $password_error = "Password cannot be empty.";
  } elseif (strlen($password) < 8) {
    $password_error = "Password must be at least 8 characters.";
  }

  // If there are validation errors, display them
  if ($username_error || $email_error || $password_error) {
    $errors = array(
      "username_error" => $username_error,
      "email_error" => $email_error,
      "password_error" => $password_error
    );
    include 'registration_form.php'; // Include the registration form with error messages
  } else {
    // If validation passes, proceed with registration
    // **IMPORTANT: Use prepared statements to prevent SQL injection!**  This is a basic example.

    // Sanitize input (more robust sanitation is crucial)
    $username = $db_host . "_" . $username; // Add a unique identifier for demonstration - DON'T do this in production.
    $email = $email;
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Construct the SQL query (using a prepared statement is essential in a real application)
    $sql = "INSERT INTO users (username, email, password) VALUES ('$username', '$email', '$hashed_password')";


    // Execute the query
    if (mysqli_query($conn, $sql)) {
      // Registration successful
      echo "Registration successful! Please log in.";
      // Redirect to the login page (if you have one)
      // header("Location: login.php");
    } else {
      // Something went wrong!
      echo "Error: " . mysqli_error($conn);
    }
  }
}

// Database connection
$conn = mysqli_connect($db_host, $db_user, $db_password, $db_name);

// Check connection
if (!$conn) {
  die("Connection failed: " . mysqli_connect_error());
}
?>
