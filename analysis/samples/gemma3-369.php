    <p>You are logged in as user ID: <?php echo $_SESSION["user_id"]; ?></p>
    <a href="logout.php">Logout</a> <!--  Create a logout.php file for logout functionality -->
</body>
</html>


<?php

// Database connection details (replace with your actual values)
$servername = "localhost";
$username = "your_username";
$password = "your_password";
$dbname = "your_database_name";

// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  // Get the username and password from the form
  $username = $_POST["username"];
  $password = $_POST["password"];

  // Validate the input (IMPORTANT - this is basic, improve for production)
  if (empty($username) || empty($password)) {
    $error = "Username and password must be filled.";
  } elseif (!filter_var($username, FILTER_VALIDATE_EMAIL)) {
    $error = "Invalid username (Please enter a valid email address).";
  } else {
    // Connect to the database
    $conn = new mysqli($servername, $username, $password, $dbname);

    // Check connection
    if ($conn->connect_error) {
      die("Connection failed: " . $conn->connect_error);
    }

    // Prepare and execute the query
    $sql = "SELECT id, username, password FROM users WHERE username = ? AND password = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $username, $password); // "ss" indicates two string parameters
    $stmt->execute();

    $result = $stmt->get_result();

    if ($result->num_rows == 1) {
      // User found, set session variables
      session_start();
      $user_row = $result->fetch_assoc();
      $_SESSION["user_id"] = $user_row["id"];
      $_SESSION["username"] = $user_row["username"];
      echo "Login successful!  Redirecting...";

      // Redirect to a protected page or your application's homepage
      header("Location: protected_page.php"); // Replace with your desired URL
      exit();
    } else {
      $error = "Invalid username or password.";
    }
  }
}
?>
