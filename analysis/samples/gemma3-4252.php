        <label for="username">Username:</label>
        <input type="text" id="username" name="username" placeholder="Enter username" required>

        <br><br>

        <label for="email">Email:</label>
        <input type="email" id="email" name="email" placeholder="Enter email" required>

        <br><br>

        <label for="password">Password:</label>
        <input type="password" id="password" name="password" placeholder="Enter password" required>

        <br><br>

        <button type="submit">Register</button>
    </form>

</body>
</html>


<?php

// Database configuration (replace with your actual database details)
$db_host = "localhost";
$db_name = "your_database_name";
$db_user = "your_db_user";
$db_password = "your_db_password";

// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  // Validate the form data
  $username = trim($_POST["username"]);
  $email = trim($_POST["email"]);
  $password = trim($_POST["password"]);

  // Basic validation
  if (empty($username) || empty($email) || empty($password)) {
    $errors = "All fields are required.";
  } elseif (
    !preg_match("/^[a-zA-Z0-9._-]+$/", $username) || // Basic username validation
    !preg_match("/^[_A-Za-z0-9!#$%&'*+-/^_`{|}~-]+(\.[_A-Za-z0-9!#$%&'*+-/^_`{|}~)?]*)@([A-Za-z0-9-]+(\.[A-Za-z0-9-]+)*(\.[A-Za-z0-9-]+)*)$/", $email) ||
    $password < 6 // Password should be at least 6 characters
  ) {
    $errors = "Invalid username or email format.  Password must be at least 6 characters.";
  } else {
    // Hash the password
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Prepare the SQL query
    $sql = "INSERT INTO users (username, email, password) VALUES (?, ?, ?)";

    // Prepare the statement
    $stmt = $db->prepare($sql);
    $stmt->bind_param("sss", $username, $email, $hashed_password);

    // Execute the query
    if ($stmt->execute()) {
      $success = "Registration successful. You have been successfully registered!";
    } else {
      $errors = "Registration failed.  Please try again.";
    }

    // Close the statement
    $stmt->close();
  }
}

// Display error messages
if (isset($errors)) {
  echo "<div class='error'>" . $errors . "</div>";
}

// Display success message
if (isset($success)) {
  echo "<div class='success'>" . $success . "</div>";
}

?>
