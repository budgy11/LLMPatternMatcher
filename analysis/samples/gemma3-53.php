
    <br><br>

    <button type="submit">Login</button>
  </form>

</body>
</html>


// Inside the 'if ($conn->connect_error)' block, replace with:
//  $stmt = $conn->prepare("SELECT username, password FROM users WHERE username = ? AND password = ?");
//  $stmt->bind_param("ss", $username, $password); // "ss" means two string parameters
//  $result = $stmt->execute();
//  $row = $result->fetch_assoc();
//  $stmt->close();

// Remember to close the statement after use.


<?php

// Database connection details
$host = "localhost";
$username = "your_username";
$password = "your_password";
$database = "your_database";

// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  // Get the username and password from the form
  $username = $_POST["username"];
  $password = $_POST["password"];

  // Validate the input (important for security!)
  if (empty($username) || empty($password)) {
    $error_message = "Username and Password cannot be empty.";
  } else {
    // SQL query to authenticate the user
    $sql = "SELECT * FROM users WHERE username = '$username' AND password = '$password'";
    $result = mysqli_query($host, $sql);

    if ($result) {
      // User found, set session variables
      if (mysqli_num_rows($result) > 0) {
        // User exists, set session variables
        $_SESSION["username"] = $username;
        $_SESSION["user_id"] = mysqli_fetch_assoc($result)['id']; // Assuming you have an 'id' column in the users table
        echo "<p>Login successful!</p>";
        // Redirect to a protected page or display a welcome message
        header("Location: protected_page.php"); // Redirect to a protected page
        exit();
      } else {
        $error_message = "Invalid username or password.";
      }
    } else {
      $error_message = "Database error.";
    }
  }
}
?>
