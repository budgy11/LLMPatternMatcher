

<?php

// Database credentials (Replace with your actual database details)
$db_host = "localhost";
$db_name = "your_database_name";
$db_user = "your_username";
$db_password = "your_password";

// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  // Collect form data
  $username = $_POST["username"];
  $email = $_POST["email"];
  $password = $_POST["password"];

  // Validate the input (Important for security!)
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
  // Simple email validation (can be improved with regex)
  if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $errors[] = "Invalid email address.";
  }

  // Hash the password (Crucial for security - do NOT store passwords in plain text)
  $hashed_password = password_hash($password, PASSWORD_DEFAULT);

  // Check if there are any errors
  if (empty($errors)) {
    // Insert the user into the database
    $conn = new mysqli($db_host, $db_user, $db_password, $db_name);

    if ($conn->connect_error) {
      die("Connection failed: " . $conn->connect_error);
    }

    // Prepare the SQL query
    $sql = "INSERT INTO users (username, email, password) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($sql);

    if ($stmt) {
      $stmt->bind_param("ss", $username, $hashed_password);
      $stmt->execute();
      $stmt->close();
    } else {
      die("Query preparation failed");
    }


    // Display a success message
    echo "<p>Registration successful! Please log in.</p>";
  } else {
    // Display the registration form with error messages
    echo "<h1>Registration</h1>";
    echo "<form method='post'>";
    echo "Username: <input type='text' name='username' value='" . htmlspecialchars($username) . "'><br>";
    echo "Email: <input type='email' name='email' value='" . htmlspecialchars($email) . "'><br>";
    echo "Password: <input type='password' name='password'><br>";
    echo "<br>";
    echo "<br>";
    echo "<p>". implode(". ", $errors) . "</p>";
    echo "<br>";
    echo "<br>";
    echo "<input type='submit' value='Register'>";
    echo "</form>";
  }
} else {
  // If the form hasn't been submitted, display the registration form
  echo "<h1>Registration</h1>";
  echo "<form method='post'>";
  echo "Username: <input type='text' name='username' value=''><br>";
  echo "Email: <input type='email' name='email' value=''><br>";
  echo "Password: <input type='password' name='password' value=''><br>";
  echo "<br>";
  echo "<br>";
  echo "<input type='submit' value='Register'>";
  echo "</form>";
}

$conn->close();

?>
