    <button type="submit">Login</button>
  </form>

  <br>
  <a href="register.php">Don't have an account? Register here.</a>

</body>
</html>


<?php

// Database configuration (Replace with your actual details)
$db_host = "localhost";
$db_name = "your_database_name";
$db_user = "your_db_user";
$db_pass = "your_db_password";

// Function to handle user login
function loginUser($username, $password) {
  // Connect to the database
  $conn = new mysqli($db_host, $db_user, $db_pass, $db_name);

  // Check connection
  if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
  }

  // Prepare and execute the query
  $query = "SELECT * FROM users WHERE username = ? AND password = ?";
  $stmt = $conn->prepare($query);
  $stmt->bind_param("ss", $username, $password); // "ss" indicates two strings
  $stmt->execute();

  // Get the result
  $result = $stmt->get_result();

  // Check if a user was found
  if ($result->num_rows > 0) {
    $user = $result->fetch_assoc();
    // Set session variables
    $_SESSION['user_id'] = $user['id']; // Assuming you have an 'id' column in your users table
    $_SESSION['username'] = $user['username'];

    // Redirect to a protected page or display a success message
    return true; // Login successful
  } else {
    return false; // Login failed
  }

  // Close the statement and connection
  $stmt->close();
  $conn->close();
}

// Example Usage (This is just for demonstration, you'll need to integrate this with a form)
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $username = $_POST["username"];
  $password = $_POST["password"];

  if (loginUser($username, $password)) {
    // User is logged in
    echo "Login successful! You are now logged in.";
    // Redirect to a protected page here.  Example:
    // header("Location: protected_page.php");
    // exit();
  } else {
    echo "Invalid username or password.";
  }
}
?>
