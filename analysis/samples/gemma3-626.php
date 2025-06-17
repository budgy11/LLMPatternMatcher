
<!DOCTYPE html>
<html>
<head>
  <title>User Login</title>
</head>
<body>

  <h1>User Login</h1>

  <form method="post" action="">
    <label for="username">Username:</label>
    <input type="text" id="username" name="username">

    <label for="password">Password:</label>
    <input type="password" id="password" name="password">

    <button type="submit">Login</button>
  </form>

</body>
</html>


// ... (database connection code) ...

// Example using a hypothetical secure password hashing function
function hashPassword($password) {
  // This is just a placeholder - REPLACE with a real secure hashing library
  return password_hash($password, PASSWORD_BCRYPT); // or Argon2, etc.
}

// ... inside loginUser function, before storing the password ...
$hashedPassword = hashPassword($password);
$sql = "INSERT INTO users (username, password, ... ) VALUES (?, ?, ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("sss", $username, $hashedPassword, ...); // "sss" indicates 3 string parameters
$stmt->execute();
$stmt->close();
$conn->close();


<?php

// Database connection details
$db_host = "localhost";
$db_name = "your_database_name";
$db_user = "your_db_username";
$db_password = "your_db_password";

// Function to handle user login
function loginUser($username, $password) {
  // Connect to the database
  $conn = new mysqli($db_host, $db_user, $db_password, $db_name);

  // Check connection
  if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
  }

  // SQL query to retrieve user data
  $sql = "SELECT * FROM users WHERE username = '$username' AND password = '$password'";

  $result = $conn->query($sql);

  if ($result->num_rows > 0) {
    // User found, return user data
    $user = $result->fetch_assoc();
    return $user;
  } else {
    // User not found
    return false;
  }

  $conn->close();
}


// Example usage (This will be in a form submission handler)
// Assuming the form contains fields: username and password
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $username = $_POST["username"];
  $password = $_POST["password"];

  $loggedInUser = loginUser($username, $password);

  if ($loggedInUser) {
    // User is logged in
    // Store session data (important for maintaining login state)
    session_start(); // Start the session if it's not already running
    $_SESSION["username"] = $loggedInUser["username"];
    $_SESSION["id"] = $loggedInUser["id"];  // Or other user ID fields

    // Redirect to a protected page or display a welcome message
    header("Location: /protected_page.php"); // Change /protected_page.php to the correct path
    exit();

  } else {
    // Login failed
    $error = "Invalid username or password.";
  }
}
?>
