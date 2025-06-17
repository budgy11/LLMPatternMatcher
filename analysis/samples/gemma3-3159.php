    <br><br>

    <button type="submit">Register</button>
  </form>

</body>
</html>


<?php

// Database connection details (Replace with your actual credentials)
$db_host = "localhost";
$db_name = "your_database_name";
$db_user = "your_username";
$db_password = "your_password";

// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {

  // Get form data
  $username = $_POST["username"];
  $email = $_POST["email"];
  $password = $_POST["password"];
  $confirm_password = $_POST["confirm_password"];

  // Validation - Important to prevent security vulnerabilities
  if (empty($username) || empty($email) || empty($password) || empty($confirm_password)) {
    $errors = "All fields are required.";
  } elseif (strlen($username) < 3) {
    $errors = "Username must be at least 3 characters long.";
  } elseif (filter_var($email, FILTER_VALIDATE_EMAIL) === false) {
    $errors = "Invalid email format.";
  } elseif ($password != $confirm_password) {
    $errors = "Passwords must match.";
  }

  // If no errors, proceed with registration
  if (empty($errors)) {
    // Hash the password - VERY IMPORTANT for security!
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Prepare the SQL query
    $sql = "INSERT INTO users (username, email, password) VALUES (?, ?, ?)";

    // Prepare the statement
    $stmt = $conn->prepare($sql);

    // Bind the parameters
    $stmt->bind_param("sss", $username, $email, $hashed_password);

    // Execute the query
    if ($stmt->execute()) {
      // Registration successful
      echo "Registration successful! You have been redirected.";
      // You might want to redirect the user to a login page or other welcome page
      header("Location: login.php");
      exit;
    } else {
      // Registration failed
      echo "Registration failed! " . $stmt->error;
    }
  }
}

?>
