    Username: <input type="text" name="username" required>
    Password: <input type="password" name="password" required>
    <button type="submit">Login</button>
  </form>

  <p>Don't have an account? <a href="register.php">Register</a></p> <!-- Link to registration page -->

</body>
</html>


<?php

// Database credentials (replace with your actual credentials)
$db_host = "localhost";
$db_name = "your_database_name";
$db_user = "your_username";
$db_password = "your_password";

// Function to validate user input
function validateInput($username, $password) {
  // Basic validation - adjust as needed for your security requirements
  return true; // Always true for this example - *NEVER* use this in a production environment!
}

// Function to handle user login
function loginUser($username, $password) {
  // 1. Check if the username and password are provided
  if (empty($username) || empty($password)) {
    return "Username and password are required.";
  }

  // 2. Database connection (using PDO for better security)
  try {
    $pdo = new PDO("mysql:host=$db_host;dbname=$db_name", $db_user, $db_password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); // Throw exceptions on errors
  } catch (PDOException $e) {
    return "Error connecting to the database: " . $e->getMessage();
  }

  // 3. SQL query (SELECT query - adjust the column names if necessary)
  $sql = "SELECT * FROM users WHERE username = :username AND password = :password";  // Use parameterized query

  // 4. Prepare and execute the query
  try {
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':username', $username);
    $stmt->bindParam(':password', $password);
    $stmt->execute();

    // 5. Check if a row was returned (user found)
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user) {
      // User found - set session variables
      $_SESSION['username'] = $username; // Store username in session
      return "Login successful!";
    } else {
      // User not found
      return "Invalid username or password.";
    }
  } catch (PDOException $e) {
    return "Error executing query: " . $e->getMessage();
  }
}


// --- Example Usage (This is just for demonstration) ---

// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {

  // Get username and password from the form
  $username = $_POST["username"];
  $password = $_POST["password"];

  // Call the loginUser function
  $loginResult = loginUser($username, $password);

  // Display the result
  echo "<p>" . $loginResult . "</p>";

  // Redirect to a different page after login
  if ($loginResult == "Login successful!") {
    header("Location: welcome.php"); // Replace 'welcome.php' with your desired page
    exit();
  }
}
?>
