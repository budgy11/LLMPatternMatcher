    Username: <input type="text" name="username" placeholder="Your Username"><br><br>
    Password: <input type="password" name="password" placeholder="Your Password"><br><br>
    <button type="submit">Login</button>
  </form>

  <br>
  <a href="register.php">Not a user? Register here</a>

</body>
</html>


<?php

// Database configuration (replace with your actual credentials)
$db_host = "localhost";
$db_name = "your_database_name";
$db_user = "your_username";
$db_pass = "your_password";

// Function to check if the login is successful
function is_login_successful($result) {
  return $result->rowCount() > 0;
}

// Function to handle login
function loginUser($username, $password) {
  // Connect to the database
  try {
    $pdo = new PDO("mysql:host=$db_host;dbname=$db_name;charset=utf8", $db_user, $db_pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);  // For error handling
  } catch (PDOException $e) {
    // Handle database connection errors
    echo "Database connection failed: " . $e->getMessage();
    return false;
  }

  // Prepare the SQL query
  $stmt = $pdo->prepare("SELECT id, username, password FROM users WHERE username = ?");

  // Bind the username parameter
  $stmt->bindParam(1, $username);

  // Execute the query
  $stmt->execute();

  // Get the result
  $result = $stmt->fetch(PDO::FETCH_ASSOC);  // Fetch as an associative array

  // Check if a user with the given username exists
  if ($result) {
    // Verify the password
    if (password_verify($password, $result['password'])) {
      // Login successful - set session variables
      session_start();
      $_SESSION['user_id'] = $result['id'];
      $_SESSION['username'] = $result['username'];
      return true;
    } else {
      // Incorrect password
      return false;
    }
  } else {
    // User not found
    return false;
  }
}


// Example Usage (Demonstration -  Use with a form in a real application)
// Assuming you have a form with fields 'username' and 'password'

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $username = $_POST["username"];
  $password = $_POST["password"];

  if (loginUser($username, $password)) {
    // Redirect to a secure page or display a success message
    header("Location: welcome.php"); // Replace with your welcome page
    exit();
  } else {
    // Display an error message
    echo "<p style='color:red;'>Invalid username or password.</p>";
  }
}


?>
