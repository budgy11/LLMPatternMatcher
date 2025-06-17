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

// Database connection details - Replace with your actual values
$db_host = "localhost";
$db_name = "your_database_name";
$db_user = "your_database_user";
$db_password = "your_database_password";

// Form handling
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  // Get form data
  $username = $_POST["username"];
  $password = $_POST["password"];
  $email = $_POST["email"];

  // Validate input (Important for security!)
  if (empty($username) || empty($password) || empty($email)) {
    $errors = array("username" => "Username cannot be empty.",
                    "password" => "Password cannot be empty.",
                    "email" => "Email cannot be empty.");
  } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $errors = array("email" => "Invalid email format.");
  } elseif (strlen($username) < 3) {
    $errors = array("username" => "Username must be at least 3 characters long.");
  }
  // Password strength check (Basic example - consider stronger hashing)
  if (strlen($password) < 8) {
    $errors = array("password" => "Password must be at least 8 characters long.");
  }


  // If no errors, proceed with registration
  if (empty($errors)) {
    // Hash the password (Crucial for security!)
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // SQL query to insert into the database
    $sql = "INSERT INTO users (username, password, email) VALUES ('$username', '$hashed_password', '$email')";

    // Execute the query
    if (mysqli_query($GLOBALS["conn"], $sql)) {
      // Registration successful
      echo "<p>Registration successful! Please <a href='login.php'>login</a>.</p>";
    } else {
      // Registration failed
      echo "<p>Registration failed. Error: " . mysqli_error($GLOBALS["conn"]) . "</p>";
    }
  }
}

// Database connection
$GLOBALS["conn"] = mysqli_connect($db_host, $db_user, $db_password, $db_name);

// Check connection
if ($GLOBALS["conn"]->connect_error) {
  die("Connection failed: " . $GLOBALS["conn"]->connect_error);
}


?>
