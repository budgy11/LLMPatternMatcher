
<!DOCTYPE html>
<html>
<head>
  <title>User Registration</title>
</head>
<body>

  <h2>User Registration</h2>

  <form action="register.php" method="post">
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

// Database connection details (Replace with your actual credentials)
$host = "localhost";
$username = "your_username";
$password = "your_password";
$database = "your_database_name";

// Form handling - Check if the registration form was submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  // Get form data
  $firstname = $_POST["firstname"];
  $lastname = $_POST["lastname"];
  $email = $_POST["email"];
  $password = $_POST["password"];
  $confirm_password = $_POST["confirm_password"];

  // Validate the data - IMPORTANT for security!
  $errors = [];

  // Check if required fields are filled
  if (empty($firstname) || empty($lastname) || empty($email) || empty($password) || empty($confirm_password)) {
    $errors[] = "All fields are required.";
  }

  // Email validation
  if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $errors[] = "Invalid email format.";
  }

  // Password validation - Minimum length and check for strong password (optional)
  if (strlen($password) < 8) {
    $errors[] = "Password must be at least 8 characters long.";
  }
  if ($password != $confirm_password) {
    $errors[] = "Passwords do not match.";
  }

  // Check if the email already exists in the database
  $sql = "SELECT email FROM users WHERE email = '$email'";
  $result = mysqli_query($GLOBALS['conn'], $sql);

  if (mysqli_num_rows($result) > 0) {
    $errors[] = "This email address is already registered.";
  }

  // If no errors, proceed with registration
  if (empty($errors)) {
    // Hash the password - NEVER store passwords in plain text!
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // SQL query to insert the new user
    $sql = "INSERT INTO users (firstname, lastname, email, password) VALUES ('$firstname', '$lastname', '$email', '$hashed_password')";

    // Execute the query
    if (mysqli_query($GLOBALS['conn'], $sql)) {
      // Registration successful - Redirect to a welcome page or login page
      header("Location: welcome.php"); // Replace with your welcome page URL
      exit();
    } else {
      // Registration failed
      echo "Error: " . mysqli_error($GLOBALS['conn']);
    }
  }
}

//  Display the registration form
?>
