    <label for="username">Username:</label>
    <input type="text" id="username" name="username" placeholder="Enter username">
    <br><br>

    <label for="email">Email:</label>
    <input type="email" id="email" name="email" placeholder="Enter email">
    <br><br>

    <label for="password">Password:</label>
    <input type="password" id="password" name="password" placeholder="Enter password">
    <br><br>

    <button type="submit">Register</button>
</form>

</body>
</html>


// ... (database connection)

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = htmlspecialchars($_POST["username"]);
    $email = htmlspecialchars($_POST["email"]);
    $password = htmlspecialchars($_POST["password"]);

    // Prepared statement
    $sql = "INSERT INTO users (username, email, password) VALUES (?, ?, ?)";
    $stmt = mysqli_stmt_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "sss", $username, $email, $hashed_password); // "sss" specifies 3 string parameters
    mysqli_stmt_execute($stmt);
    // ... (rest of the code)


<?php

// Database connection details (Replace with your actual credentials)
$host = "localhost";
$username = "your_username";
$password = "your_password";
$database = "your_database_name";

// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {

  // Get form data
  $username = $_POST["username"];
  $email = $_POST["email"];
  $password = $_POST["password"];

  // Validate the data (Important security step!)
  if (empty($username) || empty($email) || empty($password)) {
    $error = "All fields are required.";
  } elseif (filter_var($email, FILTER_VALIDATE_EMAIL) === false) {
    $error = "Invalid email format.";
  } else {
    // Hash the password using password_hash
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // SQL query to insert the new user into the database
    $sql = "INSERT INTO users (username, email, password) VALUES ('$username', '$email', '$hashed_password')";

    // Execute the query
    $conn = new mysqli($host, $username, $password, $database);

    if ($conn->connect_error) {
      $error = "Connection failed: " . $conn->connect_error;
    } else {
      $conn->query($sql);
      $success = "Registration successful! Please check your email for a verification link.";
    }
  }
}
?>
