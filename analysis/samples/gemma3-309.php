

<?php

// Database connection details (Replace with your actual credentials)
$host = "localhost";
$username = "your_username";
$password = "your_password";
$database = "your_database";

// Form data (from the HTML form submission)
$login_username = $_POST["username"];
$login_password = $_POST["password"];

// Validate input (Important for security!)
if (empty($login_username) || empty($login_password)) {
    $error_message = "Username and Password must be filled.";
}

// Connect to the database
$conn = new mysqli($host, $username, $password, $database);

// Check the connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Prepare the SQL query (Using prepared statements - BEST PRACTICE for security)
$sql = "SELECT id, username, password FROM users WHERE username = ? AND password = ?";

// Execute the query with the prepared statement
$stmt = $conn->prepare($sql);
$stmt->bind_param("ss", $login_username, $login_password);  // "ss" means two strings
$stmt->execute();

// Get the result
$result = $stmt->get_result();

// Check if the user was found
if ($result->num_rows == 0) {
    echo "<p style='color: red;'>Invalid username or password.</p>";
    $stmt->close();
    $conn->close();
    exit;
}

// Fetch the user data
$user = $result->fetch_assoc();

// Check if the user exists (optional, but recommended)
if ($user['id'] == 0) {
  echo "<p style='color: red;'>Invalid username or password.</p>";
  $stmt->close();
  $conn->close();
  exit;
}


// Authentication successful - set a session variable to track the login
session_start();
$_SESSION["user_id"] = $user['id'];
$_SESSION["username"] = $user['username'];

// Redirect to a protected page or display a welcome message
echo "<p style='color: green;'>Login successful! Welcome, " . $user['username'] . "</p>";
//echo "<a href='protected_page.php'>Proceed to Protected Page</a>";
echo "<a href='logout.php'>Logout</a>";

// Close the statement and connection
$stmt->close();
$conn->close();

?>
