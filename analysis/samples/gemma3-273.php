    Username: <input type="text" name="username" placeholder="Enter your username"><br><br>
    Password: <input type="password" name="password" placeholder="Enter your password"><br><br>
    <input type="submit" value="Login">
  </form>

</body>
</html>


<?php

// Database credentials (replace with your actual credentials)
$db_host = "localhost";
$db_name = "your_database_name";
$db_user = "your_database_user";
$db_pass = "your_database_password";

// Session handling
session_start();


/**
 * Logs in a user.
 *
 * @param string $username The user's login username.
 * @param string $password The user's login password.
 *
 * @return bool True on successful login, false otherwise.
 */
function loginUser(string $username, string $password) {
  // Validate inputs (basic - you should add more robust validation)
  if (empty($username) || empty($password)) {
    return false;
  }

  // Connect to the database
  $conn = new mysqli($db_host, $db_user, $db_pass, $db_name);

  // Check connection
  if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
  }

  // Prepare the SQL query
  $sql = "SELECT * FROM users WHERE username = ? AND password = ?";  //Use parameterized queries to prevent SQL Injection
  $stmt = $conn->prepare($sql);

  if ($stmt === false) {
    die("Error preparing statement: " . $conn->error);
  }

  // Bind parameters
  $stmt->bind_param("ss", $username, $password); // "ss" indicates two string parameters

  // Execute the query
  if (!$stmt->execute()) {
    die("Query failed: " . $stmt->error);
  }

  // Get the result
  $result = $stmt->get_result();

  // Check if any users were found
  if ($result->num_rows > 0) {
    $user = $result->fetch_assoc();  // Fetch the user data as an associative array
    // Set session variables to identify the user
    $_SESSION['user_id'] = $user['id'];
    $_SESSION['username'] = $user['username'];

    // Optionally, redirect the user to a specific page
    // header("Location: user_dashboard.php");  //Example of redirecting
    return true;
  } else {
    return false;
  }

  // Close the statement and connection
  $stmt->close();
  $conn->close();
}


// Login form handling (Example -  This code will be in your login.php file)
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $username = $_POST["username"];
  $password = $_POST["password"];

  if (loginUser($username, $password)) {
    echo "Login successful!  Redirecting...";
    //  Redirect after successful login
    header("Location: user_dashboard.php");  //Or wherever you want to redirect
    exit();
  } else {
    echo "Invalid username or password.";
  }
}

?>
