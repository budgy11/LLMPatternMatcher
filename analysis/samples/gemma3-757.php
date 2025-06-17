    Username: <input type="text" name="username" required><br><br>
    Password: <input type="password" name="password" required><br><br>
    <input type="submit" value="Login">
  </form>

</body>
</html>


<?php

// Database credentials (replace with your actual values)
$host = "localhost";
$username = "your_username";
$password = "your_password";
$database = "your_database_name";

// Function to handle user login
function loginUser($username, $password) {
  // Connect to the database
  $conn = new mysqli($host, $username, $password, $database);

  // Check connection
  if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
  }

  // SQL query to retrieve user data
  $sql = "SELECT id, username, password FROM users WHERE username = '$username'";
  $result = $conn->query($sql);

  if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    // Verify the password
    if (password_verify($password, $row['password'])) {
      // Login successful
      $_SESSION['user_id'] = $row['id'];
      $_SESSION['username'] = $username;
      return true;
    } else {
      // Incorrect password
      return false;
    }
  } else {
    // User not found
    return false;
  }

  // Close the connection
  $conn->close();
}

// Example usage:

// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $username = $_POST["username"];
  $password = $_POST["password"];

  if (loginUser($username, $password)) {
    // Login successful - redirect to the home page or another secure page
    header("Location: home.php"); // Replace 'home.php' with your desired destination
    exit();
  } else {
    // Login failed - display an error message
    echo "<p style='color:red;'>Invalid username or password.</p>";
  }
}

?>
