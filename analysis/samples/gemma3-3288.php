    <label for="username">Username:</label>
    <input type="text" id="username" name="username" required><br><br>

    <label for="password">Password:</label>
    <input type="password" id="password" name="password" required><br><br>

    <label for="email">Email:</label>
    <input type="email" id="email" name="email" required><br><br>

    <button type="submit">Register</button>
  </form>

</body>
</html>


<?php

// Database configuration (replace with your actual credentials)
$db_host = "localhost";
$db_user = "your_db_user";
$db_password = "your_db_password";
$db_name = "your_db_name";

// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {

  // Get form data
  $username = $_POST["username"];
  $password = $_POST["password"];
  $email = $_POST["email"];

  // Validate the data (IMPORTANT - add more robust validation here)
  if (empty($username) || empty($password) || empty($email)) {
    $error = "All fields are required.";
  } elseif (strlen($username) < 3) {
    $error = "Username must be at least 3 characters.";
  } elseif (preg_match("/@/", $email)) {
    $error = "Invalid email format.";
  }  else {
    // Hash the password (VERY IMPORTANT for security!)
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // SQL query to insert the new user
    $sql = "INSERT INTO users (username, password, email) VALUES ('$username', '$hashed_password', '$email')";

    // Execute the query
    if (mysqli_query($GLOBALS["conn"], $sql)) {
      $success = "Registration successful.  You have been logged in.";
    } else {
      $error = "Error: " . mysqli_error($GLOBALS["conn"]);
    }
  }
}

// Connect to the database
$conn = mysqli_connect($db_host, $db_user, $db_password, $db_name);

// Check connection
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

?>
