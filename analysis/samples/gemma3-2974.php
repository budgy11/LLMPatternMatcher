
    <br><br>

    <label for="password">Password:</label>
    <input type="password" id="password" name="password" placeholder="Enter password">

    <br><br>

    <input type="submit" value="Register">
  </form>

</body>
</html>


<?php

// Database connection details (replace with your actual values)
$host = "localhost";
$username = "your_username";
$password = "your_password";
$database = "your_database_name";

// Form handling - Check for POST request
if ($_SERVER["REQUEST_METHOD"] == "POST") {

  // Get form data
  $firstname = $_POST["firstname"];
  $lastname = $_POST["lastname"];
  $email = $_POST["email"];
  $password = $_POST["password"];
  $confirm_password = $_POST["confirm_password"];

  // Validate form data
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
  if (empty($confirm_password)) {
    $errors[] = "Confirm password cannot be empty.";
  }
  if ($password != $confirm_password) {
    $errors[] = "Passwords do not match.";
  }

  // If there are any errors, display them
  if (!empty($errors)) {
    echo "<h2>Error:</h2>";
    echo "<ul>";
    foreach ($errors as $error) {
      echo "<li>" . $error . "</li>";
    }
    echo "</ul>";
  } else {
    // If no errors, proceed with registration

    // Sanitize input (IMPORTANT: Use prepared statements in a real application!)
    $firstname = $mysqli->real_escape_string($firstname);
    $lastname = $mysqli->real_escape_string($lastname);
    $email = $mysqli->real_escape_string($email);
    $password = $mysqli->real_escape_string($password);

    // Hash the password (NEVER store passwords in plain text!)
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // SQL query to insert the new user into the database
    $sql = "INSERT INTO users (firstname, lastname, email, password) VALUES ('$firstname', '$lastname', '$email', '$hashed_password')";

    if ($mysqli->query($sql)) {
      // Registration successful
      echo "<h2>Registration Successful!</h2>";
      echo "<p>You have been successfully registered. Please check your email to verify your account.</p>";
      // Redirect to login page or home page
      header("Location: login.php"); // Assuming you have a login.php page
      exit();
    } else {
      // Something went wrong during the query
      echo "<h2>Error:</h2>";
      echo "<p>" . $mysqli->error . "</p>";
    }
  }
}
?>
