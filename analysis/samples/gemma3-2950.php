    <br><br>

    <label for="password">Password:</label>
    <input type="password" id="password" name="password" value="">
    <br><br>

    <input type="submit" value="Register">
  </form>

</body>
</html>


<?php

// Database configuration (replace with your actual values)
$db_host = "localhost";
$db_name = "your_database_name";
$db_user = "your_username";
$db_password = "your_password";


// Check if the registration form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  // Get form data
  $username = $_POST["username"];
  $email = $_POST["email"];
  $password = $_POST["password"];
  $confirm_password = $_POST["confirm_password"];

  // Validate the form data
  $errors = [];

  // Username validation
  if (empty($username)) {
    $errors[] = "Username cannot be empty.";
  }

  // Email validation (basic)
  if (empty($email)) {
    $errors[] = "Email cannot be empty.";
  }
  // More robust email validation can be added here (e.g., checking format)
  if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $errors[] = "Invalid email format.";
  }


  // Password validation
  if (empty($password)) {
    $errors[] = "Password cannot be empty.";
  }
  if (empty($confirm_password)) {
    $errors[] = "Confirm password cannot be empty.";
  }

  if ($password != $confirm_password) {
    $errors[] = "Passwords do not match.";
  }
  if (strlen($password) < 8) {
    $errors[] = "Password must be at least 8 characters long.";
  }


  // Check if there are any errors
  if (count($errors)) {
    // Display the errors (you can use a more user-friendly error display)
    echo "<h2>Errors:</h2>";
    foreach ($errors as $error) {
      echo "<p>" . $error . "</p>";
    }
  } else {
    // Prepare the data for insertion into the database
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Connect to the database
    $conn = new mysqli($db_host, $db_user, $db_password, $db_name);

    // Check the connection
    if ($conn->connect_error) {
      die("Connection failed: " . $conn->connect_error);
    }

    // SQL query to insert the user data
    $sql = "INSERT INTO users (username, email, password) VALUES (?, ?, ?)";

    // Prepare the statement
    $stmt = $conn->prepare($sql);

    // Bind the parameters
    $stmt->bind_param("ss", $username, $hashed_password);

    // Execute the statement
    if ($stmt->execute()) {
      // User registered successfully
      echo "<h2>Registration successful!</h2>";
      echo "<p>You have successfully registered.  Please <a href=\"login.php\">log in</a>.</p>";
      // Redirect to the login page
      // header("Location: login.php");  // Or another appropriate redirect
      exit(); // Important:  Stop further execution after a successful redirect
    } else {
      // Error executing the query
      echo "<h2>Error registering user.</h2>";
      echo "<p>" . $conn->error . "</p>";
    }

    // Close the statement and connection
    $stmt->close();
    $conn->close();
  }
}
?>
