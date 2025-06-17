    <br><br>

    <label for="password">Password:</label>
    <input type="password" id="password" name="password">
    <br><br>

    <button type="submit">Register</button>
  </form>

</body>
</html>


<?php

// Include the database connection file (assuming you have one)
require_once 'db_connection.php'; // Replace with your actual file name

// Check if the registration form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {

  // Collect form data
  $username = $_POST["username"];
  $password = $_POST["password"];
  $email = $_POST["email"];

  // Validate the data (IMPORTANT - Add more robust validation here!)
  $errors = [];

  if (empty($username)) {
    $errors[] = "Username cannot be empty.";
  }
  if (empty($password)) {
    $errors[] = "Password cannot be empty.";
  }
  if (empty($email)) {
    $errors[] = "Email cannot be empty.";
  }

  // Basic email validation (improve this!)
  if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $errors[] = "Invalid email format.";
  }


  // Hash the password (VERY IMPORTANT for security)
  $hashed_password = password_hash($password, PASSWORD_DEFAULT);

  // Check if there are any errors
  if (empty($errors)) {
    // Prepare the SQL query
    $sql = "INSERT INTO users (username, password, email) VALUES (?, ?, ?)";

    // Prepare the statement
    $stmt = $conn->prepare($sql);

    // Bind the parameters
    $stmt->bind_param("sss", $username, $hashed_password, $email);

    // Execute the query
    if ($stmt->execute()) {
      // Registration successful
      echo "Registration successful! You have been redirected.";
      // Optionally, redirect to a success page or login page
      header("Location: login.php"); // Redirect to login page
      exit; // Stop further execution
    } else {
      // Registration failed
      echo "Registration failed. Error: " . $stmt->error;
    }
  } else {
    // Display errors to the user
    echo "Errors:<ul>";
    foreach ($errors as $error) {
      echo "<li>" . $error . "</li>";
    }
    echo "</ul>";
  }
}
?>
