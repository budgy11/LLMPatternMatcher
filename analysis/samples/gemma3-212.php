    <br><br>
    <button type="submit">Login</button>
  </form>

</body>
</html>


<?php

// Database connection details (replace with your actual credentials)
$host = "localhost";
$username = "your_username";
$password = "your_password";
$database = "your_database";

// Function to handle the login process
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
    // User found
    $user_data = $result->fetch_assoc();
    $hashed_password = $user_data['password']; // Get the stored hash

    // Verify password (using password_verify) - This is the secure way
    if (password_verify($password, $hashed_password)) {
      // Password is correct!
      // Set session variables to indicate successful login
      $_SESSION['user_id'] = $user_data['id'];
      $_SESSION['username'] = $username;

      return true; // Login successful
    } else {
      // Password mismatch
      return false; // Login failed
    }
  } else {
    // User not found
    return false; // Login failed
  }

  // Close the connection
  $conn->close();
}


// Example usage:

// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  // Get username and password from the form
  $username = $_POST["username"];
  $password = $_POST["password"];

  // Validate the input (basic validation - improve for production)
  if (empty($username) || empty($password)) {
    echo "Username and Password cannot be empty.";
  } else {
    // Call the loginUser function
    if (loginUser($username, $password)) {
      // Login successful - Redirect to the desired page
      header("Location: welcome.php"); // Replace 'welcome.php' with your welcome page
      exit();
    } else {
      // Login failed
      echo "Invalid username or password.";
    }
  }
}
?>
