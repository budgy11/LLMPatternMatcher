        <label for="username">Username:</label>
        <input type="text" id="username" name="username" required><br><br>

        <label for="email">Email:</label>
        <input type="email" id="email" name="email" required><br><br>

        <label for="password">Password:</label>
        <input type="password" id="password" name="password" required><br><br>

        <input type="submit" value="Register">
    </form>

</body>
</html>


<?php

// Database credentials (replace with your actual values)
$db_host = "localhost";
$db_name = "your_database_name";
$db_user = "your_database_user";
$db_pass = "your_database_password";

// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  // Get form data
  $username = $_POST["username"];
  $email = $_POST["email"];
  $password = $_POST["password"];

  // Validate the data (VERY IMPORTANT - never trust user input!)
  if (empty($username) || empty($email) || empty($password)) {
    $errors = "All fields are required.";
  } elseif (
    !preg_match("/^[a-zA-Z0-9_]+$/", $username) // Username validation
  ) {
    $errors = "Username can only contain letters, numbers, and underscores.";
  } elseif (
    !filter_var($email, FILTER_VALIDATE_EMAIL)
  ) {
    $errors = "Invalid email format.";
  } elseif (
    strlen($password) < 8
  ) {
    $errors = "Password must be at least 8 characters long.";
  }
  // Hash the password (VERY IMPORTANT for security)
  $hashed_password = password_hash($password, PASSWORD_DEFAULT);

  // Check if there are any errors
  if ($errors != "") {
    echo "<p style='color:red;'>$errors</p>";
  } else {
    // SQL query to insert the user data into the database
    $sql = "INSERT INTO users (username, email, password) VALUES ('$username', '$email', '$hashed_password')";

    // Execute the query
    if (mysqli_query($GLOBALS["conn"], $sql)) {
      echo "<p style='color:green;'>User registered successfully!</p>";
    } else {
      echo "<p style='color:red;'>Error registering user: " . mysqli_error($GLOBALS["conn"]) . "</p>";
    }
  }
}
?>
