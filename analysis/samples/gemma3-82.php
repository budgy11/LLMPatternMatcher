
<!DOCTYPE html>
<html>
<head>
  <title>Login</title>
</head>
<body>

  <h2>Login</h2>

  <form method="post" action="">
    Username: <input type="text" name="username" required><br><br>
    Password: <input type="password" name="password" required><br><br>
    <input type="submit" value="Login">
  </form>

  <br>
  <a href="register.php">Don't have an account? Register here.</a>

</body>
</html>


// ... (previous code) ...

  // Hash the password before storing it in the database
  $hashed_password = password_hash($password, PASSWORD_DEFAULT);  // Use a strong salt automatically
  $sql = "SELECT * FROM users WHERE username = '$username' AND password = '$hashed_password'";

// ... (rest of the loginUser function) ...


// ... (previous code) ...

  if ($result->num_rows > 0) {
    // User found, create a session
    $user_data = $result->fetch_assoc();
    $_SESSION['username'] = $user_data['username'];
    $_SESSION['user_id'] = $user_data['id'];

    // Verify the entered password with the hashed password in the database
    if (password_verify($password, $user_data['password'])) {
      // Password matches, login successful
      header("Location: secure_page.php");
      exit();
    } else {
      // Password does not match, login failed
      echo "<p style='color:red;'>Invalid username or password.</p>";
    }
  }


<?php

// Database configuration (Replace with your actual values)
$db_host = "localhost";
$db_name = "your_database_name";
$db_user = "your_db_user";
$db_password = "your_db_password";

// Function to validate user input (basic example - improve security in production)
function validate_input($username, $password) {
  $username = trim($username);
  $password = trim($password);
  return $username && $password; // Check if both are non-empty
}

// Function to handle user login
function login_user($username, $password) {
  // Database connection
  $conn = new mysqli($db_host, $db_user, $db_password, $db_name);

  // Check connection
  if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
  }

  // SQL query to authenticate the user
  $sql = "SELECT * FROM users WHERE username = '$username' AND password = PASSWORD('$password')";

  $result = $conn->query($sql);

  if ($result->num_rows > 0) {
    // User found, set session variables
    session_start();
    $user_data = $result->fetch_assoc();
    $_SESSION['user_id'] = $user_data['id'];
    $_SESSION['username'] = $user_data['username'];
    return true; // Login successful
  } else {
    return false; // Login failed
  }

  $conn->close();
}

// Example usage (handle form submission)
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $username = $_POST["username"];
  $password = $_POST["password"];

  if (validate_input($username, $password)) {
    if (login_user($username, $password)) {
      header("Location: welcome.php"); // Redirect to a welcome page
      exit();
    } else {
      $error = "Invalid username or password.";
    }
  } else {
    $error = "Please enter both username and password.";
  }
}
?>
