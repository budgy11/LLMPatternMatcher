
<!DOCTYPE html>
<html>
<head>
  <title>User Registration</title>
</head>
<body>

  <h1>User Registration</h1>

  <form method="post" action="">
    Username: <input type="text" name="username" required><br><br>
    Password: <input type="password" name="password" required><br><br>
    Email: <input type="email" name="email" required><br><br>
    <input type="submit" value="Register">
  </form>

</body>
</html>


<?php

// Database configuration (replace with your actual values)
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

  // Validate the input (VERY IMPORTANT -  don't skip this!)
  $errors = [];
  if (empty($username)) {
    $errors[] = "Username cannot be empty.";
  }
  if (empty($email)) {
    $errors[] = "Email cannot be empty.";
  }
  if (empty($password)) {
    $errors[] = "Password cannot be empty.";
  }

  // Basic email validation (improve this)
  if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $errors[] = "Invalid email format.";
  }

  // Check if there are any errors
  if (count($errors) > 0) {
    // Display errors (you should do this on a separate page for better design)
    echo "<ul>";
    foreach ($errors as $error) {
      echo "<li>" . $error . "</li>";
    }
    echo "</ul>";
    //  Don't redirect here - keep the user on the registration page
    exit; // Important to stop further processing if there are errors.
  }

  // Hash the password (VERY IMPORTANT - never store passwords in plain text)
  $hashed_password = password_hash($password, PASSWORD_DEFAULT);

  // SQL query (use prepared statements to prevent SQL injection -  SEE IMPORTANT NOTES BELOW)
  $sql = "INSERT INTO users (username, email, password) VALUES (?, ?, ?)";

  // Prepare the statement
  $stmt = $conn->prepare($sql);

  // Bind the parameters
  $stmt->bind_param("ss", $username, $hashed_password);

  // Execute the statement
  if ($stmt->execute()) {
    // Registration successful
    echo "Registration successful!  Please verify your email.";  // Or redirect here
  } else {
    // Registration failed
    echo "Registration failed.  Error: " . $stmt->error;
  }

  // Close the statement
  $stmt->close();
}

//  The following code is for the registration form itself
?>
