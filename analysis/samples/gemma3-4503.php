    <label for="username">Username:</label>
    <input type="text" id="username" name="username" required><br><br>

    <label for="email">Email:</label>
    <input type="email" id="email" name="email" required><br><br>

    <label for="password">Password:</label>
    <input type="password" id="password" name="password" required><br><br>

    <button type="submit">Register</button>
  </form>

</body>
</html>


<?php

// Database connection details (replace with your actual details)
$servername = "localhost";
$username = "your_username";
$password = "your_password";
$dbname = "your_database_name";

// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  // Get form data
  $firstname = $_POST["firstname"];
  $lastname = $_POST["lastname"];
  $email = $_POST["email"];
  $password = $_POST["password"];

  // Validate the email format
  if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $error[] = "Invalid email format.";
  }

  // Validate password (basic example - consider more robust validation)
  if (strlen($password) < 8) {
    $error[] = "Password must be at least 8 characters long.";
  }

  // Hash the password (important for security)
  $hashed_password = password_hash($password, PASSWORD_DEFAULT);

  // Check if any errors occurred
  if (empty($error)) {
    // Connect to the database
    $conn = new mysqli($servername, $username, $password, $dbname);

    // Check connection
    if ($conn->connect_error) {
      die("Connection failed: " . $conn->connect_error);
    }

    // Prepare the SQL query
    $sql = "INSERT INTO users (firstname, lastname, email, password)
            VALUES ('$firstname', '$lastname', '$email', '$hashed_password')";

    // Execute the query
    if ($conn->query($sql) === TRUE) {
      echo "New record created successfully";
    } else {
      echo "Error: " . $sql . "<br>" . $conn->error;
    }
  } else {
    // Display errors
    echo "Errors: ";
    foreach ($error as $err) {
      echo $err . "<br>";
    }
  }
}
?>
