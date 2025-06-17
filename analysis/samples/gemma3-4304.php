    <br><br>

    <input type="submit" value="Register">
  </form>

</body>
</html>


<?php

// Database connection details (Replace with your actual credentials)
$servername = "localhost";
$username = "your_username";
$password = "your_password";
$dbname = "your_database_name";

// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {

  // Validate the form data (Important for security!)
  $username = trim($_POST["username"]);
  $email = trim($_POST["email"]);
  $password = trim($_POST["password"]);

  // Basic validation (Improve this for more robust validation)
  if (empty($username) || empty($email) || empty($password)) {
    $errors = "All fields are required.";
  } elseif (strlen($username) < 3) {
    $errors = "Username must be at least 3 characters long.";
  } elseif (filter_var($email, FILTER_VALIDATE_EMAIL) === false) {
    $errors = "Invalid email format.";
  } elseif (strlen($password) < 8) {
    $errors = "Password must be at least 8 characters long.";
  }

  // If no errors, proceed with registration
  if (empty($errors)) {
    try {
      // Create database connection
      $conn = new mysqli($servername, $username, $password, $dbname);

      // Check connection
      if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
      }

      // Hash the password (VERY IMPORTANT for security!)
      $hashed_password = password_hash($password, PASSWORD_DEFAULT);

      // Prepare the SQL query
      $sql = "INSERT INTO users (username, email, password) VALUES (?, ?, ?)";

      // Prepare the statement
      $stmt = $conn->prepare($sql);

      // Bind the parameters
      $stmt->bind_param("ss", $username, $hashed_password);

      // Execute the query
      if ($stmt->execute()) {
        echo "Registration successful! You have been redirected.";
        // Redirect to a success page or login page
        header("Location: login.php"); // Replace login.php with your login page
        exit();
      } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
      }
    } catch (Exception $e) {
      echo "An error occurred: " . $e->getMessage();
    }
  }
}
?>
