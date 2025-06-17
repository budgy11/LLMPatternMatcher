    Username: <input type="text" name="username" <?php if (isset($username)) echo 'value="' . $username . '"'; ?>><br><br>
    Password: <input type="password" name="password"><br><br>
    <input type="submit" value="Login">
  </form>

</body>
</html>


<?php

// Database connection details (replace with your actual credentials)
$host = "localhost";
$username = "your_username";
$password = "your_password";
$database = "your_database";

// Form data (from the login form)
$username = $_POST['username'];
$password = $_POST['password'];

// Check if the form was submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {

  // Connect to the database
  $conn = new mysqli($host, $username, $password, $database);

  // Check connection
  if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
  }

  // SQL query to check credentials
  $sql = "SELECT id, username, password FROM users WHERE username = '$username'";
  $result = $conn->query($sql);

  // Check if the username exists
  if ($result->num_rows == 1) {
    $row = $result->fetch_assoc();
    $hashed_password = $row['password']; // Retrieve the hashed password

    // Compare the entered password with the stored hashed password
    if (password_verify($password, $hashed_password)) {
      // Password matches! Login successful.
      // Set session variables to track the user's login status and user ID
      session_start();
      $_SESSION['user_id'] = $row['id'];
      $_SESSION['username'] = $username;

      // Redirect to a secure page or display a success message
      header("Location: secured_page.php"); // Replace with your secure page URL
      exit();
    } else {
      // Incorrect password
      echo "Incorrect password.";
    }
  } else {
    // Username does not exist
    echo "Username does not exist.";
  }

  $conn->close(); // Close the database connection

} else {
  // If the form wasn't submitted via POST, redirect to the login form
  header("Location: login_form.php");
  exit();
}
?>
