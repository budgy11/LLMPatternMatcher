

<?php

// Database connection details (replace with your actual credentials)
$servername = "localhost";
$username = "your_username";
$password = "your_password";
$dbname = "your_database_name";

// Check if the registration form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  // Get form data
  $firstname = $_POST["firstname"];
  $lastname = $_POST["lastname"];
  $email = $_POST["email"];
  $password = $_POST["password"];

  // Validate the data (IMPORTANT - Add more robust validation here!)
  $errors = [];

  if (empty($firstname)) {
    $errors[] = "First name cannot be empty.";
  }
  if (empty($lastname)) {
    $errors[] = "Last name cannot be empty.";
  }
  if (empty($email)) {
    $errors[] = "Email cannot be empty.";
  }
  if (filter_var($email, FILTER_VALIDATE_EMAIL) === false) {
    $errors[] = "Invalid email format.";
  }
  if (empty($password)) {
    $errors[] = "Password cannot be empty.";
  }
  // Add password complexity requirements here (e.g., minimum length, special characters)
  if (strlen($password) < 8) {
    $errors[] = "Password must be at least 8 characters long.";
  }

  // Check if there are any errors
  if (count($errors) > 0) {
    // Display the errors
    echo "<h2>Error:</h2>";
    echo "<ol>";
    foreach ($errors as $error) {
      echo "<li>" . $error . "</li>";
    }
    echo "</ol>";
  } else {
    // Hash the password (VERY IMPORTANT for security!)
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Prepare the SQL query
    $sql = "INSERT INTO users (firstname, lastname, email, password) VALUES (?, ?, ?, ?)";

    // Prepare the statement
    $stmt = $conn->prepare($sql);

    // Bind the parameters
    $stmt->bind_param("sss", $firstname, $lastname, $hashed_password);

    // Execute the query
    if ($stmt->execute()) {
      // Registration successful
      echo "<h2>Registration Successful!</h2>";
      echo "<p>You have successfully registered.  Please check your email to activate your account (if applicable).</p>";
      // Redirect the user to a login page or homepage
      header("Location: login.php"); // Replace with your login page
      exit();
    } else {
      // Registration failed
      echo "<h2>Registration Failed!</h2>";
      echo "<p>An error occurred while registering.  " . $conn->error . "</p>";
    }

    // Close the statement
    $stmt->close();
  }
}
?>
