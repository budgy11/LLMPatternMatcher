        <label for="name">Name:</label>
        <input type="text" id="name" name="name" required>

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

// Database connection details (replace with your actual values)
$servername = "localhost";
$username = "your_username";
$password = "your_password";
$dbname = "your_database_name";

// Check if the registration form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  // Collect form data
  $firstname = $_POST["firstname"];
  $lastname = $_POST["lastname"];
  $email = $_POST["email"];
  $password = $_POST["password"];

  // Validate the input data (important for security!)
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
  // You should add password complexity requirements here (e.g., minimum length,
  // special characters, etc.)
  // Example:
  // if (strlen($password) < 8) {
  //   $errors[] = "Password must be at least 8 characters long.";
  // }

  // Check if there are any errors
  if (count($errors) > 0) {
    // Display the errors to the user
    echo "<h2>Error:</h2>";
    foreach ($errors as $error) {
      echo "<p>" . $error . "</p>";
    }
  } else {
    // Hash the password before storing it in the database
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

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
      echo "<h2>Registration successful!</h2>";
      echo "<p>You have successfully registered.  Please check your email to activate your account.</p>";
      // Optionally redirect the user to a success page.
    } else {
      echo "<h2>Error:</h2>" . "<p>" . $conn->error . "</p>";
    }

    // Close the connection
    $conn->close();
  }
}
?>
