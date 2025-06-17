        Username: <input type="text" name="username" placeholder="Enter your username"><br><br>
        Password: <input type="password" name="password" placeholder="Enter your password"><br><br>
        <input type="submit" value="Login">
    </form>

</body>
</html>


<?php

// Database credentials (replace with your actual values)
$host = "localhost";
$username = "your_db_username";
$password = "your_db_password";
$database = "your_db_name";

// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  // Get username and password from the form
  $username = $_POST["username"];
  $password = $_POST["password"];

  // Validate the input (very important!)
  if (empty($username) || empty($password)) {
    $error_message = "Username and password must be filled.";
  } elseif (!filter_var($username, FILTER_VALIDATE_EMAIL)) {
    $error_message = "Invalid email format.";
  } else {
    // Database connection
    $conn = new mysqli($host, $username, $password, $database);

    // Check connection
    if ($conn->connect_error) {
      die("Connection failed: " . $conn->connect_error);
    }

    // Query to check credentials
    $sql = "SELECT * FROM users WHERE email = '$username'"; // Using email for security
    $result = $conn->query($sql);

    if ($result->num_rows == 0) {
      $error_message = "Invalid username or password.";
    } else {
      // If the query returns a row, user exists
      $row = $result->fetch_assoc();
      // Password verification (IMPORTANT!)
      if (password_verify($password, $row["password"])) {
        // Session creation (to keep user logged in)
        session_start();
        $_SESSION["user_id"] = $row["id"];  // Assuming you have an 'id' column in your table
        $_SESSION["username"] = $row["email"]; // Store the username for convenience

        // Redirect to a welcome page or secure area
        header("Location: welcome.php"); // Replace with your welcome page
        exit();
      } else {
        $error_message = "Incorrect password.";
      }
    }
  }
}
?>
