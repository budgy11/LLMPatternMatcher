
<!DOCTYPE html>
<html>
<head>
    <title>User Login</title>
</head>
<body>

    <h1>User Login</h1>

    <form method="post" action="">
        <label for="username">Username:</label>
        <input type="text" id="username" name="username" required><br><br>

        <label for="password">Password:</label>
        <input type="password" id="password" name="password" required><br><br>

        <button type="submit">Login</button>
    </form>

    <hr>

    <p>
        <a href="logout.php" onclick="logoutFunction()">Logout</a>  <!-- Call logout.php to handle logout -->
    </p>

    <script>
        //Simulating a logout function - In a real application, logout.php would handle this
        function logoutFunction() {
            //In a real application, you would redirect to logout.php here.
            //For this example, just a placeholder
            alert("Logout initiated (placeholder)"); //Replace with actual redirect
        }
    </script>

</body>
</html>


<?php

// Database Credentials (Replace with your actual credentials!)
$db_host = "localhost";
$db_username = "your_username";
$db_password = "your_password";
$db_name = "your_database_name";

// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {

  // Get username and password from the form
  $username = $_POST["username"];
  $password = $_POST["password"];

  // Validate input (Important for security!)
  if (empty($username) || empty($password)) {
    $error_message = "Username and password are required.";
  } else {
    // Connect to the database
    $conn = new mysqli($db_host, $db_username, $db_password, $db_name);

    // Check connection
    if ($conn->connect_error) {
      die("Connection failed: " . $conn->connect_error);
    }

    // SQL query to authenticate the user
    $sql = "SELECT id, username, password FROM users WHERE username = '$username'";
    $result = $conn->query($sql);

    if ($result->num_rows == 1) {
      $row = $result->fetch_assoc();
      // Verify password (using password_hash in a real application!)
      if (password_verify($password, $row["password"])) {
        // If passwords match, set session variables
        $_SESSION["user_id"] = $row["id"];
        $_SESSION["username"] = $username;
        header("Location: welcome.php"); // Redirect to a welcome page
        exit();
      } else {
        $error_message = "Incorrect password.";
      }
    } else {
      $error_message = "Invalid username.";
    }
  }
}
?>
