        Username: <input type="text" name="username">
        Password: <input type="password" name="password">
        <button type="submit">Login</button>
    </form>

</body>
</html>


<?php

// Database credentials - Replace with your actual values
$db_host = "localhost";
$db_name = "your_database_name";
$db_user = "your_username";
$db_password = "your_password";

function loginUser($username, $password) {
  // Connect to the database
  $conn = new mysqli($db_host, $db_user, $db_password, $db_name);

  // Check connection
  if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
  }

  // SQL query to fetch user data
  $sql = "SELECT * FROM users WHERE username = '$username' AND password = '$password'";
  $result = $conn->query($sql);

  if ($result->num_rows > 0) {
    // User found, create a session
    // Set a session variable to indicate login
    session_start();
    $_SESSION['username'] = $username; // Store username in session
    $_SESSION['user_id'] = $result->fetch_assoc()['id']; // Example: Store user ID as well
    return true; // Login successful
  } else {
    return false; // Login failed
  }

  $conn->close();
}


// Example Usage (from a form submission)
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $username = $_POST["username"];
  $password = $_POST["password"];

  if (loginUser($username, $password)) {
    // User logged in successfully
    header("Location: /welcome.php"); // Redirect to a welcome page (replace with your URL)
    exit();
  } else {
    // Login failed
    echo "<p style='color:red;'>Invalid username or password.</p>";
  }
}

// HTML Form for Login (to be embedded in your HTML page)
?>
