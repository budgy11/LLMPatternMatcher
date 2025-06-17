    Username: <input type="text" name="username" placeholder="Your Username" value="<?php echo htmlspecialchars($_POST['username'] ?? ''); ?>">
    Password: <input type="password" name="password" placeholder="Your Password">
    <button type="submit">Login</button>
  </form>

  <p>Not have an account? <a href="register.php">Register</a></p>

</body>
</html>


<?php

// Database connection details (replace with your actual credentials)
$servername = "localhost";
$username = "your_username";
$password = "your_password";
$dbname = "your_database_name";

// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  // Get username and password from form
  $username = $_POST["username"];
  $password = $_POST["password"];

  // Validate input (important for security!)
  if (empty($username) || empty($password)) {
    $error[] = "Username and Password must be filled.";
  }

  // Connect to the database
  $conn = new mysqli($servername, $username, $password, $dbname);

  // Check connection
  if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
  }

  // Prepare the SQL query with prepared statements (HIGHLY RECOMMENDED)
  $sql = "SELECT id, username, password FROM users WHERE username = ? AND password = ?";
  $stmt = $conn->prepare($sql);

  if ($stmt === false) {
    die("Error preparing statement: " . $conn->error);
  }

  // Bind parameters to the statement
  $stmt->bind_param("ss", $username, $password);  // "ss" means two strings

  // Execute the statement
  if (!$stmt->execute()) {
    echo "Error: " . $stmt->error;
    $stmt->close();
    $conn->close();
    exit();
  }

  // Get the result
  $result = $stmt->get_result();

  // Check if any rows were returned
  if ($result->num_rows > 0) {
    // User found!
    // You can now redirect to a different page or display a welcome message
    $row = $result->fetch_assoc();
    //Set session variables.  This is generally the best way to manage user data.
    session_start();
    $_SESSION["user_id"] = $row["id"];
    $_SESSION["username"] = $row["username"];

    echo "Login successful!  Redirecting...";
    header("Location: welcome.php"); // Redirect to a welcome page or a secure area
    $stmt->close();
    $conn->close();
    exit();
  } else {
    // User not found
    echo "Invalid username or password.";
    $stmt->close();
    $conn->close();
    exit();
  }
}

?>
