    <br><br>

    <button type="submit">Register</button>
  </form>

</body>
</html>


<?php

// Database Configuration (Replace with your actual database credentials)
$db_host = "localhost";
$db_username = "your_username";
$db_password = "your_password";
$db_name = "your_database_name";

// Check if the form has been submitted
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
  } elseif (strlen($username) < 3) {
    $errors[] = "Username must be at least 3 characters long.";
  }

  // Email validation
  if (empty($email)) {
    $errors[] = "Email cannot be empty.";
  } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $errors[] = "Invalid email address.";
  }

  // Password validation
  if (empty($password)) {
    $errors[] = "Password cannot be empty.";
  } elseif (strlen($password) < 8) {
    $errors[] = "Password must be at least 8 characters long.";
  }

  // Confirm Password validation
  if (empty($confirm_password)) {
    $errors[] = "Confirm Password cannot be empty.";
  } elseif ($password != $confirm_password) {
    $errors[] = "Passwords do not match.";
  }


  // If no errors, proceed with registration
  if (empty($errors)) {
    // Database connection
    $conn = new mysqli($db_host, $db_username, $db_password, $db_name);

    // Check connection
    if ($conn->connect_error) {
      die("Connection failed: " . $conn->connect_error);
    }

    // Hash the password (VERY IMPORTANT for security!)
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // SQL query
    $sql = "INSERT INTO users (username, email, password) VALUES ('$username', '$email', '$hashed_password')";

    // Execute the query
    if ($conn->query($sql) === TRUE) {
      echo "Registration successful!  Please check your email for verification instructions.";
      // Optionally, redirect to a success page or the login page
      // header("Location: login.php"); //  Example: Redirect to login page
    } else {
      echo "Error: " . $sql . "<br>" . $conn->error;
    }

    $conn->close();
  }
}
?>
