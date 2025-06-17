    <label for="username">Username:</label>
    <input type="text" id="username" name="username" required>

    <br><br>

    <label for="email">Email:</label>
    <input type="email" id="email" name="email" required>

    <br><br>

    <label for="password">Password:</label>
    <input type="password" id="password" name="password" required>

    <br><br>

    <input type="submit" value="Register">
  </form>

</body>
</html>


<?php

// Database connection details (replace with your actual credentials)
$host = "localhost";
$username = "your_username";
$password = "your_password";
$database = "your_database";

// Check if the registration form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  // Get form data
  $firstname = $_POST["firstname"];
  $lastname = $_POST["lastname"];
  $email = $_POST["email"];
  $password = $_POST["password"];

  // Validate the data
  $errors = [];

  // Check if required fields are empty
  if (empty($firstname) || empty($lastname) || empty($email) || empty($password)) {
    $errors[] = "All fields are required.";
  }

  // Email validation (basic check - improve as needed)
  if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $errors[] = "Invalid email format.";
  }

  // Password strength (basic - improve as needed)
  if (strlen($password) < 8) {
    $errors[] = "Password must be at least 8 characters long.";
  }


  // Check if the email already exists in the database
  $sql = "SELECT * FROM users WHERE email = '$email'";
  $result = mysqli_query($GLOBALS['host'], $sql); // Use mysqli_query or PDO

  if (mysqli_num_rows($result) > 0) {
    $errors[] = "Email address is already registered.";
  }

  // If no errors, insert the user into the database
  if (empty($errors)) {
    // Hash the password (VERY IMPORTANT for security!)
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // SQL query to insert the user
    $sql = "INSERT INTO users (firstname, lastname, email, password) VALUES ('$firstname', '$lastname', '$email', '$hashed_password')";

    // Execute the query
    if (mysqli_query($GLOBALS['host'], $sql)) {
      // Successful registration
      echo "Registration successful! Please check your email to activate your account.";
      // Redirect to a welcome page or login page
      header("Location: welcome.php"); // Or your preferred redirection
      exit;
    } else {
      // Error during registration
      echo "Error: " . mysqli_error($GLOBALS['host']);
    }
  }
}
?>
