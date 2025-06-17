
    <br><br>

    <label for="password">Password:</label>
    <input type="password" id="password" name="password" placeholder="Enter password">

    <br><br>

    <button type="submit">Register</button>
  </form>

</body>
</html>


<?php

// Database configuration (Replace with your actual database details)
$db_host = "localhost";
$db_name = "your_database_name";
$db_user = "your_username";
$db_password = "your_password";

// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {

  // Validate input (Important for security -  expand as needed)
  $username = trim($_POST["username"]);
  $email = trim($_POST["email"]);
  $password = trim($_POST["password"]);

  // Validation example:  Basic checks - enhance this significantly!
  if (empty($username) || empty($email) || empty($password)) {
    $error = "All fields are required.";
  } elseif (strlen($username) < 3) {
    $error = "Username must be at least 3 characters long.";
  } elseif (filter_var($email, FILTER_VALIDATE_EMAIL) === false) {
    $error = "Invalid email format.";
  } elseif (strlen($password) < 8) {
    $error = "Password must be at least 8 characters long.";
  } else {
    // Hash the password - *VERY IMPORTANT* - Never store passwords in plain text!
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Prepare the SQL query
    $sql = "INSERT INTO users (username, email, password)
            VALUES ('$username', '$email', '$hashed_password')";

    // Execute the query
    if (mysqli_query($GLOBALS["conn"], $sql)) {
      $success = "Registration successful! You have been redirected.";
    } else {
      $error = "Error registering: " . mysqli_error($GLOBALS["conn"]);
    }
  }
}

// Connect to the database
$GLOBALS["conn"] = mysqli_connect($db_host, $db_user, $db_password, $db_name);

// Check the connection
if ($GLOBALS["conn"]->connect_error) {
  die("Connection failed: " . $GLOBALS["conn"]->connect_error);
}


?>
